<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserAuctionRequest;
use App\Http\Resources\v1\AuctionResource;
use App\Models\Auction;
use App\ModelServices\Ads\AuctionService;
use Illuminate\Http\JsonResponse;

class UserAuctionController extends AuthUserController
{
    protected string $resource = AuctionResource::class;

    public function __construct(
        private AuctionService $auctionService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $auctions = $this->auctionService->getAuctionsFor($this->authUser());
        return $this->ok($this->paginate($auctions));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAuctionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $auction = $this->auctionService->make($this->authUser(), $data);
        $auction->load("ads");
        return $this->ok($auction);
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction): JsonResponse
    {
        $auction->load("user", "ads", "offers");
        return $this->ok($auction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserAuctionRequest $request, Auction $auction): JsonResponse
    {
        if ($auction->isInProcess()) {
            return $this->error(422, "only auctions that hasn't been started can be updated");
        }
        $data = $request->validated();
        $auction->update($data);
        $auction->load("ads");
        return $this->ok($auction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction): JsonResponse
    {
        $this->auctionService->delete($auction);
        return $this->deleted();
    }
}
