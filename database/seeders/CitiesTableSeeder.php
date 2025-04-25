<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/city.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File city.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            City::create([
                'id' => $data['id'],
                'city_name' => $data['city_name'],
                'province_id' => $data['province_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}