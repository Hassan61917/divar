<?php

namespace App\Events;

use App\Models\AlarmOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlarmOfferWasOrdered
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private AlarmOrder $order
    )
    {
        //
    }
}
