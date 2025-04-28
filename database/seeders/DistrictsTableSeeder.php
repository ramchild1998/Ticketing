<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DistrictsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file JSON
        $jsonPath = storage_path('app/json/district.json');

        // Baca file JSON
        if (!file_exists($jsonPath)) {
            throw new \Exception('File district.json tidak ditemukan di storage/app/json');
        }

        // Baca dan decode file JSON
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData as $data) {
            District::create([
                'id' => $data['id'],
                'district_name' => $data['district_name'],
                'city_id' => $data['city_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}