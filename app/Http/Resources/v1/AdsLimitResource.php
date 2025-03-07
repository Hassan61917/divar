<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AdsLimitResource extends AppJsonResource
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
            "limit" => $this->limit,
            "duration" => $this->duration,
            "category" => $this->mergeRelation(CategoryResource::class, "category")
        ];
    }
}
