<?php

namespace App\ModelServices\Ads;

use App\Enums\AdsStatus;
use App\Enums\ShowStatus;
use App\Models\Ads;
use Illuminate\Database\Eloquent\Builder;

class FrontAdsService implements IFrontAdsService
{
    public function getAdvertises(int $stateId, int $cityId, array $relations = []): Builder
    {
        return Ads::query()
            ->select("ads.*")
            ->inCity($stateId,$cityId)
            ->leftJoin("ladder_orders", "ladder_orders.ads_id", "=", "ads.id")
            ->where(function ($query) {
                return $query
                    ->where("ladder_orders.status", ShowStatus::Showing->value)
                    ->orWhere("ads.status", AdsStatus::Published->value);
            })->orderBy("ladder_orders.show_at", "desc");
    }
}
