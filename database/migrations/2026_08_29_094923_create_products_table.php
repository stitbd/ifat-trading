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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code',25);
            $table->string('name',100)->nullable();
            $table->foreignId('wing_id')->nullable()->constrained('wings')->nullOnDelete();
            $table->foreignId('categories_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('sub_categories_id')->nullable()->constrained('subcategories')->nullOnDelete();
             $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
             $table->foreignId('manufacturer_id')->nullable()->constrained('manufacturers')->nullOnDelete();
             $table->foreignId('country_of_origin_id')->nullable()->constrained('country_of_origins')->nullOnDelete();
             $table->foreignId('product_type_id')->nullable()->constrained('product_types')->nullOnDelete();
             $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->nullOnDelete();
             $table->foreignId('product_size_id')->nullable()->constrained('product_sizes')->nullOnDelete();
             $table->foreignId('warranty_period_id')->nullable()->constrained('warranty_periods')->nullOnDelete();
             $table->foreignId('vat_percentage_id')->nullable()->constrained('vat_percentages')->nullOnDelete();
             $table->string('position',100)->nullable();
             $table->string('unit_of_measurement',100)->nullable();
             $table->string('image')->nullable();
             $table->integer('min_alert_stock')->nullable();
             
            $table->tinyInteger('status')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
