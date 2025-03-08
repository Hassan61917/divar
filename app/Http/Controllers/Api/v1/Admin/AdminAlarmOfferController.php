<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminAlarmOfferRequest;
use App\Http\Resources\v1\AlarmOfferResource;
use App\Models\AdsOffer;
use App\Models\AlarmOffer;
use Illuminate\Http\JsonResponse;

class AdminAlarmOfferController extends Controller
{
    protected string $resource = AlarmOfferResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $offers = AlarmOffer::query()->with("category")->withCount("orders");
        return $this->ok($this->paginate($offers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAlarmOfferRequest $request): JsonResponse
    {
        $data = $request->validated();
        $offer = AlarmOffer::create($data);
        $offer->load("category");
        return $this->ok($offer);
    }

    /**
     * Display the specified resource.
     */
    public function show(AlarmOffer $offer): JsonResponse
    {
        $offer->load("category")->loadCount("orders");
        return $this->ok($offer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminAlarmOfferRequest $request, AdsOffer $offer): JsonResponse
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
