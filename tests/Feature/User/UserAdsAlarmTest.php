<?php

namespace Tests\Feature\User;

use App\Enums\OrderStatus;
use App\Models\AdsAlarm;
use App\Models\AlarmOrder;
use App\Models\Category;
use App\Models\Order;
use Tests\UserTest;

class UserAdsAlarmTest extends UserTest
{
    public function test_index_should_see_order_alarm_if_order_is_paid()
    {
        $order = $this->makeOrder();
        $alarm = $this->makeAlarm($order)->create();
        $this->withoutExceptionHandling();
        $res = $this->getJson(route("v1.user.ads-alarms.index", $order));
        $res->assertSee($alarm->title);
    }

    public function test_store_should_store_ads_alarm_to_an_order()
    {
        $order = $this->makeOrder();
        $data = $this->makeAlarm($order)->raw();
        $this->postJson(route("v1.user.ads-alarms.store", $order), $data);
        $this->assertDatabaseCount("ads_alarms", 1);
    }

    public function test_store_should_not_store_ads_alarm_if_exists()
    {
        $order = $this->makeOrder();
        $alarm1 = $this->makeAlarm($order)->create();
        $data = $this->makeAlarm($order, $order->offer->category, ["title" => $alarm1->title])->raw();
        $this->postJson(route("v1.user.ads-alarms.store", $order), $data)
            ->assertStatus(422);
    }

    public function test_store_should_not_store_ads_alarm_if_category_is_not_a_child_of_order_category()
    {
        $order = $this->makeOrder();
        $data = $this->makeAlarm($order)->raw();
        $this->postJson(route("v1.user.ads-alarms.store", $order), $data)
            ->assertStatus(422);
    }

    private function makeOrder(): AlarmOrder
    {
        return AlarmOrder::factory()
            ->for($this->user)
            ->has(Order::factory()->state(["status" => OrderStatus::Paid->value]), "order")
            ->create();
    }

    private function makeAlarm(AlarmOrder $order, ?Category $category = null, array $data = [])
    {
        $category = $category ?: $order->offer->category;
        return AdsAlarm::factory()
            ->for($order, "order")
            ->for($category)
            ->state($data);
    }
}
