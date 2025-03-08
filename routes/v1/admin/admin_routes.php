<?php

use App\Http\Controllers\Api\v1\Admin\AdminAdsController;
use App\Http\Controllers\Api\v1\Admin\AdminAdsFieldController;
use App\Http\Controllers\Api\v1\Admin\AdminAdsLimitController;
use App\Http\Controllers\Api\v1\Admin\AdminAdsOfferController;
use App\Http\Controllers\Api\v1\Admin\AdminAlarmOfferController;
use App\Http\Controllers\Api\v1\Admin\AdminAuctionController;
use App\Http\Controllers\Api\v1\Admin\AdminBanController;
use App\Http\Controllers\Api\v1\Admin\AdminCategoryController;
use App\Http\Controllers\Api\v1\Admin\AdminCityController;
use App\Http\Controllers\Api\v1\Admin\AdminCommentController;
use App\Http\Controllers\Api\v1\Admin\AdminDeleteReasonController;
use App\Http\Controllers\Api\v1\Admin\AdminDiscountController;
use App\Http\Controllers\Api\v1\Admin\AdminLadderController;
use App\Http\Controllers\Api\v1\Admin\AdminLadderOrderController;
use App\Http\Controllers\Api\v1\Admin\AdminMessageController;
use App\Http\Controllers\Api\v1\Admin\AdminOfferOrderController;
use App\Http\Controllers\Api\v1\Admin\AdminOrderController;
use App\Http\Controllers\Api\v1\Admin\AdminQuestionController;
use App\Http\Controllers\Api\v1\Admin\AdminReportCategoryController;
use App\Http\Controllers\Api\v1\Admin\AdminReportController;
use App\Http\Controllers\Api\v1\Admin\AdminRoleController;
use App\Http\Controllers\Api\v1\Admin\AdminStateController;
use App\Http\Controllers\Api\v1\Admin\AdminTicketCategoryController;
use App\Http\Controllers\Api\v1\Admin\AdminTicketController;
use App\Http\Controllers\Api\v1\Admin\AdminUserController;
use App\Http\Controllers\Api\v1\Admin\AdminVisitController;
use App\Http\Controllers\Api\v1\Admin\AdminWalletController;
use App\Http\Controllers\Api\v1\Admin\AdminWalletTransactionController;
use App\Http\Controllers\Api\v1\Admin\AdminWishlistController;
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

Route::apiResource("orders", AdminOrderController::class)->except("store", "update");

Route::apiResource("ads-offers", AdminAdsOfferController::class);

Route::apiResource("offer-orders", AdminOfferOrderController::class)->only("index", "show");

Route::apiResource("questions", AdminQuestionController::class)->except("store");

Route::apiResource("messages", AdminMessageController::class)->except("store");

Route::apiResource("comments", AdminCommentController::class);

Route::apiResource("wishlist", AdminWishlistController::class)->only("index", "show");

Route::apiResource("ladders", AdminLadderController::class);
Route::apiResource("ladder-orders", AdminLadderOrderController::class)->except("store");
Route::post("ladder-orders/{ladder_order}/show", [AdminLadderOrderController::class, "show"])->name("ladder-orders.show");
Route::post("ladder-orders/{ladder_order}/cancel", [AdminLadderOrderController::class, "cancel"])->name("ladder-orders.cancel");
Route::post("ladder-orders/{ladder_order}/complete", [AdminLadderOrderController::class, "complete"])->name("ladder-orders.complete");

Route::apiResource("ticket-categories", AdminTicketCategoryController::class);
Route::apiResource("tickets", AdminTicketController::class);
Route::get("tickets/closed", [AdminTicketController::class, "closed"])->name("tickets.closed");
Route::post("tickets/{ticket}/answer", [AdminTicketController::class, "answer"])->name("tickets.answer");
Route::post("tickets/{ticket}/close", [AdminTicketController::class, "close"])->name("tickets.close");

Route::apiResource("report-categories", AdminReportCategoryController::class);
Route::apiResource("report-rules", AdminReportCategoryController::class);
Route::apiResource("reports", AdminReportController::class)->except("update");

Route::apiResource("alarm-offers", AdminAlarmOfferController::class);

Route::apiResource("auctions", AdminAuctionController::class)->except("store", "update");

Route::delete("visits/destory-trashed", [AdminVisitController::class, "destroyTrashed"])->name("visits.destory-all");
Route::apiResource("visits", AdminVisitController::class)->except("update", "store");

