<?php

namespace Tests\Feature\User;

use App\Enums\AdsStatus;
use App\Models\Ads;
use App\Models\AdsLimit;
use App\Models\Category;
use Tests\UserTest;

class UserAdsPublishTest extends UserTest
{
    public function test_publish_should_publish_ads()
    {
        $limit = AdsLimit::factory()->create(["limit" => 10]);
        $category = $this->makeCategory($limit->category);
        $ads = $this->makeAds([], $category);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.advertises.publish", $ads));
        $this->assertEquals($ads->fresh()->status, AdsStatus::Published->value);
    }

    public function test_publish_should_not_publish_ads_if_title_is_same()
    {
        $ads1 = $this->makeAds(["status" => AdsStatus::Published->value]);
        $ads2 = $this->makeAds(["title" => $ads1->title]);
        $this->postJson(route("v1.user.advertises.publish", $ads2))
            ->assertStatus(422);
    }

    public function test_publish_should_not_publish_ads_if_description_is_same()
    {
        $ads1 = $this->makeAds(["status" => AdsStatus::Published->value]);
        $ads2 = $this->makeAds(["description" => $ads1->description]);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.advertises.publish", $ads2))
            ->assertStatus(422);
    }

    public function test_publish_should_publish_ads_if_ads_limit_is_free()
    {
        $limit = AdsLimit::factory()->create(["limit" => 1]);
        $mobile = $this->makeCategory($limit->category);
        $ads = $this->makeAds([], $mobile);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.advertises.publish", $ads));
        $this->assertEquals($ads->fresh()->status, AdsStatus::Published->value);
    }

    public function test_publish_should_not_publish_ads_if_ads_limit_is_not_free()
    {
        $limit = AdsLimit::factory()->create(["limit" => 0]);
        $mobile = $this->makeCategory($limit->category);
        $ads2 = $this->makeAds([], $mobile);
        $this->postJson(route("v1.user.advertises.publish", $ads2))
            ->assertStatus(422);
    }

    public function test_publish_should_not_publish_ads_if_reached_the_limit()
    {
        $limit = AdsLimit::factory()->create(["limit" => 1]);
        $mobile = $this->makeCategory($limit->category);
        $this->makeAds(["status" => AdsStatus::Published->value], $mobile);
        $ads2 = $this->makeAds([], $mobile);
        $this->postJson(route("v1.user.advertises.publish", $ads2))
            ->assertStatus(422);
    }
    private function makeAds(array $data = [], ?Category $category = null)
    {
        $category = $category ?: $this->makeCategory();

        return Ads::factory()
            ->for($category)
            ->for($this->user)
            ->state($data)
            ->complete()
            ->create();
    }

    private function makeCategory(?Category $parent = null)
    {
        $parent = $parent ?: Category::factory()->create();
        return Category::factory()->for($parent, "parent")->create();
    }
}
