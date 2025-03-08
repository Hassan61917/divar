<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends AppModel
{
    protected $withCount = ['cities'];
    protected $fillable = ["name", "slug"];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
