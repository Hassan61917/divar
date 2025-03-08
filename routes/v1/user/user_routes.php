<?php

use App\Http\Controllers\Api\v1\User\UserAdsAlarmController;
use App\Http\Controllers\Api\v1\User\UserAdsController;
use App\Http\Controllers\Api\v1\User\UserAlarmOrderController;
use App\Http\Controllers\Api\v1\User\UserAuctionBidController;
use App\Http\Controllers\Api\v1\User\UserAuctionController;
use App\Http\Controllers\Api\v1\User\UserBlockController;
use App\Http\Controllers\Api\v1\User\UserCommentController;
use App\Http\Controllers\Api\v1\User\UserDiscountController;
use App\Http\Controllers\Api\v1\User\UserLadderOrderController;
use App\Http\Controllers\Api\v1\User\UserMessageController;
use App\Http\Controllers\Api\v1\User\UserOfferOrderController;
use App\Http\Controllers\Api\v1\User\UserProfileController;
use App\Http\Controllers\Api\v1\User\UserQuestionController;
use App\Http\Controllers\Api\v1\User\UserReportController;
use App\Http\Controllers\Api\v1\User\UserTicketController;
use App\Http\Controllers\Api\v1\User\UserWalletController;
use App\Http\Controllers\Api\v1\User\UserWalletTransactionController;
use App\Http\Controllers\Api\v1\User\UserWishlistController;
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

Route::apiResource("questions", UserQuestionController::class);
Route::post("/questions/{question}/answer", [UserQuestionController::class, "answer"])->name("questions.answer");

Route::apiResource("blocks", UserBlockController::class);
Route::apiResource("messages", UserMessageController::class)->except("index");
Route::get("inbox", [UserMessageController::class, "inbox"])->name("inbox");
Route::get("outbox", [UserMessageController::class, "outbox"])->name("outbox");
Route::get("chats", [UserMessageController::class, "chats"])->name("chats");
Route::get("chats/{user}/chat", [UserMessageController::class, "chat"])->name("chat");

Route::apiResource("comments", UserCommentController::class);
Route::get("my-ads-comments",[UserCommentController::class, "myAdsComments"])->name("comments.my-ads");

Route::apiResource("wishlist", UserWishlistController::class)->except("update");

Route::apiResource("ladder-orders", UserLadderOrderController::class);
Route::post("ladder-orders/{ladder_order}/cancel", [UserLadderOrderController::class, "cancel"])->name("ladder-orders.cancel");

Route::apiResource("tickets", UserTicketController::class);
Route::post("tickets/{ticket}/add-message", [UserTicketController::class, "addMessage"])->name("tickets.add-message");
Route::post("tickets/{ticket}/close", [UserTicketController::class, "close"])->name("tickets.close");

Route::apiResource("reports", UserReportController::class)->except("update");

Route::apiResource("alarm-orders", UserAlarmOrderController::class)->except("destroy","update");
Route::apiResource("alarm-orders/{alarm_order}/ads-alarms", UserAdsAlarmController::class);

Route::apiResource("auctions", UserAuctionController::class);

Route::apiResource("auction-bids", UserAuctionBidController::class);
