<?php

use App\Enums\AdsStatus;
use App\Utils\EnumHelper;
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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("category_id")->constrained("categories")->onDelete("cascade");
            $table->foreignId("state_id")->constrained("states");
            $table->foreignId("city_id")->constrained("cities");
            $table->enum("status", EnumHelper::toArray(AdsStatus::class))->default(AdsStatus::Draft);
            $table->string("location");
            $table->boolean("is_validated")->default(false);
            $table->string("fields")->nullable();
            $table->string("title")->nullable();
            $table->text("description")->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->boolean('chat')->default(true);
            $table->boolean('call')->default(true);
            $table->unsignedInteger("phone")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
