<?php

namespace App\Models\Trait\Relations;

use App\Models\Ads;
use App\Models\Ban;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserRelations
{
    use UserFinancialRelations;
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, "role_user");
    }
    public function banHistory(): HasMany
    {
        return $this->hasMany(Ban::class);
    }
    public function bannedUsers(): HasMany
    {
        return $this->hasMany(Ban::class, 'admin_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    public function ads(): HasMany
    {
        return $this->hasMany(Ads::class);
    }
}
