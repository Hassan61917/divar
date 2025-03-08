<?php

namespace App\Handlers\LadderOrder;

use App\Enums\AdsStatus;
use App\Exceptions\ModelException;
use App\Handlers\ModelHandler;
use App\Models\LadderOrder;

class LadderOrderHandler extends ModelHandler
{
    protected array $rules = [
        "status", "user", "category"
    ];

    protected function status(LadderOrder $order): void
    {
        if ($order->ads->status != AdsStatus::Published->value) {
            throw new ModelException("only published ads can be ordered");
        }
    }

    protected function user(LadderOrder $order): void
    {
        if (!$order->user->is($order->ads->user)) {
            throw new ModelException("you can ladder your own ads");
        }
    }

    protected function category(LadderOrder $order): void
    {
        if (!$order->ads->category->is($order->ladder->category)) {
            throw new ModelException("category must be same");
        }
    }
}
