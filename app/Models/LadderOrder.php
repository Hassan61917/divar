<?php

namespace App\Models;

use App\Enums\ShowStatus;
use App\Models\Interfaces\IOrderItem;
use App\Models\Trait\With\WithOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LadderOrder extends AppModel implements IOrderItem
{
    use WithOrder;

    protected $fillable = [
        "ads_id", "ladder_id", "status", "show_at", "end_at"
    ];

    public function scopeShowing(Builder $builder): Builder
    {
        return $builder->where("status", ShowStatus::Showing->value);
    }

    public function casts(): array
    {
        return [
            "show_at" => "datetime",
            "end_at" => "datetime",
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Ads::class);
    }

    public function ladder(): BelongsTo
    {
        return $this->BelongsTo(Ladder::class);
    }
}

