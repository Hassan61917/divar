<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserAuctionBidRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "auction_id" => "required|exists:auctions,id",
            "price" => "required|numeric",
        ];
    }
}
