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
        Schema::create('card_categories', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique();
            $table->string('name');
            $table->string('code');
            $table->string('mode')->nullable();
            $table->string('region')->nullable();
            $table->string('publisher')->nullable();
            $table->boolean('auto_delivery')->default(true);
            $table->text('icon')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_categories');
    }
};
