<?php

namespace Database\Factories;

use App\Enums\AdsStatus;
use App\Enums\ShowStatus;
use App\Models\Ads;
use App\Models\Ladder;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LadderOrder>
 */
class LadderOrderFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "ladder_id" => $this->randomModel(Ladder::class),
            "user_id" => User::factory(),
            "ads_id" => Ads::factory()->complete()->create(["status" => AdsStatus::Published->value]),
            "status" => ShowStatus::Waiting->value,
            "show_at" => now(),
            "end_at" => null,
        ];
    }
}
