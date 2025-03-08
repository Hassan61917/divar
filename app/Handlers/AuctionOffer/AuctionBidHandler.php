<?php

namespace App\Handlers\AuctionOffer;

use App\Exceptions\ModelException;
use App\Handlers\ModelHandler;
use App\Models\AuctionBid;

class AuctionBidHandler extends ModelHandler
{
    protected array $rules = [
        "user", "price"
    ];

    public function user(AuctionBid $offer): void
    {
        if ($offer->user()->is($offer->auction->user)) {
            throw new ModelException("you can't offer your auctions");
        }
    }

    public function price(AuctionBid $offer): void
    {
        $auction = $offer->auction;
        $lastOffer = $auction->lastBid();
        $amount = ($auction->ads->price / 100) * $auction->step;
        $price = $lastOffer ? $lastOffer->price : $auction->start_price;
        $price += $amount;
        if ($offer->price < $price) {
            throw new ModelException("price is out of range");
        }
    }
}
