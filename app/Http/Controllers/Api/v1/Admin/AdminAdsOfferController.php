<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminAdsOfferRequest;
use App\Models\AdsOffer;
use App\ModelServices\Ads\AdsOfferService;
use Illuminate\Http\JsonResponse;

class AdminAdsOfferController extends Controller
{
    protected string $resource = AdsOffer::class;

    public function __construct(
        private AdsOfferService $offerService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $offers = $this->offerService->getOffers();
        return $this->ok($this->paginate($offers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAdsOfferRequest $request): JsonResponse
    {
        $data = $request->validated();
        $offer = $this->offerService->make($data);
        $offer->load("category");
        return $this->ok($offer);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdsOffer $offer): JsonResponse
    {
        $offer->load("category")->loadCount("orders");
        return $this->ok($offer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminAdsOfferRequest $request, AdsOffer $offer): JsonResponse
    {
        $data = $request->validated();
        $offer->update($data);
        $offer->load("category");
        return $this->ok($offer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdsOffer $offer): JsonResponse
    {
        $offer->delete();
        return $this->deleted();
    }
}
