<?php

namespace App\Models;

use App\Models\Interfaces\IOrderItem;
use App\Models\Trait\With\WithOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmOrder extends AppModel implements IOrderItem
{
    use WithOrder;

    protected $fillable = [
        "offer_id", "expired_at"
    ];

    public function scopeAvailable(Builder $builder): Builder
    {
        return $builder->where("expired_at", ">=", now());
    }

    public function isAvailable(): bool
    {
        return $this->expired_at && Carbon::make($this->expired_at)->gt(now());
    }

    protected function casts(): array
    {
        return [
            "expired_at" => "datetime"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(AlarmOffer::class, "offer_id");
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(AdsAlarm::class, "order_id");
    }

    public function ads(): BelongsToMany
    {
        return $this->belongsToMany(Ads::class, "ads_alarm_offer", "order_id");
    }
}
