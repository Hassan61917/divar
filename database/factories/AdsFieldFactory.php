<?php

namespace Database\Factories;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdsField>
 */
class AdsFieldFactory extends AppFactory
{
    public function definition(): array
    {
        return [
            "category_id" => Category::factory(),
            "rule" => "required|string",
            "title" => "brand",
            "value" => null,
        ];
    }
}
