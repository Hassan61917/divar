<?php

namespace Database\Factories;

use App\Models\Ads;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "ads_id" => Ads::factory(),
            "ip" => $this->faker->ipv4(),
        ];
    }
}
