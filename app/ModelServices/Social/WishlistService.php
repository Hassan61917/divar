<?php

namespace App\ModelServices\Social;

use App\Models\Ads;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WishlistService
{
    public function getAll(array $relations = []): Builder
    {
        return Wishlist::query()->with($relations);
    }

    public function getAllFor(User $user, array $relations = []): HasMany
    {
        return $user->wishlist()->with($relations);
    }

    public function make(User $user, Ads $ads): Wishlist
    {
        return $user->wishlist()->create([
            "ads_id" => $ads->id
        ]);
    }
}
