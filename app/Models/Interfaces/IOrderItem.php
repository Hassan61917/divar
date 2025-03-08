<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface IOrderItem
{
    public function order(): MorphOne;
}
