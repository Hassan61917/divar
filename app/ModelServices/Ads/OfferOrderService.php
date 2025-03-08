<?php

namespace App\ModelServices\Ads;

use App\Events\OfferWasOrdered;
use App\Exceptions\ModelException;
use App\Models\Ads;
use App\Models\AdsLimit;
use App\Models\AdsOffer;
use App\Models\Category;
use App\Models\OfferOrder;
use App\Models\User;
use App\ModelServices\Financial\OrderService;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfferOrderService
{
    public function __construct(
        private OrderService $orderService,
    )
    {
    }

    public function getOrders(array $relations = [])
    {
        return OfferOrder::query()->with($relations);
    }

    public function findLimit(Ads $ads): AdsLimit
    {
        $category = $ads->category;
        $parent = $category->getLastParent();
        return AdsLimit::where("category_id", $parent->id)->first();
    }

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->offerOrders()->with($relations);
    }

    public function useAds(Ads $ads, OfferOrder $order): void
    {
        $order->ads()->save($ads);
        if ($order->ads()->count() == $order->offer->count) {
            $order->update(["expired_at" => now()]);
        }
    }

    public function order(OfferOrder $order): void
    {
        $order->update(["expired_at" => now()->addDays($order->offer->duration)]);
        $this->orderService->complete($order->order);
    }

    public function getAvailableOffer(User $user, Category $category): ?OfferOrder
    {
        return $this->getOrdersFor($user)
            ->whereHas("offer",
                fn($query) => $query
                    ->where("category_id", $category->getLastParent()->id)
                    ->orWhereNull("category_id")
            )
            ->available()
            ->first();
    }

    public function makeOrder(User $user, AdsOffer $offer): OfferOrder
    {
        if ($this->alreadyOrdered($user, $offer)) {
            throw new ModelException("offer is already ordered");
        }
        $order = $user->offerOrders()->create(["offer_id" => $offer->id]);
        $this->orderService->makeOrder($order->user, $order, $order->offer->price);
        OfferWasOrdered::dispatch($order);
        return $order;
    }

    private function alreadyOrdered(User $user, AdsOffer $offer): bool
    {
        return $user->offerOrders()
            ->where("offer_id", $offer->id)
            ->available()
            ->orWhereNull("expired_at")
            ->exists();
    }
}
