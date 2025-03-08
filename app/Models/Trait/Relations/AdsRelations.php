<?php

namespace App\Models\Trait\Relations;

use App\Models\Auction;
use App\Models\Category;
use App\Models\City;
use App\Models\Comment;
use App\Models\LadderOrder;
use App\Models\Question;
use App\Models\State;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait AdsRelations
{

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function ladderOrders(): HasMany
    {
        return $this->hasMany(LadderOrder::class);
    }
    public function auction(): HasOne
    {
        return $this->hasOne(Auction::class, "ads_id");
    }
}
