<?php

namespace Database\Factories;

use App\Models\Ads;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends AppFactory
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
            "ads_id" => Ads::factory()->complete()->create(),
            "question" => $this->faker->sentence(),
            "answer" => $this->faker->text(),
        ];
    }
}
