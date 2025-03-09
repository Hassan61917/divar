<?php

namespace App\ModelServices\Financial;

use App\Enums\OrderStatus;
use App\Events\OrderWasPaid;
use App\Exceptions\ModelException;
use App\Handlers\Discount\DiscountHandler;
use App\Models\Discount;
use App\Models\Interfaces\IOrderItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderService
{
    public function __construct(
        private WalletService   $walletService,
        private DiscountHandler $discountHandler
    ) {}

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->orders()->with($relations);
    }

    public function getOrders(array $relations = [])
    {
        return Order::query()->with($relations);
    }

    public function makeOrder(User $user, IOrderItem $model, int $price): Order
    {
        return $user->orders()->create([
            "item_type" => $model::class,
            "item_id" => $model->id,
            "total_price" => $price
        ]);
    }

    public function pay(Order $order): Order
    {
        $amount = $order->getAmount();
        $this->walletService->withdraw($order->user->wallet, $amount);
        $this->updateStatus($order, OrderStatus::Paid);
        OrderWasPaid::dispatch($order);
        return $order;
    }

    public function cancel(Order $order, int $amount): Order
    {
        if ($this->isCompleted($order)) {
            throw new ModelException("Cannot cancel completed orders");
        }
        if ($this->isPaid($order)) {
            $this->walletService->deposit($order->user->wallet, $amount);
        }
        $this->updateStatus($order, OrderStatus::Cancelled);
        return $order;
    }

    public function applyDiscount(Order $order, string $code): Order
    {
        $discount = $this->findDiscount($code);
        $this->discountHandler->handle($discount, [$order]);
        $discount->users()->save($order->user);
        $order->update([
            "discount_code" => $code,
            "discount_price" => $order->total_price - $discount->getValue($order->total_price)
        ]);
        return $order;
    }

    public function complete(Order $order): Order
    {
        if (!$this->isPaid($order)) {
            throw new ModelException("unpaid orders cannot be completed");
        }
        $this->updateStatus($order, OrderStatus::Completed);
        return $order;
    }

    public function updateStatus(Order $order, OrderStatus $status): void
    {
        $order->update([
            "status" => $status->value
        ]);
    }

    private function isPaid(Order $order): bool
    {
        return $order->isStatus(OrderStatus::Paid);
    }

    private function isCompleted(Order $order): bool
    {
        return $order->isStatus(OrderStatus::Completed);
    }

    public function findDiscount(string $code): Discount
    {
        return Discount::where('code', $code)->first();
    }
}
