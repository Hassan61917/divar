<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class TicketResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "title" => $this->title,
            "status" => $this->status,
            "rate" => $this->rate,
            "category" => $this->mergeRelation(TicketCategoryResource::class, "category"),
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "messages" => $this->mergeRelations(TicketMessageResource::class, "messages"),
        ];
    }
}
