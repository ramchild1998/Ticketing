<?php

namespace Database\Seeders;

use App\Models\Poscode;
use App\Models\PostalCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PostalcodesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/postalcode.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File postalcode.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            PostalCode::create([
                'id' => $data['id'],
                'poscode' => $data['poscode'],
                'subdistrict_id' => $data['subdistrict_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}