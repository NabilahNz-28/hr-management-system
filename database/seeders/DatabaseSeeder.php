<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            // 2 Superadmin
            for ($i = 1; $i <= 2; $i++) {
                User::create([
                    'name' => "Superadmin {$i}",
                    'email' => "superadmin{$i}@test.com",
                    'password' => Hash::make('password123'),
                    'role' => 'superadmin'
                ]);
            }

            // 1 PIC
            User::create([
                'name' => "PIC 1",
                'email' => "pic1@test.com",
                'password' => Hash::make('password123'),
                'role' => 'pic' // Di aplikasi mungkin lowercase
            ]);

            // 10 Karyawan
            for ($i = 1; $i <= 10; $i++) {
                User::create([
                    'name' => "Karyawan {$i}",
                    'email' => "karyawan{$i}@test.com",
                    'password' => Hash::make('password123'),
                    'role' => 'karyawan'
                ]);
            }
        }

        $this->call([
            LeaveTypesTableSeeder::class,
            LeavesTableSeeder::class,
        ]);
    }
}
