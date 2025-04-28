<?php

namespace Database\Seeders;

use App\Models\Subdistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SubdistrictsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file JSON
        $jsonPath = storage_path('app/json/subdistrict.json');

        // Baca file JSON
        if (!file_exists($jsonPath)) {
            throw new \Exception('File subdistrict.json tidak ditemukan di storage/app/json');
        }

        // Baca dan decode file JSON
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData as $data) {
            Subdistrict::create([
                'id' => $data['id'],
                'subdistrict_name' => $data['subdistrict_name'],
                'district_id' => $data['district_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}