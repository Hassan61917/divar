<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AuctionBidResource extends AppJsonResource
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
            "price" => $this->price,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "auction" => $this->mergeRelation(AuctionResource::class, "auction")
        ];
    }
}
