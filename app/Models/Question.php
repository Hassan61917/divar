<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Models\Trait\With\WithLike;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends AppModel implements Likeable
{
    use WithLike;
    protected $fillable = [
        "ads_id", "question", "answer"
    ];

    public function scopeAnswered(Builder $builder): Builder
    {
        return $builder->whereNotNull("answer");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }
}
