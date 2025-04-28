<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file JSON
        $jsonPath = storage_path('app/json/city.json');

        // Baca file JSON
        if (!file_exists($jsonPath)) {
            throw new \Exception('File city.json tidak ditemukan di storage/app/json');
        }

        // Baca dan decode file JSON
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData as $data) {
            City::create([
                'id' => $data['id'],
                'city_name' => $data['city_name'],
                'province_id' => $data['province_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}