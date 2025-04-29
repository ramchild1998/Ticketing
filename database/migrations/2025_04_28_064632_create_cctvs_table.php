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
        Schema::create('cctv', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('area_id');
            $table->string('status_cctv', 100);
            $table->string('merk_dvr', 40);
            $table->string('sn_dvr', 40);
            $table->string('kunci_ruangan', 40);
            $table->string('sampling_dvr', 40);
            $table->timestamp('tanggal_pengerjaan');
            $table->string('note', 100);
            $table->string('pkt_atm', 40);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->foreign('machine_id')->references('id')->on('machine');
            $table->foreign('location_id')->references('id')->on('location');
            $table->foreign('area_id')->references('id')->on('area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cctv');
    }
};
