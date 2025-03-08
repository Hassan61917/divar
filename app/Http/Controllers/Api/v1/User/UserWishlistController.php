<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Resources\v1\WishlistResource;
use App\Models\Ads;
use App\Models\Wishlist;
use App\ModelServices\Social\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserWishlistController extends AuthUserController
{
    protected string $resource = WishlistResource::class;

    public function __construct(
        public WishlistService $wishlistService
    )
    {
    }

    public function index(): JsonResponse
    {
        $wishList = $this->wishlistService->getAllFor($this->authUser(), ["item"]);
        return $this->ok($this->paginate($wishList));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            "ads_id" => "required|exists:ads,id",
        ]);
        $ads = Ads::find($data['ads_id']);
        $wish = $this->wishlistService->make($this->authUser(), $ads);
        return $this->ok($wish);
    }

    public function show(Wishlist $wish): JsonResponse
    {
        $wish->load("ads");
        return $this->ok($wish);
    }

    public function destroy(Wishlist $wish): JsonResponse
    {
        $wish->delete();
        return $this->deleted();
    }
}
