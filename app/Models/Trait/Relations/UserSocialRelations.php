<?php

namespace App\Models\Trait\Relations;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Message;
use App\Models\UserBlock;
use App\Models\Visit;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserSocialRelations
{
    public function inbox(): HasMany
    {
        return $this->hasMany(Message::class, "receiver_id");
    }

    public function outbox(): HasMany
    {
        return $this->hasMany(Message::class, "sender_id");
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(UserBlock::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
