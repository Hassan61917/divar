<?php

use App\Http\Controllers\Api\v1\Front\FrontAdsController;
use App\Http\Controllers\Api\v1\Front\FrontAuctionController;
use App\Http\Controllers\Api\v1\Front\FrontCategoryController;
use Illuminate\Support\Facades\Route;

Route::get("/categories", [FrontCategoryController::class, "index"])->name("categories.index");

Route::get("/advertises", [FrontAdsController::class, "index"])->name("advertises.index");
Route::get("/advertises/{advertise}", [FrontAdsController::class, "show"])->name("advertises.show");

Route::get("/auctions", [FrontAuctionController::class, "index"])->name("auctions.index");
Route::get("/auctions/{auction}", [FrontAuctionController::class, "show"])->name("auctions.show");
