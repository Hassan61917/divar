<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AlarmOfferResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "price" => $this->price,
            "count" => $this->count,
            "duration" => $this->duration,
            "ordersCount" => $this->mergeCount("orders"),
            "category" => $this->mergeRelation(CategoryResource::class, "category")
        ];
    }
}
