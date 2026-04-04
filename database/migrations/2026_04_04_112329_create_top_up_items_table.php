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
        Schema::create('top_up_items', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique();
            $table->string('api_category_id');

            $table->foreignId('direct_top_up_id')
                ->nullable()
                ->constrained('direct_top_ups')
                ->nullOnDelete();

            $table->string('name');
            $table->string('category_id')->nullable();
            $table->string('par_value_currency', 10)->nullable();
            $table->decimal('par_value', 10, 2)->default(0);
            $table->string('currency', 10)->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('origin_price', 10, 2)->default(0);
            $table->integer('discount_rate')->default(0);
            $table->decimal('min_amount', 10, 2)->default(1);
            $table->decimal('max_amount', 10, 2)->default(1);
            $table->boolean('account_check')->default(false);
            $table->boolean('status')->default(true);
            $table->decimal('profit_margin', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_up_items');
    }
};
