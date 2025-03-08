<?php

namespace App\Events;

use App\Models\OfferOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OfferWasOrdered
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private OfferOrder $order
    )
    {
        //
    }
}
