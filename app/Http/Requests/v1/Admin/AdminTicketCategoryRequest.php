<?php

namespace App\Http\Requests\v1\Admin;

use App\Enums\PriorityType;
use App\Http\Requests\AppFormRequest;
use App\Rules\EnumRule;

class AdminTicketCategoryRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "title" => "required|string",
            "priority" => ["required", new EnumRule(PriorityType::class)],
            "auto_close" => "nullable|integer|between:1,10",
        ];
    }
}
