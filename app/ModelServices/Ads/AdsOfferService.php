<?php

namespace App\ModelServices\Ads;

use App\Models\AdsOffer;
use Illuminate\Database\Eloquent\Builder;

class AdsOfferService
{
    public function getOffers(): Builder
    {
        return AdsOffer::query()->with("category")->withCount("orders");
    }

    public function make(array $data): AdsOffer
    {
        return AdsOffer::create($data);
    }


}
