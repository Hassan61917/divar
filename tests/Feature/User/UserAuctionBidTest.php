<?php

namespace Tests\Feature\User;

use App\Models\Auction;
use App\Models\AuctionBid;
use Tests\UserTest;

class UserAuctionBidTest extends UserTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user->wallet()->update(["balance" => 10000]);
    }

    public function test_store_should_not_offer_if_auction_belong_to_user()
    {
        $data = $this->createData(1010, Auction::factory()->for($this->user)->create());
        $this->postJson(route("v1.user.auction-bids.store"), $data)
            ->assertStatus(422);
    }

    public function test_store_should_not_offer_if_price_is_less_then_auction_start_price()
    {
        $data = $this->createData(10);
        $this->postJson(route("v1.user.auction-bids.store"), $data)
            ->assertStatus(422);
    }

    public function test_store_should_not_offer_if_price_is_less_then_last_auction_offer_price()
    {
        $price = 150;
        $auction = Auction::factory()->create();
        AuctionBid::factory()->for($auction)->create(["price" => $price]);
        $data = $this->createData($price - 1, $auction);
        $this->postJson(route("v1.user.auction-bids.store", $auction), $data)
            ->assertStatus(422);
    }

    public function test_store_should_store_offer_to_auction()
    {
        $data = $this->createData(200);
        $this->postJson(route("v1.user.auction-bids.store"), $data);
        $this->assertDatabaseCount("auction_offers", 1);
    }

    public function test_store_should_deposit_back_if_auction_has_last_offer()
    {
        $price = 150;
        $auction = Auction::factory()->create();
        $lastOffer = AuctionBid::factory()
            ->for($this->makeUser())
            ->for($auction)
            ->create(["price" => $price]);
        $data = $this->createData($price * 2, $auction);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.auction-bids.store", $auction), $data);
        $this->assertEquals($lastOffer->user->wallet->balance, $price);
    }


    private function createData(int $price, ?Auction $auction = null): array
    {
        $auction = $auction ?: Auction::factory()->create();
        return [
            "auction_id" => $auction->id,
            "price" => $price,
        ];
    }
}
