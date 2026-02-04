<?php

use App\Percentages;
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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->boolean('is_retail_sale')->default(false);
            $table->enum('selected_percentage', array_column(Percentages::cases(), 'value'))->default('first_wholesale_percentage');
            $table->decimal('total', 10, 2);
            $table->foreignId('sale_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('product_id')
                ->constrained()
                ->onUpdate('cascade');
            $table->foreignId('price_modification_id')
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
        Schema::dropIfExists('sale_items');
    }
};
