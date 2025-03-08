<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\AppFormRequest;

class UserCommentRequest extends AppFormRequest
{
    protected array $unset = ["post_id"];

    public function rules(): array
    {
        return [
            "ads_id" => "required|exists:ads,id",
            "comment" => "required|string",
            "parent_id" => "nullable|exists:comments,id",
        ];
    }
}
