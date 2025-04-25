<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProvincesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/province.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File province.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            Province::create([
                'id' => $data['id'],
                'province_name' => $data['province_name'],
                'status' => (bool) $data['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}