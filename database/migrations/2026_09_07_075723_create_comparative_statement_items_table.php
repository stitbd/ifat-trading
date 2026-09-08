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
        Schema::create('comparative_statement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cs_detail_id')->constrained('comparative_statement_details')->onDelete('cascade');
            $table->foreignId('requisition_detail_id')->constrained('requisition_details')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('cs_qty', 20, 2);
            $table->decimal('unit_price', 20, 2)->default(0);
            $table->decimal('total', 20, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparative_statement_items');
    }
};
