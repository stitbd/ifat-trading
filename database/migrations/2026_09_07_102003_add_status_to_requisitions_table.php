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

            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('
                    1=Pending,
                    2=Forwarded to SCI,
                    3=Rejected by SCI,
                    4=Forwarded to OM,
                    5=Rejected by OM,
                    6=Forwarded to MD,
                    7=Approved by MD,
                    8=Rejected by MD,
                    9=CS Generated Partially,
                    10=CS Generated
                ')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {

            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('')
                ->change();
        });
    }
};
