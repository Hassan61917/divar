<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends AppModel
{
    protected $fillable = [
        "user_id", "ads_id", "start_price", "step", "started_at", "finished_at"
    ];

    public function scopeInProcess(Builder $builder): Builder
    {
        return $builder
            ->where("started_at", "<=", now())
            ->where("finished_at", ">=", now());
    }

    public function scopeFinished(Builder $builder): Builder
    {
        return $builder->where("finished_at", ">", now());
    }

    public function isInProcess(): bool
    {
        return $this->started_at <= now() &&
            $this->finished_at >= now();
    }

    public function casts(): array
    {
        return [
            "started_at" => "datetime",
            "finished_at" => "datetime",
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }

    public function lastBid():?AuctionBid
    {
        return $this->bids()->orderBy("price", "desc")->first();
    }
}
