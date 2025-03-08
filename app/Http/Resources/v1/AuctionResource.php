<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AuctionResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "start_price" => $this->start_price,
            "step" => $this->step,
            "started_at" => $this->started_at,
            "finished_at" => $this->finished_at,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "ads" => $this->mergeRelation(AdsResource::class, "ads"),
            "bids" => $this->mergeRelations(AuctionBidResource::class, "bids"),
            "bidsCount" => $this->mergeCount("bids")
        ];
    }
}
