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
        Schema::create('office', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('code_office', 10); // varchar(10)
            $table->string('office_name', 45); // varchar(45)
            $table->string('address', 255); // varchar(255)
            $table->string('pic_name', 45); // varchar(45)
            $table->string('email', 45); // varchar(45)
            $table->string('phone', 20); // varchar(20)
            $table->boolean('status'); // tinyint(1)
            $table->unsignedBigInteger('vendor_id'); // int UN
            $table->unsignedBigInteger('province_id'); // int UN
            $table->unsignedBigInteger('city_id'); // int UN
            $table->unsignedBigInteger('district_id'); // int UN
            $table->unsignedBigInteger('subdistrict_id'); // int UN
            $table->unsignedBigInteger('poscode_id'); // int UN
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
        Schema::dropIfExists('office');
    }
};
