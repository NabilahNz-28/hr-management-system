<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Kategori Cuti
            [
                'type_code' => 'tahunan',
                'name' => 'Cuti Tahunan',
                'name_en' => 'Annual Leave',
                'description' => 'Cuti rutin tahunan karyawan',
                'max_days' => 12,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'type_code' => 'melahirkan',
                'name' => 'Cuti Melahirkan',
                'name_en' => 'Maternity Leave',
                'description' => 'Cuti untuk persalinan',
                'max_days' => 90,
                'requires_document' => true,
                'is_active' => true,
            ],
            [
                'type_code' => 'besar',
                'name' => 'Cuti Besar',
                'name_en' => 'Sabbatical Leave',
                'description' => 'Cuti besar (loyalitas)',
                'max_days' => 30,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'type_code' => 'cuti_sakit',
                'name' => 'Cuti Sakit',
                'name_en' => 'Sick Leave Extended',
                'description' => 'Cuti sakit berkepanjangan',
                'max_days' => 14,
                'requires_document' => true,
                'is_active' => true,
            ],
            [
                'type_code' => 'cuti_penting',
                'name' => 'Cuti Alasan Penting',
                'name_en' => 'Important Reason Leave',
                'description' => 'Cuti untuk alasan yang sangat penting',
                'max_days' => 5,
                'requires_document' => true,
                'is_active' => true,
            ],
            // Kategori Izin
            [
                'type_code' => 'sakit',
                'name' => 'Izin Sakit',
                'name_en' => 'Sick Leave',
                'description' => 'Izin karena sakit (surat dokter jika > 1 hari)',
                'max_days' => 0,
                'requires_document' => true,
                'is_active' => true,
            ],
            [
                'type_code' => 'penting',
                'name' => 'Izin Penting',
                'name_en' => 'Important Leave',
                'description' => 'Izin untuk keperluan mendesak',
                'max_days' => 0,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'type_code' => 'urusan_keluarga',
                'name' => 'Urusan Keluarga',
                'name_en' => 'Family Matter',
                'description' => 'Izin karena ada urusan keluarga',
                'max_days' => 0,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'type_code' => 'urusan_pribadi',
                'name' => 'Urusan Pribadi',
                'name_en' => 'Personal Matter',
                'description' => 'Izin karena urusan administrasi pribadi',
                'max_days' => 0,
                'requires_document' => false,
                'is_active' => true,
            ],
            [
                'type_code' => 'lainnya',
                'name' => 'Lainnya',
                'name_en' => 'Others',
                'description' => 'Izin dengan alasan lain',
                'max_days' => 0,
                'requires_document' => false,
                'is_active' => true,
            ],
        ];

        DB::table('leave_types')->truncate();
        DB::table('leave_types')->insert($types);
        
        $this->command->info('Seeder leave_types berhasil dijalankan.');
    }
}
