<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserStoreAdsRequest;
use App\Http\Requests\v1\User\UserUpdateAdsRequest;
use App\Http\Resources\v1\AdsFieldResource;
use App\Http\Resources\v1\AdsResource;
use App\Models\Ads;
use App\ModelServices\Ads\AdsFiledService;
use App\ModelServices\Ads\AdsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAdsController extends AuthUserController
{
    protected string $resource = AdsResource::class;

    public function __construct(
        private AdsService      $adsService,
        private AdsFiledService $filedService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $advertises = $this->adsService->getAdsFor($this->authUser(), ["category"]);
        return $this->ok($this->paginate($advertises));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreAdsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $ads = $this->adsService->makeAds($this->authUser(), $data);
        $ads->load("category");
        return $this->ok($ads);
    }

    public function getFields(Ads $advertise): JsonResponse
    {
        $fields = $this->filedService->getFieldsFor($advertise);
        return $this->ok(null, AdsFieldResource::collection($fields));
    }

    public function saveFields(Request $request, Ads $advertise): JsonResponse
    {
        $rules = $this->filedService->getFieldsRules($advertise);
        $data = $request->validate($rules);
        $this->adsService->updateFields($advertise, $data);
        return $this->ok($advertise);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ads $advertise): JsonResponse
    {
        $advertise->load("category");
        return $this->ok($advertise);
    }

    public function publish(Ads $advertise): JsonResponse
    {
        $advertise = $this->adsService->publish($advertise);
        $advertise->load("category");
        return $this->ok($advertise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateAdsRequest $request, Ads $advertise): JsonResponse
    {
        if (!$advertise->is_validated) {
            return $this->error(422, "you must fill fields before updating");
        }
        $data = $request->validated();
        $advertise = $this->adsService->update($advertise,$data);
        $advertise->load("category");
        return $this->ok($advertise);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteReason(Request $request, Ads $advertise): JsonResponse
    {
        $data = $request->validate([
            "reason_id" => "nullable|integer|exists:delete_reasons,id",
        ]);
        $this->adsService->setDeleteReason($advertise, $data["reason_id"]);
        return $this->ok($advertise);
    }
    public function destroy(Ads $advertise): JsonResponse
    {
        $advertise->delete();
        return $this->deleted();
    }
}
