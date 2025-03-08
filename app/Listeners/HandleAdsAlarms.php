<?php

namespace App\Listeners;

use App\Events\AdsWasPublished;
use App\Models\Ads;
use App\ModelServices\Financial\AlarmOrderService;
use App\Notifications\AdsWasFind;
use Illuminate\Support\Collection;

class HandleAdsAlarms
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private AlarmOrderService $orderService
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
        $alarms = $this->getAlarms($ads);
        foreach ($alarms as $alarm) {
            $user = $alarm->order->user;
            $user->notify(new AdsWasFind($ads));
            $this->orderService->addAds($alarm->order, $ads);
        }
    }

    private function getAlarms(Ads $ads): Collection
    {
        return $ads->category
            ->alarms()
            ->whereHas("order", fn($query) => $query->available())
            ->where(fn($query) => $query->filterTitle($ads->title))
            ->orWhere(fn($query) => $query->filterPrice($ads->price))
            ->get();
    }
}
