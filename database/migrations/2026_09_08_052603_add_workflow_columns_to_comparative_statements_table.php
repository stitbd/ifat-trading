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
        Schema::table('comparative_statements', function (Blueprint $table) {
            $table->foreignId('forwarded_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->timestamp('forwarded_at')->nullable()->after('forwarded_by');

            $table->foreignId('sci_approved_by')->nullable()->after('forwarded_at')->constrained('users')->nullOnDelete();
            $table->timestamp('sci_approved_at')->nullable()->after('sci_approved_by');
            $table->text('sci_remarks')->nullable()->after('sci_approved_at');

            $table->foreignId('sci_rejected_by')->nullable()->after('sci_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('sci_rejected_at')->nullable()->after('sci_rejected_by');

            $table->foreignId('om_approved_by')->nullable()->after('sci_rejected_at')->constrained('users')->nullOnDelete();
            $table->timestamp('om_approved_at')->nullable()->after('om_approved_by');
            $table->text('om_remarks')->nullable()->after('om_approved_at');

            $table->foreignId('om_rejected_by')->nullable()->after('om_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('om_rejected_at')->nullable()->after('om_rejected_by');

            $table->foreignId('md_approved_by')->nullable()->after('om_rejected_at')->constrained('users')->nullOnDelete();
            $table->timestamp('md_approved_at')->nullable()->after('md_approved_by');
            $table->text('md_remarks')->nullable()->after('md_approved_at');

            $table->foreignId('md_rejected_by')->nullable()->after('md_remarks')->constrained('users')->nullOnDelete();
            $table->timestamp('md_rejected_at')->nullable()->after('md_rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comparative_statements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('forwarded_by');
            $table->dropConstrainedForeignId('sci_approved_by');
            $table->dropConstrainedForeignId('sci_rejected_by');
            $table->dropConstrainedForeignId('om_approved_by');
            $table->dropConstrainedForeignId('om_rejected_by');
            $table->dropConstrainedForeignId('md_approved_by');
            $table->dropConstrainedForeignId('md_rejected_by');

            $table->dropColumn([
                'forwarded_at',
                'sci_approved_at',
                'sci_remarks',
                'sci_rejected_at',
                'om_approved_at',
                'om_remarks',
                'om_rejected_at',
                'md_approved_at',
                'md_remarks',
                'md_rejected_at',
            ]);
        });
    }
};
