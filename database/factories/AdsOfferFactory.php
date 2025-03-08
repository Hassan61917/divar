<?php

namespace Database\Factories;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdsOffer>
 */
class AdsOfferFactory extends AppFactory
{
    public function definition(): array
    {
        return [
            "category_id" => $this->randomModel(Category::class),
            "count" => 10,
            "price" => 100,
            "duration" => 365
        ];
    }
}
