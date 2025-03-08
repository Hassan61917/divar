<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\AdsOfferOrderResource;
use App\Models\OfferOrder;
use App\ModelServices\Ads\OfferOrderService;
use Illuminate\Http\JsonResponse;

class AdminOfferOrderController extends Controller
{
    protected string $resource = AdsOfferOrderResource::class;

    public function __construct(
        private OfferOrderService $orderService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = $this->orderService->getOrders(["offer"]);
        return $this->ok($this->paginate($orders));
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferOrder $order): JsonResponse
    {
        $order->load("offer", "user", "ads");
        return $this->ok($order);
    }
}
