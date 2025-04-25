<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class OfficesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/office.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File office.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            Office::create([
                'id' => $data['id'],
                'code_office' => $data['code_office'],
                'office_name' => $data['office_name'],
                'address' => $data['address'],
                'pic_name' => $data['pic_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => (bool) $data['status'],
                'vendor_id' => $data['vendor_id'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'district_id' => $data['district_id'],
                'subdistrict_id' => $data['subdistrict_id'],
                'poscode_id' => $data['poscode_id'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
                'created_by' => $data['created_by'],
                'updated_by' => $data['updated_by'],
            ]);
        }

        fclose($file);

        // Terapkan office_id ke users setelah offices diisi
        User::applyPendingOfficeIds();
    }
}