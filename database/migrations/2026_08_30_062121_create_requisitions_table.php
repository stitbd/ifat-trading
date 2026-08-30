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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_no')->unique();

            $table->foreignId('wing_id')
                ->constrained('wings')
                ->onDelete('cascade');

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->onDelete('cascade');

            $table->enum('requisition_type', ['local', 'import'])
                ->default('local');

            $table->decimal('total_quantity', 20, 2)->nullable();

            $table->date('date');

            $table->text('note')->nullable();

            $table->string('place_of_supply')->nullable();

            $table->tinyInteger('status')->default(1);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};