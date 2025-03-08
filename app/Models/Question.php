<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends AppModel
{
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
