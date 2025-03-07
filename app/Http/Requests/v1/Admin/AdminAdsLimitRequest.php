<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminAdsLimitRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id|unique:ads_limits,category_id",
            "limit" => "required|integer",
            "duration" => "required|integer|min:1|max:365"
        ];
    }
}
