<?php

namespace App\Handlers\Ads;

use App\Handlers\Ads\Rules\CheckLimit;
use App\Handlers\Ads\Rules\CheckStatus;
use App\Handlers\Ads\Rules\ExistsAds;
use App\Handlers\ModelHandler;

class AdsHandler extends ModelHandler
{
    protected array $rules = [
        CheckStatus::class,
        ExistsAds::class,
        CheckLimit::class
    ];
}
