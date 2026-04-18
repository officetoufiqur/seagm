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
        Schema::create('star_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('star_id')->constrained('stars')->onDelete('cascade');
            $table->string('title');
            $table->string('subtitle');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('star_items');
    }
};
