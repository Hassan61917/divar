<?php

namespace Tests\Feature\User;

use App\Enums\AdsStatus;
use App\Enums\OrderStatus;
use App\Enums\ShowStatus;
use App\Models\Ads;
use App\Models\Ladder;
use App\Models\LadderOrder;
use App\Models\Order;
use Tests\UserTest;

class UserLadderTest extends UserTest
{
    public function test_index_should_return_user_orders()
    {
        $order1 = LadderOrder::factory()->for($this->user)->create();
        $order2 = LadderOrder::factory()->create();
        $res = $this->getJson(route("v1.user.ladder-orders.index"));
        $res->assertSee($order1->id);
        $res->assertDontSee($order2->id);
    }

    public function test_store_should_order_a_ladder()
    {
        $ladder = Ladder::factory()->create();
        $ads = Ads::factory()
            ->for($this->user)
            ->for($ladder->category)
            ->create(["status" => AdsStatus::Published->value]);
        $data = [
            "ladder_id" => $ladder->id,
            "ads_id" => $ads->id,
        ];
        $this->postJson(route("v1.user.ladder-orders.store"), $data);
        $this->assertDatabaseHas("ladder_orders", $data);
    }

    public function test_store_should_not_order_if_ads_is_not_published()
    {
        $ladder = Ladder::factory()->create();
        $ads = Ads::factory()
            ->for($this->user)
            ->for($ladder->category)
            ->create();
        $data = [
            "ladder_id" => $ladder->id,
            "ads_id" => $ads->id,
        ];
        $this->postJson(route("v1.user.ladder-orders.store"), $data);
        $this->assertDatabaseMissing("ladder_orders", $data);
    }

    public function test_store_should_not_order_if_category_is_not_same()
    {
        $ladder = Ladder::factory()->create();
        $ads = Ads::factory()
            ->for($this->user)
            ->create(["status" => AdsStatus::Published->value]);
        $data = [
            "ladder_id" => $ladder->id,
            "ads_id" => $ads->id,
        ];
        $this->postJson(route("v1.user.ladder-orders.store"), $data);
        $this->assertDatabaseMissing("ladder_orders", $data);
    }

    public function test_store_should_not_order_if_user_is_not_owner_of_ads()
    {
        $ladder = Ladder::factory()->create();
        $ads = Ads::factory()
            ->for($ladder->category)
            ->create(["status" => AdsStatus::Published->value]);
        $data = [
            "ladder_id" => $ladder->id,
            "ads_id" => $ads->id,
        ];
        $this->postJson(route("v1.user.ladder-orders.store"), $data);
        $this->assertDatabaseMissing("ladder_orders", $data);
    }

    public function test_cancel_should_deposit_ladder_price_to_user()
    {
        $price = 100;
        $order = LadderOrder::factory()
            ->for($this->user)
            ->has(Order::factory()->for($this->user)->state(["status" => OrderStatus::Paid->value, "total_price" => $price]), "order")
            ->create([
                "status" => ShowStatus::Waiting->value,
            ]);
        $balance = $order->user->wallet->balance;
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.ladder-orders.cancel", $order));
        $this->assertEquals($order->fresh()->order->status, OrderStatus::Cancelled->value);
        $this->assertEquals($order->status, ShowStatus::Waiting->value);
        $this->assertEquals($order->user->wallet->fresh()->balance, $balance + $price);
    }

    public function test_cancel_should_deposit_remaining_of_the_price()
    {
        $price = 300;
        $duration = 3;
        $order = LadderOrder::factory()
            ->for($this->user)
            ->for(Ladder::factory()->state(["duration" => $duration]))
            ->has(Order::factory()->for($this->user)->state(["status" => OrderStatus::Paid->value, "total_price" => $price]), "order")
            ->create([
                "status" => ShowStatus::Showing->value,
                "show_at" => now(),
                "end_at" => now()->addDays($duration),
            ]);
        $time = 1;
        $this->travel($time)->day();
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.ladder-orders.cancel", $order));
        $amount = ($price / $duration) * ($duration - ($time + 1));
        $this->assertEquals($order->user->wallet->fresh()->balance, $amount);
    }

    public function test_cancel_should_not_cancel_completed_orders()
    {
        $order = LadderOrder::factory()
            ->for($this->user)
            ->create([
                "status" => ShowStatus::Completed->value,
            ]);
        $this->postJson(route("v1.user.ladder-orders.cancel", $order))
            ->assertStatus(422);
    }
}
