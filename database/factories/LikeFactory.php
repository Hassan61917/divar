<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $review = Question::factory()->create();
        return [
            "user_id" => $this->randomModel(User::class),
            "likeable_type" => $review::class,
            "likeable_id" => $review->id,
            "isLike" => $this->faker->boolean(),
        ];
    }
}
