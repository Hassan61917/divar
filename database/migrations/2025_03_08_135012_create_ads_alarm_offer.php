<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ads_alarm_offer', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained("alarm_orders");
            $table->foreignId("ads_id")->constrained("ads");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_alarm_offer');
    }
};
