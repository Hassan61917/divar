<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserStoreAdsRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",
            "location"=>"required|string",
        ];
    }
}

