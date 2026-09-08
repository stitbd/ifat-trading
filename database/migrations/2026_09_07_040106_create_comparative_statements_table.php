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
        Schema::create('comparative_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->constrained('requisitions')->onDelete('restrict');
            $table->string('cs_no')->unique();
            $table->date('cs_date');
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->comment('
                    1=Pending,
                    2=Forwarded to SCI,
                    3=Rejected by SCI,
                    4=Forwarded to OM,
                    5=Rejected by OM,
                    6=Forwarded to MD,
                    7=Approved by MD,
                    8=Rejected by MD,
                    9=CS Approved
                ')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparative_statements');
    }
};
