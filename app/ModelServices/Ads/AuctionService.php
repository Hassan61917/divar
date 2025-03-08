<?php

namespace App\ModelServices\Ads;

use App\Events\AuctionWasCreated;
use App\Handlers\Auction\AuctionHandler;
use App\Models\Auction;
use App\Models\User;
use App\ModelServices\Financial\WalletService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuctionService
{
    public function __construct(
        private AuctionHandler $auctionHandler,
        private WalletService  $walletService
    )
    {
    }

    public function getFrontAuctions(int $stateId, int $cityId, array $relations = []): Builder
    {
        return Auction::query()
            ->whereHas("ads", fn($ads) => $ads->inCity($stateId, $cityId))
            ->inProcess()
            ->with($relations);
    }

    public function getAllAuctions(array $relations = []): Builder
    {
        return Auction::query()->with($relations);
    }

    public function getAuctionsFor(User $user, array $relations = []): HasMany
    {
        return $user->auctions()->with($relations);
    }

    public function hasInProcessAuction(User $user): bool
    {
        return $this->getAuctionsFor($user)->inProcess()->exists();
    }

    public function make(User $user, array $data): Auction
    {
        $data["step"] = $data["step"] ?? 1;
        $data["started_at"] = $data["started_at"] ?? now();
        $data["finished_at"] = now()->addDay();
        $auction = $user->auctions()->make($data);
        $this->auctionHandler->handle($auction);
        $this->walletService->withdraw($user->wallet, $auction->ads->price * 0.1);
        $auction->save();
        AuctionWasCreated::dispatch($auction);
        return $auction;
    }

    public function finish(Auction $auction): void
    {
        $auction->update(["finished_at" => now()]);
        $auction->offers()->orderBy("price", "desc")->skip(1)->delete();
        $this->walletService->deposit($auction->user->wallet, $auction->lastBid()->price);
        $this->walletService->deposit($auction->user->wallet, $auction->ads->price * 0.1);
    }

    public function delete(Auction $auction): void
    {
        $amount = $auction->ads->price * 0.1;
        if ($lastOffer = $auction->lastBid()) {
            $this->walletService->deposit($lastOffer->user->wallet, $lastOffer->price + $amount);
        } else {
            $this->walletService->deposit($auction->user->wallet, $amount);
        }
        $auction->delete();
    }
}
