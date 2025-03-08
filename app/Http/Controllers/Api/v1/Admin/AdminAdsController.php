<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserUpdateAdsRequest;
use App\Http\Resources\v1\AdsResource;
use App\Models\Ads;
use App\ModelServices\Ads\AdsService;
use Illuminate\Http\JsonResponse;

class AdminAdsController extends Controller
{
    protected string $resource = AdsResource::class;

    public function __construct(
        private AdsService $adsService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $ads = $this->adsService->getAllAds(["category"]);
        return $this->ok($this->paginate($ads));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ads $advertise): JsonResponse
    {
        $advertise->load("category", "user");
        return $this->ok($advertise);
    }

    public function draft(Ads $advertise): JsonResponse
    {
        $this->adsService->draft($advertise);
        return $this->ok($advertise);
    }

    public function publish(Ads $advertise): JsonResponse
    {
        $this->adsService->publish($advertise);
        $advertise->load("category", "user");
        return $this->ok($advertise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateAdsRequest $request, Ads $advertise): JsonResponse
    {
        $data = $request->validated();
        $this->adsService->update($advertise, $data);
        $advertise->load("category", "user");
        return $this->ok($advertise);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ads $advertise): JsonResponse
    {
        $advertise->delete();
        return $this->deleted();
    }
}
