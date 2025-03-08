<?php

use App\Http\Controllers\Api\v1\Admin\AdminAdsController;
use App\Http\Controllers\Api\v1\Admin\AdminAdsFieldController;
use App\Http\Controllers\Api\v1\Admin\AdminAdsLimitController;
use App\Http\Controllers\Api\v1\Admin\AdminBanController;
use App\Http\Controllers\Api\v1\Admin\AdminCategoryController;
use App\Http\Controllers\Api\v1\Admin\AdminCityController;
use App\Http\Controllers\Api\v1\Admin\AdminDeleteReasonController;
use App\Http\Controllers\Api\v1\Admin\AdminDiscountController;
use App\Http\Controllers\Api\v1\Admin\AdminRoleController;
use App\Http\Controllers\Api\v1\Admin\AdminStateController;
use App\Http\Controllers\Api\v1\Admin\AdminUserController;
use App\Http\Controllers\Api\v1\Admin\AdminWalletController;
use App\Http\Controllers\Api\v1\Admin\AdminWalletTransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource("roles", AdminRoleController::class);

Route::apiResource("users", AdminUserController::class);
Route::post("/users/{user}/{role}/add-role", [AdminUserController::class, 'addRole'])->name('users.add-role');
Route::delete("/users/{user}/{role}/remove-role", [AdminUserController::class, 'removeRole'])->name('users.remove-role');

Route::apiResource("bans", AdminBanController::class);
Route::post("/bans/{user}/ban", [AdminBanController::class, "ban"])->name("user.ban");
Route::post("/bans/{user}/unban", [AdminBanController::class, "unban"])->name("user.unban");

Route::apiResource("states", AdminStateController::class);
Route::apiResource("cities", AdminCityController::class);

Route::apiResource("wallets", AdminWalletController::class)->except(["update", "destroy"]);
Route::prefix("wallets/{wallet}")->name("wallets.")->group(function () {
    Route::post("/block", [AdminWalletController::class, "block"])->name("block");
    Route::post("/unblock", [AdminWalletController::class, "unblock"])->name("unblock");
    Route::post("/deposit", [AdminWalletController::class, "deposit"])->name("deposit");
    Route::post("/withdraw", [AdminWalletController::class, "withdraw"])->name("withdraw");
    Route::post("/{destination}/transfer", [AdminWalletController::class, "transfer"])->name("transfer");
});
Route::apiResource("wallet-transactions", AdminWalletTransactionController::class)->except(["store", "update"]);

Route::apiResource("categories", AdminCategoryController::class);

Route::apiResource("ads-limit", AdminAdsLimitController::class);

Route::apiResource("advertises",AdminAdsController::class)->except("store");

Route::apiResource("ads-fields", AdminAdsFieldController::class);

Route::apiResource("delete-reasons", AdminDeleteReasonController::class);

Route::apiResource("discounts", AdminDiscountController::class);
