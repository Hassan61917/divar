<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class DiscountResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "code" => $this->code,
            "amount" => $this->amount,
            "percent" => $this->percent,
            "limit" => $this->limit,
            "maxAmount" => $this->max_amount,
            "totalBalance" => $this->total_balance,
            "expiredAt" => $this->expired_at,
            "category" => $this->mergeRelation(CategoryResource::class, "category"),
            "client" => $this->mergeRelation(UserResource::class, "client"),
            "users" => $this->mergeRelations(UserResource::class, "users")
        ];
    }
}
