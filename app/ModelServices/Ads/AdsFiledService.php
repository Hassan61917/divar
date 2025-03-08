<?php

namespace App\ModelServices\Ads;

use App\Models\Ads;
use App\Models\AdsField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AdsFiledService
{
    public function getFieldsFor(Ads $advertise): Collection
    {
        $category = $advertise->category;
        $ids = [$category->id, ...$category->getParentsIds()];
        return AdsField::query()->whereIn("category_id", $ids)->get();
    }

    public function getFieldsRules(Ads $ads): array
    {
        $fields = $this->getFieldsFor($ads);
        $result = [];
        foreach ($fields as $field) {
            $result[$field->title] = $field->rule;
        }
        return $result;
    }

    public function getFields(array $relations = []): Builder
    {
        return AdsField::query()->with($relations);
    }

    public function makeField(array $data): AdsField
    {
        return AdsField::create($data);
    }
}
