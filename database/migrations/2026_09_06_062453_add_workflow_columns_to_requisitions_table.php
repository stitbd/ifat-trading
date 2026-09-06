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
        Schema::table('requisitions', function (Blueprint $table) {
            // Overall workflow status
            $table->enum('workflow_status', [
                'pending',
                'forwarded_to_sci',
                'sci_approved',
                'sci_rejected',
                'forwarded_to_om',
                'om_approved',
                'om_rejected',
                'forwarded_to_md',
                'md_approved',
                'md_rejected',
                'cs_generated',
            ])->default('pending')->after('status');

            // Step 1: General User -> forward to SCI
            $table->unsignedBigInteger('forwarded_by')->nullable()->after('workflow_status');
            $table->timestamp('forwarded_at')->nullable()->after('forwarded_by');

            // Step 2: Supply Chain Incharge
            $table->unsignedBigInteger('sci_approved_by')->nullable()->after('forwarded_at');
            $table->timestamp('sci_approved_at')->nullable()->after('sci_approved_by');
            $table->unsignedBigInteger('sci_rejected_by')->nullable()->after('sci_approved_at');
            $table->timestamp('sci_rejected_at')->nullable()->after('sci_rejected_by');
            $table->text('sci_remarks')->nullable()->after('sci_rejected_at');

            // Step 3: Operation Manager
            $table->unsignedBigInteger('om_approved_by')->nullable()->after('sci_remarks');
            $table->timestamp('om_approved_at')->nullable()->after('om_approved_by');
            $table->unsignedBigInteger('om_rejected_by')->nullable()->after('om_approved_at');
            $table->timestamp('om_rejected_at')->nullable()->after('om_rejected_by');
            $table->text('om_remarks')->nullable()->after('om_rejected_at');

            // Step 4: MD
            $table->unsignedBigInteger('md_approved_by')->nullable()->after('om_remarks');
            $table->timestamp('md_approved_at')->nullable()->after('md_approved_by');
            $table->unsignedBigInteger('md_rejected_by')->nullable()->after('md_approved_at');
            $table->timestamp('md_rejected_at')->nullable()->after('md_rejected_by');
            $table->text('md_remarks')->nullable()->after('md_rejected_at');

            // Step 5: CS generation
            $table->unsignedBigInteger('cs_generated_by')->nullable()->after('md_remarks');
            $table->timestamp('cs_generated_at')->nullable()->after('cs_generated_by');

            // Foreign keys
            $table->foreign('forwarded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sci_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sci_rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('om_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('om_rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('md_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('md_rejected_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cs_generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropForeign(['forwarded_by']);
            $table->dropForeign(['sci_approved_by']);
            $table->dropForeign(['sci_rejected_by']);
            $table->dropForeign(['om_approved_by']);
            $table->dropForeign(['om_rejected_by']);
            $table->dropForeign(['md_approved_by']);
            $table->dropForeign(['md_rejected_by']);
            $table->dropForeign(['cs_generated_by']);

            $table->dropColumn([
                'workflow_status',
                'forwarded_by',
                'forwarded_at',
                'sci_approved_by',
                'sci_approved_at',
                'sci_rejected_by',
                'sci_rejected_at',
                'sci_remarks',
                'om_approved_by',
                'om_approved_at',
                'om_rejected_by',
                'om_rejected_at',
                'om_remarks',
                'md_approved_by',
                'md_approved_at',
                'md_rejected_by',
                'md_rejected_at',
                'md_remarks',
                'cs_generated_by',
                'cs_generated_at',
            ]);
        });
    }
};
