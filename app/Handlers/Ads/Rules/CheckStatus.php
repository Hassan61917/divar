<?php

namespace App\Handlers\Ads\Rules;

use App\Enums\AdsStatus;
use App\Exceptions\ModelException;
use App\Handlers\IModelHandler;
use App\Models\Ads;
use Illuminate\Database\Eloquent\Model;

class CheckStatus implements IModelHandler
{
    public function handle(Model|Ads $model, array $params = []): void
    {
        $status = $model->status;
        if ($status == AdsStatus::Published->value) {
            throw new ModelException("already published");
        }
        if ($status == AdsStatus::Draft->value) {
            throw new ModelException("you must complete ads before publishing it");
        }
    }
}
