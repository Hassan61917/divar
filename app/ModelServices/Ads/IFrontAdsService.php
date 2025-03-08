<?php

namespace App\ModelServices\Ads;

use Illuminate\Database\Eloquent\Builder;

interface IFrontAdsService
{
    public function getAdvertises(int $stateId, int $cityId, array $relations = []): Builder;
}
