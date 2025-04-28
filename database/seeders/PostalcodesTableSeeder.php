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
        // Path ke file JSON
        $jsonPath = storage_path('app/json/poscode.json');

        // Baca file JSON
        if (!file_exists($jsonPath)) {
            throw new \Exception('File postalcode.json tidak ditemukan di storage/app/json');
        }

        // Baca dan decode file JSON
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        foreach ($jsonData as $data) {
            PostalCode::create([
                'id' => $data['id'],
                'poscode' => $data['poscode'],
                'subdistrict_id' => $data['subdistrict_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}