<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuctionBid>
 */
class AuctionBidFactory extends AppFactory
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
            "auction_id" => Auction::factory(),
            "price" => 120
        ];
    }
}
