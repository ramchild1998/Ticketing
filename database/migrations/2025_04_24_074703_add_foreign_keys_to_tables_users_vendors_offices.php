<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan foreign key untuk users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('office_id')->references('id')->on('office');
        });

        // Tambahkan foreign key untuk vendors
        Schema::table('vendor', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });

        // Tambahkan foreign key untuk offices
        Schema::table('office', function (Blueprint $table) {
            $table->foreign('vendor_id')->references('id')->on('vendor');
            $table->foreign('province_id')->references('id')->on('province');
            $table->foreign('city_id')->references('id')->on('city');
            $table->foreign('district_id')->references('id')->on('district');
            $table->foreign('subdistrict_id')->references('id')->on('subdistrict');
            $table->foreign('poscode_id')->references('id')->on('poscode');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        // Hapus foreign key untuk users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
        });

        // Hapus foreign key untuk vendors
        Schema::table('vendor', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        // Hapus foreign key untuk offices
        Schema::table('office', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['subdistrict_id']);
            $table->dropForeign(['poscode_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
    }
};