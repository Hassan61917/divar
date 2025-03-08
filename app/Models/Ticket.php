<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends AppModel
{
    protected $fillable = [
        "category_id",
        "title",
        "status",
        "closed_at",
        "rate"
    ];

    public function scopeSortByPriority(Builder $builder): Builder
    {
        return $builder
            ->select("tickets.*")
            ->join("ticket_categories", "ticket_categories.id", "=", "tickets.category_id")
            ->orderBy("ticket_categories.priority", "desc");
    }
    public function scopeUnClosed(Builder $builder): Builder
    {
        return $builder->where('status', "!=", TicketStatus::Closed->value);
    }

    protected function casts(): array
    {
        return [
            "closed_at" => "datetime",
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, "category_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
}
