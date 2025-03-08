<?php

namespace App\Http\Requests\v1\Admin;

use App\Http\Requests\AppFormRequest;

class AdminReportRuleRequest extends AppFormRequest
{
    public function rules(): array
    {
        return [
            "category_id" => ["required", "exists:report_categories,id"],
            "count" => ["required", "integer", "min:1"],
            "duration" => ["required", "integer", "min:1"],
        ];
    }
}
