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
        Schema::create('direct_top_ups', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique(); 
            $table->string('name');
            $table->string('code');
            $table->string('mode'); 
            $table->string('region');
            $table->boolean('auto_delivery')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_top_ups');
    }
};
