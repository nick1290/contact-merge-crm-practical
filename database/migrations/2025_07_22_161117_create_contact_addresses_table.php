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
        Schema::create('contact_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contact_id');
            $table->text('address')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('landmark', 255)->nullable();
            $table->string('pincode', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('country', 255)->nullable()->default('India');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_addresses');
    }
};
