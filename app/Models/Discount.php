<?php

namespace App\Models;

use App\Models\Trait\Relations\DiscountRelations;
use Illuminate\Database\Eloquent\Builder;

class Discount extends AppModel
{
    use DiscountRelations;

    protected $fillable = [
        "title", "description", "code", "category_id",
        "client_id", "limit", "amount", "percent",
        "total_balance", "max_amount", "expired_at"
    ];

    public function scopeForClient(Builder $builder, int $clientId): Builder
    {
        return $builder->where("client_id", $clientId);
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->Where("expired_at", ">", now());
    }

    protected function casts(): array
    {
        return [
            "expired_at" => "datetime",
        ];
    }

    public function getValue(int $total_price)
    {
        $value = 0;
        if ($this->amount) {
            $value = $this->amount;
        }
        if ($this->percent) {
            $value = ($total_price / 100) * $this->percent;
        }
        if ($this->max_amount) {
            $value = min($this->max_amount, $value);
        }
        return $value;
    }
}

