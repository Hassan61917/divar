<?php

namespace App\Listeners;

use App\Events\AdsWasPublished;
use App\ModelServices\Ads\AdsOfferService;
use App\ModelServices\Ads\AdsService;
use App\ModelServices\Ads\OfferOrderService;

class UpdateOfferOrder
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private OfferOrderService $orderService,
        private AdsService      $adsService,
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AdsWasPublished $event): void
    {
        $ads = $event->ads;
        $user = $ads->user;
        $limit = $this->orderService->findLimit($ads);
        if ($this->adsService->isReachedLimit($user, $limit)) {
            $order = $this->orderService->getAvailableOffer($user, $ads->category);
            $this->orderService->useAds($ads, $order);
        }
    }
}
