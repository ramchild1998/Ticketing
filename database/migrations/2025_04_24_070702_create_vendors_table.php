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
        Schema::create('vendor', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('vendor_name', 45); // varchar(45)
            $table->string('pic_name', 45); // varchar(45)
            $table->string('email', 45); // varchar(45)
            $table->string('phone', 20); // varchar(20)
            $table->boolean('status'); // tinyint(1)
            $table->timestamps(); // created_at, updated_at
            $table->unsignedBigInteger('created_by'); // bigint UN
            $table->unsignedBigInteger('updated_by'); // bigint UN

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor');
    }
};
