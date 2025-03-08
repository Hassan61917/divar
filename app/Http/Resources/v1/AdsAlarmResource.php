<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AdsAlarmResource extends AppJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "min_price" => $this->min_price,
            "max_price" => $this->max_price,
            "category" => $this->mergeRelation(CategoryResource::class, "category"),
            "order" => $this->mergeRelation(AlarmOrderResource::class, "order")
        ];
    }
}
