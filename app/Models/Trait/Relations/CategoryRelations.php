<?php

namespace App\Models\Trait\Relations;

use App\Models\Ads;
use App\Models\AdsField;
use App\Models\AdsLimit;
use App\Models\AdsOffer;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait CategoryRelations
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function adsLimits(): HasOne
    {
        return $this->hasOne(AdsLimit::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
    public function adsFields(): HasMany
    {
        return $this->hasMany(AdsField::class);
    }
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(AdsOffer::class);
    }
}
