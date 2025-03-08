<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\AlarmOrderResource;
use App\Models\AlarmOffer;
use App\Models\AlarmOrder;
use App\ModelServices\Financial\AlarmOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAlarmOrderController extends AuthUserController
{
    protected string $resource = AlarmOrderResource::class;

    public function __construct(
        private AlarmOrderService $alarmService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = $this->alarmService->getOrdersFor($this->authUser(), ["offer"]);
        return $this->ok($this->paginate($orders));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "offer_id" => "required|exists:alarm_offers,id",
        ]);
        $order = $this->alarmService->makeOrder($this->authUser(), AlarmOffer::find($data['offer_id']));
        $order->load("offer");
        return $this->ok($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(AlarmOrder $order): JsonResponse
    {
        $order->load('offer', "ads", "alarms");
        return $this->ok($order);
    }
}
