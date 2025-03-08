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
        Schema::create('ads_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->nullable()->constrained("categories");
            $table->unsignedInteger("count");
            $table->unsignedInteger("price");
            $table->unsignedInteger("duration")->default(365);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_offers');
    }
};
