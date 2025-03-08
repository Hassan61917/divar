<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class QuestionResource extends AppJsonResource
{
    protected array $resources = [];
    public function toArray(Request $request): array
    {
        return [
            "question" => $this->question,
            "answer" => $this->answer,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "ads" => $this->mergeRelation(AdsResource::class, "ads"),
        ];
    }
}
