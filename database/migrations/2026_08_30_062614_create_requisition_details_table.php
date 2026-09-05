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
        Schema::create('requisition_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')
                ->constrained('requisitions')
                ->onDelete('cascade');

            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');

            $table->decimal('physical_stock', 20, 2);
            $table->decimal('in_transit_stock', 20, 2);
            $table->decimal('lc_pending_stock', 20, 2);
            $table->decimal('pi_stock', 20, 2);
            $table->decimal('sale_one_stock', 20, 2);
            $table->decimal('sale_two_stock', 20, 2);
            $table->decimal('sale_three_stock', 20, 2);
            $table->decimal('required_stock', 20, 2);
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_details');
    }
};