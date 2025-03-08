<?php

namespace Database\Factories;

use App\Models\AdsOffer;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OfferOrder>
 */
class OfferOrderFactory extends AppFactory
{
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "offer_id" => $this->randomModel(AdsOffer::class),
            "expired_at" => now()->addYear()
        ];
    }
}
