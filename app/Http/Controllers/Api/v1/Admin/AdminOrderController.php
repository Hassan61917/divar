<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\OrderResource;
use App\Models\Order;
use App\ModelServices\Financial\OrderService;
use Illuminate\Http\JsonResponse;

class AdminOrderController extends Controller
{
    protected string $resource = OrderResource::class;

    public function __construct(
        private OrderService $orderService
    )
    {
    }

    public function index(): JsonResponse
    {
        $orders = $this->orderService->getOrders(["user"]);
        return $this->ok($this->paginate($orders));
    }

    public function show(Order $order): JsonResponse
    {
        $order->load("user","item");
        return $this->ok($order);
    }
    public function complete(Order $order): JsonResponse
    {
        $booking = $this->orderService->complete($order);
        return $this->ok($booking);
    }
    public function destroy(Order $order): JsonResponse
    {
        if ($order->status != OrderStatus::Draft->value) {
            return $this->error(406, "paid orders can not be deleted");
        }
        $order->delete();
        return $this->deleted();
    }
}
