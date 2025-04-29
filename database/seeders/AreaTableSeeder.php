<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AreaTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/area.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File area.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            Area::create([
                'id' => $data['id'],
                'name_area' => $data['name_area'],
                'status' => (bool) $data['status'],
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
            ]);
        }

        fclose($file);
    }
}
