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
        Schema::create('wings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('imported_number', 100)->unique()->nullable();
            $table->string('bin_number', 50)->unique()->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('image')->nullable();
            $table->string('authority_signature')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
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
        Schema::dropIfExists('wings');
    }
};