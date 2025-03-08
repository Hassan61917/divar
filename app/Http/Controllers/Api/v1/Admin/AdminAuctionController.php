<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\AuctionResource;
use App\Models\Auction;
use App\ModelServices\Ads\AuctionService;
use Illuminate\Http\JsonResponse;

class AdminAuctionController extends Controller
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
        $auctions = $this->auctionService->getAllAuctions(["user"])->withCount("offers");
        return $this->ok($this->paginate($auctions));
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
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction): JsonResponse
    {
        $this->auctionService->delete($auction);
        return $this->deleted();
    }
}
