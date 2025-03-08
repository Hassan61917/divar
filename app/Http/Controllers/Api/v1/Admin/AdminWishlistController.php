<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\WishlistResource;
use App\Models\Wishlist;
use App\ModelServices\Social\WishlistService;
use Illuminate\Http\JsonResponse;

class AdminWishlistController extends Controller
{
    protected string $resource = WishlistResource::class;
    public function __construct(
        private WishlistService $wishlistService
    ) {}

    public function index(): JsonResponse
    {
        $wishlist = $this->wishlistService->getAll(["ads"]);
        return $this->ok($this->paginate($wishlist));
    }

    public function show(Wishlist $wish): JsonResponse
    {
        $wish->load("user", "ads");
        return $this->ok($wish);
    }
}
