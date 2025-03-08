<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminAdsFieldRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => "required|exists:categories,id",
            "title" => "required|string",
            "rule" => "required|string",
            "value" => "nullable|string",
        ];
    }
}
