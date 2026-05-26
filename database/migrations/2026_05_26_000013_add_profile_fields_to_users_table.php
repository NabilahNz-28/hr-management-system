<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom profile karyawan ke tabel users.
 *
 * Sesuai blade absensi/pengaturan/profile.blade.php yang menampilkan:
 *   - nik           : Nomor Induk Karyawan
 *   - departemen    : Departemen karyawan
 *   - jabatan       : Jabatan / posisi
 *   - no_hp         : Nomor HP (format: 08xx / +62xx)
 *   - alamat        : Alamat lengkap
 *   - tgl_bergabung : Tanggal bergabung perusahaan
 *   - foto_profile  : Path foto profile (disimpan di storage/profiles/)
 *
 * Semua nullable agar backward compatible dengan data users yang sudah ada.
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 50)->nullable()->unique()->after('email');
            $table->string('departemen', 100)->nullable()->after('nik');
            $table->string('jabatan', 100)->nullable()->after('departemen');
            $table->string('no_hp', 20)->nullable()->after('jabatan');
            $table->text('alamat')->nullable()->after('no_hp');
            $table->date('tgl_bergabung')->nullable()->after('alamat');
            $table->string('foto_profile')->nullable()->after('tgl_bergabung');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'departemen',
                'jabatan',
                'no_hp',
                'alamat',
                'tgl_bergabung',
                'foto_profile',
            ]);
        });
    }
};
