<?php

namespace Database\Factories;

use App\Enums\AdsStatus;
use App\Models\Ads;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ads = Ads::factory()
            ->state(["status" => AdsStatus::Published->value])
            ->complete()
            ->create();
        return [
            "ads_id" => $ads->id,
            "user_id" => $ads->user_id,
            "start_price" => 100,
            "step" => 1,
            "started_at" => now(),
            "finished_at" => now()->addDay()
        ];
    }
}
