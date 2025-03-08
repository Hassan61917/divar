<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AdsFieldResource extends AppJsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "title"=>$this->title,
            "value"=>$this->value,
            "rule"=>$this->rule,
            "category"=>$this->mergeRelation(CategoryResource::class,"category")
        ];
    }
}
