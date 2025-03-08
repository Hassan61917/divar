<?php

namespace Database\Factories;

use App\Models\Ads;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => $this->randomModel(User::class),
            "ads_id" => Ads::factory()->complete(),
            "comment" => $this->faker->realText(),
            "parent_id" => null,
        ];
    }
}
