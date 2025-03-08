<?php

namespace App\Models\Trait\Relations;

use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
