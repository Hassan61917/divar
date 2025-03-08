<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Symfony\Component\HttpFoundation\JsonResponse;

class FrontCategoryController extends Controller
{
    protected string $resource = CategoryResource::class;

    public function index(): JsonResponse
    {
        $categories = Category::query()->parent()->with("children");
        return $this->ok($this->paginate($categories));
    }
}
