<?php

namespace Tests\Feature\Admin;

use App\Models\AdsLimit;
use App\Models\Category;
use Tests\AdminTest;

class AdminAdsLimitTest extends AdminTest
{
    public function test_store_should_store_ads_limit()
    {
        $data = AdsLimit::factory()->raw();
        $this->postJson(route("v1.admin.ads-limit.store"), $data);
        $this->assertDatabaseHas("ads_limits", $data);
    }

    public function test_store_should_store_ads_limit_only_for_parent_category()
    {
        $category = Category::factory()->create();
        $subCategory = Category::factory()->for($category, "parent")->create();
        $data = AdsLimit::factory()->for($subCategory)->raw();
        $this->postJson(route("v1.admin.ads-limit.store"), $data)
            ->assertStatus(422);
        $this->assertDatabaseMissing("ads_limits", $data);
    }
}
