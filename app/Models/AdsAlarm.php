<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdsAlarm extends AppModel
{
    protected $with = ["category"];
    protected $fillable = [
        "order_id", "category_id", "title", "min_price", "max_price",
    ];

    public function scopeFilterTitle(Builder $builder, string $title): Builder
    {
        return $builder->whereNotNull("title")
            ->where("title", "like", "%{$title}%");
    }
    public function scopeFilterPrice(Builder $builder, int $price): Builder
    {
        return $builder->where("min_price", ">=", $price)
            ->where("max_price", "<=", $price);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(AlarmOrder::class, "order_id");
    }
}
