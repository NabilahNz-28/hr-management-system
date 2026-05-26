<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeavesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data users terlebih dahulu
        $users = DB::table('users')->where('role', 'karyawan')->get();

        if ($users->isEmpty()) {
            $this->command->info('Tidak ada data users dengan role karyawan.');
            return;
        }

        $leaves = [];

        foreach ($users as $index => $user) {
            // Setiap user punya 1-3 pengajuan cuti/izin
            $numLeaves = rand(1, 3);
            
            for ($i = 0; $i < $numLeaves; $i++) {
                $startDate = Carbon::now()->addDays(rand(5, 30));
                
                $jenis = ['izin', 'cuti'];
                $selectedJenis = $jenis[array_rand($jenis)];

                if ($selectedJenis === 'cuti') {
                    $totalDays = rand(2, 5);
                    $endDate = $startDate->copy()->addDays($totalDays - 1)->format('Y-m-d');
                    $jenisDetailOptions = ['tahunan', 'melahirkan', 'besar', 'sakit', 'penting'];
                } else {
                    $endDate = null; // izin biasanya 1 hari
                    $jenisDetailOptions = ['sakit', 'urusan_keluarga', 'urusan_pribadi', 'lainnya'];
                }

                $jenisDetail = $jenisDetailOptions[array_rand($jenisDetailOptions)];
                
                $statuses = ['pending', 'approved', 'rejected'];
                $status = $statuses[array_rand($statuses)];
                
                $leaves[] = [
                    'karyawan_id' => $user->id,
                    'jenis' => $selectedJenis,
                    'jenis_detail' => $jenisDetail,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate,
                    'keterangan' => $this->getReasonByType($jenisDetail),
                    'file_path' => null,
                    'status' => $status,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 10)),
                ];
            }
        }

        DB::table('leaves')->insert($leaves);
        
        $this->command->info('Seeder leaves berhasil dijalankan: ' . count($leaves) . ' data.');
    }

    private function getReasonByType($type): string
    {
        $reasons = [
            'tahunan' => 'Mengambil cuti tahunan untuk refreshing',
            'sakit' => 'Sedang tidak enak badan, perlu istirahat',
            'melahirkan' => 'Cuti melahirkan anak',
            'besar' => 'Cuti besar untuk urusan keluarga',
            'penting' => 'Ada keperluan penting yang mendesak',
            'urusan_keluarga' => 'Ada acara keluarga yang tidak bisa ditinggalkan',
            'urusan_pribadi' => 'Ada urusan administrasi pribadi',
            'lainnya' => 'Keperluan lainnya'
        ];
        
        return $reasons[$type] ?? 'Cuti/Izin untuk keperluan pribadi';
    }
}
