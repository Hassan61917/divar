<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AlarmOrderResource extends AppJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "expiredAt" => $this->expired_at,
            "offer" => $this->mergeRelation(AlarmOfferResource::class, "offer"),
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "ads" => $this->mergeRelations(AdsResource::class, "ads"),
            "alarms" => $this->mergeRelations(AdsAlarmResource::class, "alarms")
        ];
    }
}
