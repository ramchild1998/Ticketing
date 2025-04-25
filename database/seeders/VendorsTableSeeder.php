<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class VendorsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/vendor.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File vendor.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            Vendor::create([
                'id' => $data['id'],
                'vendor_name' => $data['vendor_name'],
                'pic_name' => $data['pic_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => (bool) $data['status'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
            ]);
        }

        fclose($file);
    }
}