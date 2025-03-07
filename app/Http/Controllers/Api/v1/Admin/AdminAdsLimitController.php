<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminAdsLimitRequest;
use App\Http\Resources\v1\AdsLimitResource;
use App\Models\AdsLimit;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAdsLimitController extends Controller
{
    protected string $resource = AdsLimitResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $limits = AdsLimit::query()->with("category");
        return $this->ok($this->paginate($limits));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAdsLimitRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = Category::find($data['category_id']);
        if ($category->parent_id) {
            return $this->error(422, "only parent category is valid");
        }
        $limit = AdsLimit::create($data);
        $limit->load("category");
        return $this->ok($limit);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdsLimit $adsLimit): JsonResponse
    {
        $adsLimit->load("category");
        return $this->ok($adsLimit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdsLimit $adsLimit): JsonResponse
    {
        $adsLimit->update($request->validated());
        $adsLimit->load("category");
        return $this->ok($adsLimit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdsLimit $adsLimit): JsonResponse
    {
        $adsLimit->delete();
        return $this->deleted();
    }
}
