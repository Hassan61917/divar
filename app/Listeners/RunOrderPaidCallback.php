<?php

namespace App\Listeners;

use App\Events\OrderWasPaid;
use App\Models\AlarmOrder;
use App\Models\OfferOrder;
use App\ModelServices\Ads\OfferOrderService;
use App\ModelServices\Financial\AlarmOrderService;
use App\ModelServices\Financial\OrderService;

class RunOrderPaidCallback
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order;
        $item = $order->item;
        $class = $this->getCallbackClass($item->type);
        if (method_exists($class, "order")) {
            $class->order($item);
        }
    }


    private function getCallbackClass(string $type): string
    {
        return match ($type) {
            OfferOrder::class => OfferOrderService::class,
            AlarmOrder::class => AlarmOrderService::class,
        };
    }
}
