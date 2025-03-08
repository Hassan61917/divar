<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AdsResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "location" => $this->location,
            "isValidated" => $this->is_validated,
            "title" => $this->title,
            "description" => $this->description,
            "price" => $this->price,
            "chat" => $this->chat,
            "call" => $this->call,
            "phone" => $this->mergeWhen($this->call, $this->phone),
            "fields" => $this->fields,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "category" => $this->mergeRelation(CategoryResource::class, "category"),
        ];
    }
}
