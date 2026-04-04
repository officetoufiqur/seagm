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
        Schema::create('top_up_fields', function (Blueprint $table) {
            $table->id();
            $table->string('api_item_id');

            $table->foreignId('top_up_item_id')
                ->nullable()
                ->constrained('top_up_items')
                ->nullOnDelete();

            $table->string('name'); 
            $table->string('type'); 
            $table->string('label');
            $table->string('label_zh')->nullable();
            $table->boolean('multiline')->default(false);
            $table->string('placeholder')->nullable();
            $table->string('prefix')->nullable();
            $table->integer('position')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_up_fields');
    }
};
