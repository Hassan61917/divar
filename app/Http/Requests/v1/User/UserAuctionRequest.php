<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserAuctionRequest extends AppFormRequest
{

    public function rules(): array
    {
        return [
            "ads_id" => "required|exists:ads,id",
            "start_price" => "required|numeric",
            "step" => "nullable|numeric|between:1,10",
            "started_at" => "nullable",
        ];
    }
}
