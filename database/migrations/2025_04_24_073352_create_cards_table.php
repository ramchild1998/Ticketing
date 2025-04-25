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
        Schema::create('card', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('card_no', 20); // varchar(20)
            $table->string('jenis_kartu', 45); // varchar(45)
            $table->string('co_brand', 10)->nullable(); // varchar(10)
            $table->string('brand_code', 10)->nullable(); // varchar(10)
            $table->unsignedBigInteger('kartu_baru'); // bigint UN
            $table->unsignedBigInteger('sisa_hopper'); // bigint UN
            $table->unsignedBigInteger('total'); // bigint UN
            $table->string('expired_dates', 7); // varchar(7)
            $table->string('keterangan', 45)->nullable(); // varchar(45)
            $table->boolean('status'); // tinyint(1)
            $table->timestamps(); // created_at, updated_at
            $table->unsignedBigInteger('created_by'); // bigint UN
            $table->unsignedBigInteger('updated_by'); // bigint UN

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card');
    }
};
