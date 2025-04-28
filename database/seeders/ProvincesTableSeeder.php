<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProvincesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file JSON
        $jsonPath = storage_path('app/json/province.json');

        // Baca file JSON
        if (!file_exists($jsonPath)) {
            throw new \Exception('File province.json tidak ditemukan di storage/app/json');
        }

        // Baca dan decode file JSON
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData as $data) {
            Province::create([
                'id' => $data['id'],
                'province_name' => $data['province_name'],
                'status' => (bool) $data['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}