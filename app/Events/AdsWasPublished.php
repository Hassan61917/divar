<?php

namespace App\Events;

use App\Models\Ads;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdsWasPublished
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Ads $ads
    )
    {
    }

}
