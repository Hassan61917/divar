<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\AdsOfferOrderResource;
use App\Models\AdsOffer;
use App\Models\OfferOrder;
use App\ModelServices\Ads\OfferOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserOfferOrderController extends AuthUserController
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
        $orders = $this->orderService->getOrdersFor($this->authUser(), ["offer"]);
        return $this->ok($this->paginate($orders));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "offer_id" => "required|exists:ads_offers,id",
        ]);
        $order = $this->orderService->makeOrder($this->authUser(), AdsOffer::find($data['offer_id']));
        $order->load("offer");
        return $this->ok($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferOrder $order): JsonResponse
    {
        $order->load('offer', "ads");
        return $this->ok($order);
    }
}
