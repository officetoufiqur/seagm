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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('card_categories')->onDelete('cascade');
            $table->string('api_id')->unique();
            $table->string('api_category');
            $table->string('category_name');
            $table->string('par_value_currency');
            
            $table->decimal('par_value', 10, 2);
            $table->string('currency');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->integer('min_amount');
            $table->decimal('origin_price', 15, 2);
            $table->integer('discount_rate');
            $table->longText('description')->nullable();

            $table->boolean('has_stock')->default(true);
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
