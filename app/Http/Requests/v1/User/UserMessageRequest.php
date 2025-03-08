<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserMessageRequest extends AppFormRequest
{
    protected array $unset = ["receiver_id"];

    public function rules(): array
    {
        return [
            "receiver_id" => "required|exists:users,id",
            "message" => "required|string",
            "reply_id" => "nullable|exists:message,id",
        ];
    }
}
