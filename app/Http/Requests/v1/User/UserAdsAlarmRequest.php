<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserAdsAlarmRequest extends AppFormRequest
{
    protected array $unset = ["category_id"];

    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",
            "max_price" => "required|numeric",
            "title" => "nullable|string",
            "min_price" => "nullable|numeric",
        ];
    }
}
