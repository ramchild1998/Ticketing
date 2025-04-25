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
        Schema::create('poscode', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->unsignedInteger('poscode'); // int UN
            $table->unsignedBigInteger('subdistrict_id'); // int UN

            $table->foreign('subdistrict_id')->references('id')->on('subdistrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poscode');
    }
};
