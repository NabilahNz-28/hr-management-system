<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanOldAttendancePhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:clean-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus (auto-delete) foto absen yang sudah berusia lebih dari 1 tahun';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneYearAgo = Carbon::now()->subYear();
        $this->info("Mencari foto absensi sebelum: " . $oneYearAgo->toDateTimeString());

        // 1. Cari record di database yang lebih dari 1 tahun dan memiliki foto
        $oldAttendances = Attendance::where('attendance_time', '<', $oneYearAgo)
            ->whereNotNull('photo')
            ->where('photo', '!=', '')
            ->get();

        $deletedCount = 0;
        foreach ($oldAttendances as $attendance) {
            $paths = [
                public_path('photos/' . $attendance->photo),
                storage_path('app/public/absensi/' . $attendance->photo),
                storage_path('absensi/' . $attendance->photo),
            ];

            foreach ($paths as $path) {
                if (File::exists($path) && File::isFile($path)) {
                    File::delete($path);
                }
            }

            // Kosongkan nama foto di record absensi agar hemat storage
            $attendance->photo = null;
            $attendance->save();
            $deletedCount++;
        }

        // 2. Pembersihan file yatim (orphan files) di folder foto/absensi yang usianya > 1 tahun
        $directories = [
            public_path('photos'),
            storage_path('app/public/absensi'),
            storage_path('absensi'),
        ];

        $orphanCount = 0;
        foreach ($directories as $dir) {
            if (File::isDirectory($dir)) {
                $files = File::files($dir);
                foreach ($files as $file) {
                    if ($file->getMTime() < $oneYearAgo->getTimestamp()) {
                        File::delete($file->getPathname());
                        $orphanCount++;
                    }
                }
            }
        }

        $this->info("Berhasil membersihkan {$deletedCount} foto absensi dari database dan {$orphanCount} file lama dari storage.");
        return 0;
    }
}
