<?php

namespace Tests\Feature\User;

use App\Models\Discount;
use App\Models\Order;
use Tests\UserTest;

class ClientDiscountTest extends UserTest
{
    public function test_apply_should_apply_discount_to_order()
    {
        $price = 1000;
        $discountAmount = 100;
        $order = $this->makeOrder($price);
        $discount = $this->makeDiscount(["amount" => $discountAmount]);
        $data = ["code" => $discount->code,];
        $this->postJson(route("v1.me.order.discount", $order), $data);
        $this->assertEquals($order->fresh()->discount_code, $discount->code);
        $this->assertEquals($order->fresh()->discount_price, $price - $discountAmount);
    }

    public function test_apply_should_not_apply_discount_if_discount_is_expired()
    {
        $order = $this->makeOrder();
        $discount = Discount::factory()->create(["expired_at" => now()->subHour()]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.me.order.discount", $order), $data)
            ->assertStatus(422);
    }

    public function test_apply_should_not_apply_discount_if_client_is_not_same()
    {
        $order = $this->makeOrder();
        $discount = Discount::factory()->create(["client_id" => $this->makeUser()->id]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.me.order.discount", $order), $data)
            ->assertStatus(422);
    }

    public function test_apply_should_not_apply_discount_if_client_reached_limit()
    {
        $order = $this->makeOrder();
        $discount = Discount::factory()->create(["limit" => 1]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.me.order.discount", $order), $data);
        $this->postJson(route("v1.me.order.discount", $order), $data)
            ->assertStatus(422);
    }
    public function test_apply_should_not_apply_discount_if_discount_balance_price_is_less_then_order_total_price()
    {
        $order = $this->makeOrder();
        $discount = Discount::factory()->create(["total_balance"=>$order->total_price + 1]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.me.order.discount", $order), $data)
            ->assertStatus(422);
    }
    private function makeOrder(int $price = 1000): Order
    {
        return Order::factory()
            ->for($this->user)
            ->create(["total_price" => $price]);
    }

    private function makeDiscount(array $data = []): Discount
    {
        return Discount::factory()->create($data);
    }
}
