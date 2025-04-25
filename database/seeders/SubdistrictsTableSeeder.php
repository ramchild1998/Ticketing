<?php

namespace Database\Seeders;

use App\Models\Subdistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubdistrictsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/subdistrict.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File subdistrict.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            Subdistrict::create([
                'id' => $data['id'],
                'subdistrict_name' => $data['subdistrict_name'],
                'district_id' => $data['district_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}