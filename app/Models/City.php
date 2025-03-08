<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends AppModel
{
    protected $fillable = ["name", "slug", "state_id"];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
