<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel leaves — sesuai blade:
 *
 * pengajuan-cuti.blade.php mengirim:
 *   - tanggal_mulai (start_date)
 *   - tanggal_selesai (end_date) — nullable untuk izin 1 hari
 *   - jenis_cuti: 'tahunan'|'melahirkan'|'besar'|'sakit'|'penting'
 *   - alasan (keterangan)
 *   - file upload (file_path)
 *
 * pengajuan-izin.blade.php mengirim:
 *   - tanggal_izin (start_date)
 *   - jenis_izin: 'sakit'|'urusan_keluarga'|'urusan_pribadi'|'lainnya'
 *   - alasan (keterangan)
 *   - file upload opsional (file_path)
 *
 * approval-izincuti.blade.php membaca:
 *   - $data->karyawan->name   (relasi ke users via karyawan_id)
 *   - $data->jenis            ('izin'|'cuti')
 *   - $data->jenis_detail     (detail jenis: tahunan, sakit, dll.)
 *   - $data->keterangan
 *   - $data->file_path
 *   - $data->status           ('pending'|'approved'|'rejected')
 *   - $data->created_at
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();                                             // bigIncrements

            // FK ke users (karyawan yang mengajukan)
            $table->foreignId('karyawan_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Tipe utama: izin (1 hari) atau cuti (beberapa hari)
            $table->enum('jenis', ['izin', 'cuti']);

            // Detail jenis — dari dropdown blade:
            //   Cuti  : tahunan, melahirkan, besar, sakit, penting
            //   Izin  : sakit, urusan_keluarga, urusan_pribadi, lainnya
            $table->string('jenis_detail', 50)->nullable();

            // Tanggal mulai (untuk izin = tanggal izin, untuk cuti = start)
            $table->date('start_date');

            // Tanggal selesai — nullable (izin 1 hari tidak perlu end_date)
            $table->date('end_date')->nullable();

            // Alasan / keterangan dari form
            $table->text('keterangan');

            // Path file lampiran (surat dokter, dll.) disimpan di storage/leaves/
            $table->string('file_path', 500)->nullable();

            // Status persetujuan
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('leaves');
    }
};
