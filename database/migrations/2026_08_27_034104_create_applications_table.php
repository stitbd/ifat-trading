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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->text('address')->nullable();
            $table->longText('about_company')->nullable();
            $table->string('phone')->nullable();
            $table->string('google_map')->nullable();
            $table->string('company_website_link')->nullable();
            $table->string('logo')->nullable();
            $table->string('fav_icon')->nullable();
            $table->string('copy_right_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
