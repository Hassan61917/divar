<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserQuestionRequest extends AppFormRequest
{
    protected array $unset = ["ads_id"];

    public function rules(): array
    {
        return [
            "ads_id" => "required|exists:ads,id",
            "question" => "required|string",
        ];
    }
}

