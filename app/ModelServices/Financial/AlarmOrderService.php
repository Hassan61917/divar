<?php

namespace App\ModelServices\Financial;

use App\Events\AlarmOfferWasOrdered;
use App\Exceptions\ModelException;
use App\Models\Ads;
use App\Models\AlarmOffer;
use App\Models\AlarmOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmOrderService
{
    public function __construct(
        private OrderService $orderService,
    )
    {
    }

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->alarmOrders()->with($relations);
    }

    public function makeOrder(User $user, AlarmOffer $offer): AlarmOrder
    {
        if ($this->alreadyOrdered($user, $offer)) {
            throw new ModelException("offer is already ordered");
        }
        $order = $user->alarmOrders()->create(["offer_id" => $offer->id]);
        $this->orderService->makeOrder($order->user, $order, $order->offer->price);
        AlarmOfferWasOrdered::dispatch($order);
        return $order;
    }

    public function order(AlarmOrder $order): void
    {
        $order->update(["expired_at" => now()->addDays($order->offer->duration)]);
        $this->orderService->complete($order->order);
    }

    public function addAds(AlarmOrder $order, Ads $ads)
    {
        if ($order->ads()->count() + 1 == $order->offer->count) {
            $order->update(["expired_at" => now()]);
        }
        return $order->ads()->save($ads);
    }

    private function alreadyOrdered(User $user, AlarmOffer $offer): bool
    {
        return $user->alarmOrders()
            ->where("offer_id", $offer->id)
            ->available()
            ->orWhereNull("expired_at")
            ->exists();
    }
}
