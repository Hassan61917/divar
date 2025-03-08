<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdsField extends AppModel
{
    protected $fillable = [
        "category_id", "title", "value", "rule"
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
