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
        Schema::create('province', function (Blueprint $table) {
            $table->id(); // int UN AI PK
            $table->string('province_name', 255); // varchar(255)
            $table->boolean('status'); // tinyint(1)
            $table->timestamps(); // timestamp
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('province');
    }
};
