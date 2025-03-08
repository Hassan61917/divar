<?php

namespace App\ModelServices\Financial;

use App\Handlers\Alarm\AlarmHandler;
use App\Models\AdsAlarm;
use App\Models\AlarmOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmService
{
    public function __construct(
        private AlarmHandler $alarmHandler
    )
    {
    }

    public function makeAlarm(AlarmOrder $order, array $data): AdsAlarm
    {
        $data["min_price"] = $data["min_price"] ?? 1;
        $alarm = $order->alarms()->make($data);
        $this->alarmHandler->handle($alarm);
        $alarm->save();
        return $alarm;
    }

    public function getAlarmsFor(AlarmOrder $order): HasMany
    {
        return $order->alarms()->with("category");
    }

}
