<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use App\Models\OfferOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "totalPrice" => $this->total_price,
            "priceAfterDiscount" => $this->mergeWhen($this->discount_price, $this->getAmount()),
            "discountCode" => $this->mergeWhen($this->discount_code, $this->discount_code),
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "item" => $this->mergeWhenLoaded("item", $this->mergeItem())
        ];
    }


    private function mergeItem(): ?JsonResource
    {
        $type = $this->item_type;
        if ($type == OfferOrder::class) {
            $item = $this->item()->with("offer")->first();
            return AdsOfferOrderResource::make($item);
        }
        return null;
    }
}
