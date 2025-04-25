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
        Schema::create('perlengkapan', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('nama_alat', 45); // varchar(45)
            $table->unsignedBigInteger('jumlah'); // bigint UN
            $table->string('satuan', 15); // varchar(15)
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
        Schema::dropIfExists('perlengkapan');
    }
};
