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
        Schema::create('price_modifications', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_current')->default(true);
            $table->boolean('sold_by_retail')->default(false);
            $table->integer('retail_units_per_box')->nullable();
            $table->integer('remaining_stock');
            $table->integer('remaining_retail_stock')->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('first_wholesale_percentage', 0, 2)->default(0.10);
            $table->decimal('second_wholesale_percentage', 0, 2)->default(0.08);
            $table->decimal('third_wholesale_percentage', 0, 2)->default(0.05);
            $table->decimal('retail_percentage', 0, 2)->nullable();
            $table->foreignId('product_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('purchase_item_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_modifications');
    }
};
