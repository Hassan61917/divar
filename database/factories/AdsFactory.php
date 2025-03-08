<?php

namespace Database\Factories;

use App\Enums\AdsStatus;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ads>
 */
class AdsFactory extends AppFactory
{
    protected bool $completed = false;

    public function complete(): static
    {
        $this->completed = true;
        return $this;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $result = [
            "user_id" => User::factory(),
            "category_id" => Category::factory(),
            "status" => AdsStatus::Draft->value,
            "state_id" => $this->randomModel(State::class),
            "city_id" => $this->randomModel(City::class),
            "location" => $this->faker->streetName(),
        ];
        if ($this->completed) {
            $result = array_merge($result, $this->getCompletedData());
        }
        return $result;
    }

    private function getCompletedData(): array
    {
        return [
            "is_validated" => true,
            "title" => $this->faker->sentence(),
            "description" => $this->faker->paragraph(),
            "price" => 1000,
            "chat" => true,
            "call" => true,
            "phone" => null,
            "status" => AdsStatus::Completed->value,
            "fields"=>[]
        ];
    }
}
