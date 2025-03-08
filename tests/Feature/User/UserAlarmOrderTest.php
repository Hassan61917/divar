<?php

namespace Tests\Feature\User;

use App\Models\AdsAlarm;
use App\Models\AlarmOffer;
use App\Models\AlarmOrder;
use Tests\UserTest;

class UserAlarmOrderTest extends UserTest
{
    public function test_store_should_order_an_alarm_offer()
    {
        $offer = AlarmOffer::factory()->create();
        $data = ["offer_id" => $offer->id];
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.alarm-orders.store"), $data);
        $this->assertDatabaseCount("alarm_orders", 1);
    }

    public function test_store_should_not_order_if_same_order_is_available()
    {
        $offer = AlarmOffer::factory()->create();
        $data = ["offer_id" => $offer->id];
        $this->postJson(route("v1.user.alarm-orders.store"), $data);
        $this->postJson(route("v1.user.alarm-orders.store"), $data)
            ->assertStatus(422);
        $this->assertDatabaseCount("alarm_orders", 1);
    }

}
