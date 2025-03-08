<?php

namespace Database\Factories;

use App\Models\AlarmOrder;
use App\Models\Category;
use App\Models\LadderOrder;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdsAlarm>
 */
class AdsAlarmFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "order_id" => AlarmOrder::factory()->create(),
            "category_id" => Category::factory()->create(),
            "title" => $this->faker->sentence(),
            "min_price" => 0,
            "max_price" => 100,
        ];
    }
}
