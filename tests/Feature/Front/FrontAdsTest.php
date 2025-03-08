<?php

namespace Front;

use App\Enums\AdsStatus;
use App\Enums\ShowStatus;
use App\Models\Ads;
use App\Models\Category;
use App\Models\City;
use App\Models\LadderOrder;
use App\Models\State;
use App\Models\User;
use Tests\UserTest;

class FrontAdsTest extends UserTest
{
    public function test_index_should_ads_in_same_city()
    {
        $ads1 = $this->makeAds(null,null,[
            "state_id"=>$this->user->profile->state_id,
            "city_id"=>$this->user->profile->city_id,
        ]);
        $ads2 = $this->makeAds();
        $res = $this->getJson(route("v1.front.advertises.index"));
        $res->assertSee($ads1->title);
        $res->assertDontSee($ads2->title);
    }

    public function test_index_should_see_ladder_ads_before_ads()
    {
        $ladder = LadderOrder::factory()->create([
            "status" => ShowStatus::Showing->value,
            "show_at" => now()->subHour()
        ]);
        $ads1 = $this->makeAds();
        $ads2 = $this->makeAds();
        $this->withoutExceptionHandling();
        $res = $this->getJson(route("v1.front.advertises.index"));
        $data = $res->json();
        $this->assertEquals($data[0]["title"], $ladder->ads->title);
        $this->assertEquals($data[1]["title"], $ads1->title);
        $this->assertEquals($data[2]["title"], $ads2->title);
    }

    public function test_index_should_filter_ads_by_category()
    {
        $digital = Category::factory()->create();
        $mobile = Category::factory()->for($digital, "parent")->create();
        $laptop = Category::factory()->for($digital, "parent")->create();
        $ads1 = $this->makeAds(null, $mobile);
        $ads2 = $this->makeAds(null, $laptop);
        $ads3 = $this->makeAds();
        $path = route("v1.front.advertises.index") . "?category={$digital->slug}";
        $res = $this->getJson($path);
        $res->assertSee($ads1->title);
        $res->assertSee($ads2->title);
        $res->assertDontSee($ads3->title);
    }

    public function test_index_should_filter_ads_by_price()
    {
        $price = 100;
        $ads1 = $this->makeAds(null, null, ["price" => $price]);
        $ads2 = $this->makeAds(null, null, ["price" => $price + 1]);
        $path = route("v1.front.advertises.index") . "?price=0-{$price}";
        $res = $this->getJson($path);
        $res->assertSee($ads1->title);
        $res->assertDontSee($ads2->title);
    }

    private function makeAds(?User $user = null, ?Category $category = null, array $data = []): Ads
    {
        $category = $category ?: Category::factory()->create();
        $user = $user ?: $this->makeUser();
        $data = array_merge($data, ["status" => AdsStatus::Published->value]);
        return Ads::factory()
            ->for($user)
            ->for($category)
            ->state($data)
            ->complete()
            ->create();
    }
}
