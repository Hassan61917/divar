<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserUpdateAdsRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "location" => "required|string",
            "title" => "required",
            "description" => "required",
            "price" => "required|integer|min:10",
            "chat" => "nullable|boolean",
            "call" => "nullable|boolean",
            "phone" => "nullable|integer|digits:10",
        ];
    }
}
