<?php

namespace Database\Factories;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdsLimit>
 */
class AdsLimitFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "category_id" => Category::factory(),
            "limit" => 12,
            "duration" => 365
        ];
    }
}
