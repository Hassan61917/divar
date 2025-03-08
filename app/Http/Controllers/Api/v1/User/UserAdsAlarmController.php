<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserAdsAlarmRequest;
use App\Http\Resources\v1\AdsAlarmResource;
use App\Models\AdsAlarm;
use App\Models\AlarmOrder;
use App\ModelServices\Financial\AlarmService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class UserAdsAlarmController extends AuthUserController
{
    protected string $resource = AdsAlarmResource::class;

    public function __construct(
        private AlarmService $alarmService
    )
    {
    }

    public function before(Model $model): void
    {
        parent::before($model);
        if (!$model->isAvailable()) {
            throw new AuthorizationException("only available orders are allowed");
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(AlarmOrder $order): JsonResponse
    {
        $alarms = $this->alarmService->getAlarmsFor($order);
        return $this->ok($this->paginate($alarms));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlarmOrder $order, UserAdsAlarmRequest $request): JsonResponse
    {
        $data = $request->validated();
        $alarm = $this->alarmService->makeAlarm($order, $data);
        return $this->ok($alarm);
    }

    /**
     * Display the specified resource.
     */
    public function show(AlarmOrder $order, AdsAlarm $alarm): JsonResponse
    {
        $alarm->load("order");
        return $this->ok($alarm);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlarmOrder $order, UserAdsAlarmRequest $request, AdsAlarm $alarm): JsonResponse
    {
        $data = $request->validated();
        $alarm->update($data);
        $alarm->load("order");
        return $this->ok($alarm);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AlarmOrder $order, AdsAlarm $alarm): JsonResponse
    {
        $alarm->delete();
        return $this->deleted();
    }
}
