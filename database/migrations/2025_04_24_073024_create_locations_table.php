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
        Schema::create('location', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('location_name', 45); // varchar(45)
            $table->string('address', 255); // varchar(255)
            $table->unsignedBigInteger('province_id'); // int UN
            $table->unsignedBigInteger('city_id'); // int UN
            $table->unsignedBigInteger('district_id'); // int UN
            $table->unsignedBigInteger('subdistrict_id'); // int UN
            $table->unsignedBigInteger('poscode_id'); // int UN
            $table->boolean('status'); // tinyint(1)
            $table->timestamps(); // created_at, updated_at
            $table->unsignedBigInteger('created_by'); // bigint UN
            $table->unsignedBigInteger('updated_by'); // bigint UN

            // Foreign key constraints
            $table->foreign('province_id')->references('id')->on('province');
            $table->foreign('city_id')->references('id')->on('city');
            $table->foreign('district_id')->references('id')->on('district');
            $table->foreign('subdistrict_id')->references('id')->on('subdistrict');
            $table->foreign('poscode_id')->references('id')->on('poscode');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location');
    }
};
