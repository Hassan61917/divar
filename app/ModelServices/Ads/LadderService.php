<?php

namespace App\ModelServices\Ads;

use App\Enums\ShowStatus;
use App\Events\LadderWasOrdered;
use App\Handlers\LadderOrder\LadderOrderHandler;
use App\Models\Ladder;
use App\Models\LadderOrder;
use App\Models\User;
use App\ModelServices\Financial\OrderService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LadderService
{
    use OrderShowable;

    public function __construct(
        private OrderService       $orderService,
        private LadderOrderHandler $orderHandler
    )
    {
    }

    public function getLadders(array $relations = []): Builder
    {
        return Ladder::query()->with($relations);
    }

    public function makeLadder(array $data): Ladder
    {
        return Ladder::create($data);
    }

    public function getOrders(array $relations = []): Builder
    {
        return LadderOrder::query()->with($relations);
    }

    public function getAvailableOrders(array $relations = []): Builder
    {
        return LadderOrder::showing()->with($relations);
    }

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->ladderOrders()->with($relations);
    }

    public function makeOrder(User $user, array $data): LadderOrder
    {
        $order = $user->ladderOrders()->make($data);
        $this->orderHandler->handle($order);
        $this->updateShowingTime($order);
        $this->orderService->makeOrder($user, $order, $order->ladder->price);
        LadderWasOrdered::dispatch($order);
        return $order;
    }

    public function cancelOrder(LadderOrder $order): LadderOrder
    {
        $amount = $this->getCancelAmount($order);
        $this->orderService->cancel($order->order, $amount);
        $this->updateOrderStatus($order, ShowStatus::Waiting);
        return $order;
    }

    public function showOrder(LadderOrder $order): LadderOrder
    {
        $this->show($order, $order->ladder->duration);
        return $order;
    }

    public function completeOrder(LadderOrder $order): LadderOrder
    {
        $this->complete($order);
        return $order;
    }

    private function getShowingTime(Ladder $ladder): Carbon
    {
        $paidOrders = $ladder->orders()->paid()->latest()->take(5)->get();
        $result = now();
        if ($paidOrders->count() == 5) {
            $lastLadder = $paidOrders->first();
            $result = Carbon::make($lastLadder->end_at);
        }
        return $result->addMinute();
    }
}
