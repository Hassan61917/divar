<?php

namespace App\Models\Trait\With;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait WithOrder
{
    public function scopePaid(Builder $builder): Builder
    {
        return $builder->whereHas("order", fn($query) => $query->paid());
    }

    public function order(): MorphOne
    {
        return $this->morphOne(Order::class, 'item');
    }

    public function createOrder(): Order
    {
        return $this->order()->create([
            "user_id" => $this->user->id,
            "total_price" => $this->offer->price
        ]);
    }

    public function isPaid(): bool
    {
        return $this->order()->where("status", OrderStatus::Paid->value)->exists();
    }
}
