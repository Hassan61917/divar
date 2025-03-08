<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Admin\AdminAdsFieldRequest;
use App\Http\Resources\v1\AdsFieldResource;
use App\Models\AdsField;
use App\ModelServices\Ads\AdsFiledService;
use Illuminate\Http\JsonResponse;

class AdminAdsFieldController extends Controller
{
    protected string $resource = AdsFieldResource::class;

    public function __construct(
        private AdsFiledService $filedService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $fields = $this->filedService->getFields(["category"]);
        return $this->ok($this->paginate($fields));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAdsFieldRequest $request): JsonResponse
    {
        $data = $request->validated();
        $field = $this->filedService->makeField($data);
        $field->load("category");
        return $this->ok($field);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdsField $field): JsonResponse
    {
        $field->load("category");
        return $this->ok($field);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminAdsFieldRequest $request, AdsField $field): JsonResponse
    {
        $data = $request->validated();
        $field->update($data);
        $field->load("category");
        return $this->ok($field);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdsField $field): JsonResponse
    {
        $field->delete();
        return $this->deleted();
    }
}
