<?php

use App\Http\Controllers\Api\v1\User\UserAdsController;
use App\Http\Controllers\Api\v1\User\UserDiscountController;
use App\Http\Controllers\Api\v1\User\UserOfferOrderController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
use App\Http\Controllers\Api\v1\User\UserWalletController;
use App\Http\Controllers\Api\v1\User\UserWalletTransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource("/profile", UserProfileController::class)->except(["show", "delete"]);

Route::prefix("wallet")->name("wallet.")->group(function () {
    Route::get("/", [UserWalletController::class, "index"])->name("index");
    Route::post("update-password", [UserWalletController::class, "setPassword"])->name("update-password");
//    Route::post("/deposit", [UserWalletController::class, "deposit"])->name("deposit");
//    Route::post("/withdraw", [UserWalletController::class, "withdraw"])->name("withdraw");
});
Route::apiResource("wallet-transactions", UserWalletTransactionController::class)->except("store", "destroy");

Route::apiResource("advertises", UserAdsController::class);
Route::get("advertises/{advertise}/get-fields", [UserAdsController::class, "getFields"])->name("advertises.get-fields");
Route::post("advertises/{advertise}/save-fields", [UserAdsController::class, "saveFields"])->name("advertises.save-fields");
Route::post("advertises/{advertise}/publish", [UserAdsController::class, "publish"])->name("advertises.publish");
Route::post("advertises/{advertise}/delete-reason",[UserAdsController::class, "deleteReason"])->name("advertises.delete-reason");

Route::get("used-discounts", [UserDiscountController::class, "used"])->name("discounts.used");
Route::get("discounts", [UserDiscountController::class, "index"])->name("discounts.index");
Route::get("discounts/{discount}", [UserDiscountController::class, "show"])->name("discounts.show");

Route::apiResource("offer-orders",UserOfferOrderController::class)->except("update", "destroy");
