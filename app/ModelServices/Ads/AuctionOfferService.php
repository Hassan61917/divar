<?php

namespace App\ModelServices\Ads;

use App\Handlers\AuctionOffer\AuctionBidHandler;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\User;
use App\ModelServices\Financial\WalletService;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuctionOfferService
{
    public function __construct(
        private AuctionBidHandler $offerHandler,
        private WalletService     $walletService
    )
    {
    }

    public function getOffersFor(User $user, array $relations = []): HasMany
    {
        return $user->auctionOffers()->with($relations);
    }

    public function make(User $user, array $data): AuctionBid
    {
        $offer = $user->auctionOffers()->make($data);
        $this->offerHandler->handle($offer);
        $auction = Auction::find($data['auction_id']);
        if ($lastOffer = $auction->lastOffer()) {
            $this->walletService->deposit($lastOffer->user->wallet, $lastOffer->price);
        }
        $this->walletService->withdraw($user->wallet, $data["price"]);
        $offer->save();
        return $offer;
    }
}
