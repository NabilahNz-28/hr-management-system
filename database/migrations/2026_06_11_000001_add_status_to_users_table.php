<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom status (aktif/nonaktif) ke tabel users.
 *
 * Dipakai di halaman superadmin Data Karyawan untuk menandai
 * karyawan aktif vs nonaktif. Default 'aktif' agar data lama
 * langsung dianggap aktif.
 */
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('role');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
