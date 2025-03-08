<?php

namespace App\Models\Trait\Relations;

use App\Models\AlarmOrder;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\Discount;
use App\Models\LadderOrder;
use App\Models\OfferOrder;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserFinancialRelations
{
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function myDiscounts(): HasMany
    {
        return $this->hasMany(Discount::class, "client_id");
    }

    public function usedDiscounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, "discount_user");
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function offerOrders(): HasMany
    {
        return $this->hasMany(OfferOrder::class);
    }
    public function ladderOrders(): HasMany
    {
        return $this->hasMany(LadderOrder::class);
    }
    public function alarmOrders(): HasMany
    {
        return $this->hasMany(AlarmOrder::class);
    }

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }

    public function auctionOffers(): HasMany
    {
        return $this->hasMany(AuctionBid::class);
    }
}
