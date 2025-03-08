<?php

namespace Tests\Feature\User;

use App\Enums\AdsStatus;
use App\Models\Ads;
use App\Models\Auction;
use App\Models\User;
use Tests\UserTest;

class UserAuctionTest extends UserTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user->wallet()->update(["balance" => 10000]);
    }

    public function test_store_should_store_ads_auction()
    {
        $auction = $this->makeData($this->user);
        $this->withoutExceptionHandling();
        $res = $this->postJson(route("v1.user.auctions.store"), $auction);
        $this->assertDatabaseCount("auctions", 1);
    }

    public function test_store_should_not_store_ads_auction_if_user_is_not_owner_of_ads()
    {
        $ads = $this->makeAds($this->makeUser());
        $auction = $this->makeData($this->user, $ads);
        $this->postJson(route("v1.user.auctions.store"), $auction)
            ->assertStatus(403);
    }

    public function test_store_should_not_store_ads_auction_if_ads_is_not_published()
    {
        $ads = $this->makeAds($this->user, ["status" => AdsStatus::Completed->value]);
        $auction = $this->makeData($this->user, $ads);
        $this->postJson(route("v1.user.auctions.store"), $auction)
            ->assertStatus(422);
    }

    public function test_store_should_not_store_ads_auction_if_user_has_open_action()
    {
        Auction::factory()
            ->for($this->user)
            ->for($this->makeAds($this->user))
            ->create();
        $data = $this->makeData($this->user);
        $this->postJson(route("v1.user.auctions.store"), $data)
            ->assertStatus(422);
    }

    private function makeAds(?User $user = null, array $data = []): Ads
    {
        $user = $user ?: $this->user;
        $data = array_merge([
            "status" => AdsStatus::Published->value,
        ], $data);
        return Ads::factory()
            ->for($user)
            ->state($data)
            ->complete()
            ->create();
    }

    private function makeData(?User $user = null, ?Ads $ads = null): array
    {
        $user = $user ?: $this->user;
        $ads = $ads ?: $this->makeAds($user);
        return Auction::factory()
            ->for($user)
            ->for($ads)
            ->raw();
    }
}
