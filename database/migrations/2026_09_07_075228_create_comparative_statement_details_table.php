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
        Schema::create('comparative_statement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cs_id')->constrained('comparative_statements')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('quotation_number')->nullable();
            $table->date('quotation_date')->nullable();
            $table->text('note')->nullable();
            $table->decimal('total_amount', 20, 2)->default(0);
            $table->date('time_of_supply')->nullable();
            $table->string('place_of_supply')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparative_statement_details');
    }
};
