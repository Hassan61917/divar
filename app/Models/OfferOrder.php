<?php

namespace App\Models;

use App\Models\Interfaces\IOrderItem;
use App\Models\Trait\With\WithOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OfferOrder extends AppModel implements IOrderItem
{
    use WithOrder;
    protected $fillable = [
        "offer_id", "expired_at"
    ];
    public function scopeAvailable(Builder $builder): Builder
    {
        return $builder->where("expired_at",">=",now());
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
        return $this->belongsTo(AdsOffer::class, "offer_id");
    }

    public function ads(): BelongsToMany
    {
        return $this->belongsToMany(Ads::class, "ads_offer_order", "order_id");
    }
}
