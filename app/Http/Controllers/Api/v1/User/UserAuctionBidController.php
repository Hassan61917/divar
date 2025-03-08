<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserAuctionBidRequest;
use App\Http\Resources\AuctionBidResource;
use App\Models\AuctionBid;
use App\ModelServices\Ads\AuctionOfferService;
use Illuminate\Http\JsonResponse;

class UserAuctionBidController extends Controller
{
    protected string $resource = AuctionBidResource::class;

    public function __construct(
        private AuctionOfferService $offerService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $offers = $this->offerService->getOffersFor($this->authUser());
        return $this->ok($this->paginate($offers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAuctionBidRequest $request): JsonResponse
    {
        $data = $request->validated();
        $offer = $this->offerService->make($this->authUser(), $data);
        $offer->load("auction");
        return $this->ok($offer);
    }

    /**
     * Display the specified resource.
     */
    public function show(AuctionBid $offer): JsonResponse
    {
        $offer->load("auction");
        return $this->ok($offer);
    }
}
