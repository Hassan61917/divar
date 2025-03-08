<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Models\Trait\With\WithLike;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends AppModel implements Likeable
{
    use WithLike;
    protected $fillable = [
        "ads_id", "parent_id", "comment"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, "parent_id");
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, "parent_id");
    }
}


