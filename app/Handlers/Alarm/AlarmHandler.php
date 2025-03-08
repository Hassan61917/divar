<?php

namespace App\Handlers\Alarm;

use App\Exceptions\ModelException;
use App\Handlers\ModelHandler;
use App\Models\AdsAlarm;

class AlarmHandler extends ModelHandler
{
    protected array $rules = [
        "exists", "category"
    ];
    private function exists(AdsAlarm $alarm): void
    {
        $data = [
            "category_id" => $alarm->category_id,
            "title" => $alarm->title,
            "min_price" => $alarm->min_price,
            "max_price" => $alarm->max_price
        ];
        if (AdsAlarm::query()->where($data)->exists()) {
            throw new ModelException("alarm already exists");
        }
    }

    private function category(AdsAlarm $alarm): void
    {
        $orderCategory = $alarm->order->offer->category;
        $category = $alarm->category;
        if (!$orderCategory->is($category) && !$orderCategory->isMyChild($category)) {
            throw new ModelException("category is not valid");
        }
    }
}
