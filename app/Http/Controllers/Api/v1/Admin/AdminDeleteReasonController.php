<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDeleteReasonRequest;
use App\Http\Resources\v1\DeleteReasonResource;
use App\Models\DeleteReason;
use Illuminate\Http\JsonResponse;

class AdminDeleteReasonController extends Controller
{
    protected string $resource = DeleteReasonResource::class;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reasons = DeleteReason::query();
        return $this->ok($this->paginate($reasons));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminDeleteReasonRequest $request): JsonResponse
    {
        $data = $request->validated();
        $reason = DeleteReason::create($data);
        return $this->ok($reason);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeleteReason $reason): JsonResponse
    {
        return $this->ok($reason);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminDeleteReasonRequest $request, DeleteReason $reason): JsonResponse
    {
        $data = $request->validated();
        $reason->update($data);
        return $this->ok($reason);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteReason $reason): JsonResponse
    {
        $reason->delete();
        return $this->deleted();
    }
}
