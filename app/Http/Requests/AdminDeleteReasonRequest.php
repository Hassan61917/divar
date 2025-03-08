<?php

namespace App\Http\Requests;

class AdminDeleteReasonRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "reason" => "required|string",
        ];
    }
}
