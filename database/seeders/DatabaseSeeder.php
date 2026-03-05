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
        $users = DB::table('users')->where('name', '!=', 'Choco Malingga S.Kom')->get();

        if ($users->isEmpty()) {
            $this->command->info('Tidak ada data users. Jalankan UsersTableSeeder terlebih dahulu.');
            return;
        }

        $leaves = [];

        foreach ($users as $index => $user) {
            // Setiap user punya 1-3 pengajuan cuti
            $numLeaves = rand(1, 3);
            
            for ($i = 0; $i < $numLeaves; $i++) {
                $startDate = Carbon::now()->addDays(rand(5, 30));
                $totalDays = rand(1, 5);
                $endDate = $startDate->copy()->addDays($totalDays - 1);
                
                $statuses = ['pending', 'approved', 'rejected'];
                $status = $statuses[array_rand($statuses)];
                
                $leaveTypes = ['tahunan', 'sakit', 'melahirkan', 'besar', 'penting'];
                $leaveType = $leaveTypes[array_rand($leaveTypes)];
                
                $leaves[] = [
                    'employee_id' => $user->id,
                    'employee_name' => $user->name,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'leave_type' => $leaveType,
                    'reason' => $this->getReasonByType($leaveType),
                    'status' => $status,
                    'pic_name' => 'Choco Malingga S.Kom',
                    'approved_at' => $status === 'approved' ? Carbon::now()->subDays(rand(1, 10)) : null,
                    'rejection_reason' => $status === 'rejected' ? 'Kuota cuti tidak mencukupi' : null,
                    'total_days' => $totalDays,
                    'document_path' => null,
                    'document_type' => null,
                    'document_size' => 0,
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
            'penting' => 'Ada keperluan penting yang mendesak'
        ];
        
        return $reasons[$type] ?? 'Cuti untuk keperluan pribadi';
    }
}