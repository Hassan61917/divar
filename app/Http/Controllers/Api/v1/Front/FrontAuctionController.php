<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\AuctionResource;
use App\Models\Auction;
use App\ModelServices\Ads\AuctionService;
use Illuminate\Http\JsonResponse;

class FrontAuctionController extends AuthUserController
{
    protected string $resource = AuctionResource::class;

    public function __construct(
        private AuctionService $auctionService
    )
    {
    }

    public function index(): JsonResponse
    {
        $stateId = $this->authUser()->profile->state_id;
        $cityId = $this->authUser()->profile->city_id;
        $auctions = $this->auctionService->getFrontAuctions($stateId, $cityId);
        return $this->ok($this->paginate($auctions));
    }

    public function show(Auction $auction): JsonResponse
    {
        $auction->load("user", "ads", "offers");
        return $this->ok($auction);
    }
}
