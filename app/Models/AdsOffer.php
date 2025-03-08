<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdsOffer extends AppModel
{
    protected $fillable = [
        "category_id", "count", "price", "duration"
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OfferOrder::class, "offer_id");
    }
}
