<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pastikan kita membuat user dummy jika belum ada
        if (User::count() === 0) {
            User::create([
                'name' => 'Karyawan Test',
                'email' => 'karyawan@test.com',
                'password' => bcrypt('password'),
                'role' => 'karyawan'
            ]);
            User::create([
                'name' => 'PIC Test',
                'email' => 'pic@test.com',
                'password' => bcrypt('password'),
                'role' => 'pic'
            ]);
            User::create([
                'name' => 'Superadmin Test',
                'email' => 'superadmin@test.com',
                'password' => bcrypt('password'),
                'role' => 'superadmin'
            ]);
        }

        $this->call([
            LeaveTypesTableSeeder::class,
            LeavesTableSeeder::class,
        ]);
    }
}
