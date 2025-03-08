<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;
use App\Rules\ParentCategoryRule;

class AdminAdsOfferRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => ["required", "exists:categories,id", new ParentCategoryRule()],
            "price" => "required|integer|min:1",
            "duration" => "required|integer|min:1",
            "count" => "required|integer|min:1",
        ];
    }
}
