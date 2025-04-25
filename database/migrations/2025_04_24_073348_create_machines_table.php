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
        Schema::create('machine', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('machine_code', 6); // varchar(6)
            $table->unsignedBigInteger('location_id'); // int UN
            $table->boolean('status'); // tinyint(1)
            $table->timestamps(); // created_at, updated_at
            $table->unsignedBigInteger('created_by'); // bigint UN
            $table->unsignedBigInteger('updated_by'); // bigint UN

            // Foreign key constraints
            $table->foreign('location_id')->references('id')->on('location');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine');
    }
};
