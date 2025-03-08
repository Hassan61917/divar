<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\AdsResource;
use App\Models\Ads;
use App\ModelServices\Ads\IFrontAdsService;
use App\ModelServices\Social\VisitService;
use Illuminate\Http\JsonResponse;

class FrontAdsController extends Controller
{
    protected string $resource = AdsResource::class;

    public function __construct(
        private IFrontAdsService $adsService,
        private VisitService     $visitService
    )
    {
    }

    public function index(): JsonResponse
    {
        $stateId = $this->authUser()->profile->state_id;
        $cityId = $this->authUser()->profile->city_id;
        $ads = $this->adsService->getAdvertises($stateId, $cityId);
        return $this->ok($this->paginate($ads));
    }

    public function show(Ads $advertise): JsonResponse
    {
        $this->visitService->visit($advertise, $this->authUser(), request()->ip());
        $advertise->load("comments", "questions", "user")->loadCount("visits");
        return $this->ok($advertise);
    }
}
