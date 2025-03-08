<?php

namespace App\Handlers\Ads\Rules;

use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\Models\Ads;
use App\ModelServices\Ads\AdsService;
use Illuminate\Database\Eloquent\Model;

class ExistsAds implements IModelHandler
{
    public function __construct(
        private AdsService $adsService
    )
    {
    }


    public function handle(Model|Ads $model, array $params = []): void
    {
        $title = $model->title;
        $description = $model->description;
        $model = $this->adsService->getAdsFor($model->user)->published();
        $exists = $model
            ->where("title", "LIKE", "%$title%")
            ->orWhere("title", "LIKE", "%$description%")
            ->exists();
        if ($exists) {
            throw new ModelException("the ads already exists");
        }
    }
}
