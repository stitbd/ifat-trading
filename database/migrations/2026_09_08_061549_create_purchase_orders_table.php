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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('requisition_id')
                ->constrained('requisitions')
                ->cascadeOnDelete();

            $table->foreignId('cs_id')
                ->constrained('comparative_statements')
                ->cascadeOnDelete();

            $table->foreignId('cs_details_id')
                ->constrained('comparative_statement_details')
                ->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');

            $table->string('po_no')->unique();
            $table->date('po_date');

            $table->date('time_of_supply')->nullable();
            $table->string('place_of_supply')->nullable();
            $table->string('contact_person')->nullable();

            // 1 = Pending, 2 = Forwarded, 3 = Approved, 4 = Rejected ... (customize as needed)
            $table->tinyInteger('status')->default(1);

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
