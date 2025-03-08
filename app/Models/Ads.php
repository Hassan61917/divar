<?php

namespace App\Models;

use App\Enums\AdsStatus;
use App\Models\Trait\Relations\AdsRelations;
use Illuminate\Database\Eloquent\Builder;

class Ads extends AppModel
{
    use AdsRelations;
    protected $fillable = [
        "category_id", "status", "location", "is_validated", "fields","state_id",
        "city_id","title", "description", "price", "chat", "call", "phone",
    ];

    public function scopePublished(Builder $builder): Builder
    {
        return $builder->where("status", AdsStatus::Published->value);
    }
    public function scopeInCity(Builder $builder, int $stateId, int $cityId): Builder
    {
        return $builder->where("state_id", $stateId)->where("city_id", $cityId);
    }

    protected function casts(): array
    {
        return [
            "is_validated" => "boolean",
            "chat" => "boolean",
            "call" => "boolean",
            "fields"=>"array",
        ];
    }
    public function isPublished(): bool
    {
        return $this->status == AdsStatus::Published->value;
    }
}
