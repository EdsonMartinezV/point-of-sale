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
            $table->string('name')->unique();
            $table->boolean('sold_separetely')->default(false);
            $table->integer('retail_units_per_box')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('retail_remaining_stock')->default(0);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('first_wholesale_percentage', 0, 2)->nullable();
            $table->decimal('second_wholesale_percentage', 0, 2)->nullable();
            $table->decimal('third_wholesale_percentage', 0, 2)->nullable();
            $table->decimal('retail_percentage', 0, 2)->nullable();
            $table->foreignId('category_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('measure_unit_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('retail_measure_unit_id') // Attention on index creation
                ->nullable()
                ->constrained('measure_units', 'id')
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
        Schema::dropIfExists('products');
    }
};
