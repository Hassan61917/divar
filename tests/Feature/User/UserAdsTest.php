<?php

namespace Tests\Feature\User;

use App\Enums\AdsStatus;
use App\Models\Ads;
use App\Models\AdsField;
use App\Models\Category;
use Illuminate\Support\Str;
use Tests\UserTest;

class UserAdsTest extends UserTest
{
    public function test_store_user_can_store_ads()
    {
        $data = Ads::factory()->for($this->user)->raw();
        $this->postJson(route("v1.user.advertises.store"), $data);
        $this->assertDatabaseHas("ads", [
            ...$data,
            "status" => AdsStatus::Draft->value
        ]);
    }

    public function test_get_fields_should_get_fields_that_are_related_to_ads_category()
    {
        $digital = $this->makeCategory("digital");
        $mobile = $this->makeCategory("mobile", $digital);
        $console = $this->makeCategory("console", $digital);
        $field1 = $this->makeField(["title" => "brand"], $digital);
        $field2 = $this->makeField(["title" => "sim card"], $mobile);
        $field3 = $this->makeField(["title" => "joy stick"], $console);
        $ads = Ads::factory()
            ->for($this->user)
            ->for($mobile)
            ->create();
        $res = $this->getJson(route("v1.user.advertises.get-fields", $ads));
        $res->assertSee($field1->title);
        $res->assertSee($field2->title);
        $res->assertDontSee($field3->title);
    }

    public function test_valida_fields_should_return_error_if_ads_fields_is_not_valid()
    {
        $digital = $this->makeCategory("digital");
        $this->makeField(["title" => "brand"], $digital);
        $data = ["brand" => ""];
        $ads = Ads::factory()
            ->for($this->user)
            ->for($digital)
            ->create();
        $res = $this->postJson(route("v1.user.advertises.save-fields", $ads), $data);
        $res->assertStatus(422);
    }

    public function test_validate_fields_should_update_ads_fields()
    {
        $digital = $this->makeCategory("digital");
        $this->makeField(["title" => "brand"], $digital);
        $data = ["brand" => "samsung"];
        $ads = Ads::factory()
            ->for($this->user)
            ->for($digital)
            ->create();
        $this->postJson(route("v1.user.advertises.save-fields", $ads), $data);
        $this->assertEquals($ads->fresh()->fields, $data);
    }
    private function makeCategory(string $name, ?Category $parent = null): Category
    {
        $slug = Str::slug($name);
        return Category::factory()->create([
            "name" => $name,
            "slug" => $slug,
            "parent_id" => $parent?->id
        ]);
    }

    private function makeField(array $data = [], ?Category $category = null): AdsField
    {
        $category = $category ?? Category::factory()->create();
        return AdsField::factory()->for($category, "category")->create($data);
    }
}
