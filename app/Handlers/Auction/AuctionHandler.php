<?php

namespace App\Handlers\Auction;


use App\Exceptions\ModelException;
use App\Handlers\Auction\Rules\CheckAuctions;
use App\Handlers\ModelHandler;
use App\Models\Auction;
use Illuminate\Auth\Access\AuthorizationException;


class AuctionHandler extends ModelHandler
{
    protected array $rules = [
        "user", "price", "status", CheckAuctions::class
    ];

    protected function user(Auction $auction): void
    {
        if (!$auction->user->is($auction->ads->user)) {
            throw new AuthorizationException("only ads owner can create auction");
        }
    }

    public function price(Auction $auction): void
    {
        if ($auction->start_price > $auction->ads->price) {
            throw new ModelException("Price is out of range");
        }
    }

    protected function status(Auction $auction): void
    {
        if (!$auction->ads->isPublished()) {
            throw new ModelException("only published ads can have auction");
        }
    }
}
