<?php

namespace App\ModelServices\Ads;

use App\Enums\ShowStatus;
use App\Exceptions\ModelException;
use App\Models\LadderOrder;
use Carbon\Carbon;

trait OrderShowable
{
    public function updateShowingTime(LadderOrder $order): void
    {
        $value = $this->getShowingTime($order->ladder);
        if ($order->id && !$this->isWaiting($order)) {
            return;
        }
        $order->show_at =  $value;
        $order->save();
    }
    private function complete(LadderOrder $order):void
    {
        $this->orderService->complete($order->order);
        $this->updateOrderStatus($order, ShowStatus::Completed);
    }
    private function show(LadderOrder $order, int $duration): void
    {
        $status = $order->status;
        if ($status != ShowStatus::Waiting->value) {
            throw new ModelException("order can not be showed");
        }
        $order->update([
            "end_at" => Carbon::make($order->show_at)->addDays($duration)
        ]);
        $this->updateOrderStatus($order, ShowStatus::Showing);
    }
    private function getCancelAmount(LadderOrder $order)
    {
        $amount = $order->order->getAmount();
        if ($order->status == ShowStatus::Showing->value) {
            $price = $amount / $order->ladder->duration;
            $result = +Carbon::make($order->end_at)->diff(now())->format('%d');
            $amount = $price * $result;
        }
        return $amount;
    }

    private function updateOrderStatus(LadderOrder $order, ShowStatus $status): void
    {
        $order->update(["status" => $status->value]);
    }

    private function isWaiting(LadderOrder $order): bool
    {
        return $order->status == ShowStatus::Waiting->value;
    }
}
