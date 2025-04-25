<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Path ke file CSV
        $csvPath = storage_path('app/csv/users.csv');

        // Baca file CSV
        if (!file_exists($csvPath)) {
            throw new \Exception('File users.csv tidak ditemukan di storage/app/csv');
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Baca header CSV

        // Simpan data office_id untuk pembaruan nanti
        $userOfficeIds = [];

        while (($row = fgetcsv($file)) !== false) {
            // Map data CSV ke kolom model
            $data = array_combine($header, $row);

            // Simpan office_id untuk pembaruan
            $userOfficeIds[$data['id']] = $data['office_id'] ? (int) $data['office_id'] : null;

            // Insert tanpa office_id
            User::create([
                'id' => $data['id'],
                'nip' => $data['nip'],
                'name' => $data['name'],
                'email' => $data['email'],
                'office_id' => null, // Set null sementara
                'email_verified_at' => $data['email_verified_at'] ?: null,
                'password' => $data['password'],
                'phone' => $data['phone'],
                'remember_token' => $data['remember_token'] ?: null,
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        }

        fclose($file);

        // Simpan office_id ke static property untuk digunakan di OfficesTableSeeder
        User::setPendingOfficeIds($userOfficeIds);
    }
}