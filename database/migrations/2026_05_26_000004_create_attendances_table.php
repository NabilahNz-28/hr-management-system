<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel absensi sesuai blade absen-masuk.blade.php & absen-pulang.blade.php.
 *
 * Data yang dikirim dari blade:
 *  - employee_name  : nama karyawan (dari session auth()->user()->name)
 *  - user_id        : FK ke users (auth()->id())
 *  - attendance_type: 'masuk' | 'pulang'
 *  - photo          : path file foto (disimpan di storage/absensi/)
 *  - latitude       : koordinat GPS
 *  - longitude      : koordinat GPS
 *  - address        : alamat hasil reverse geocode (nominatim)
 *  - attendance_time: waktu absensi (datetime)
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();                                          // bigIncrements

            // Relasi ke users
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Identitas karyawan (denormalized, agar laporan tetap bisa tampil
            // meski user dihapus — opsional tapi berguna)
            $table->string('employee_name');

            // Tipe absensi: masuk atau pulang
            $table->enum('attendance_type', ['masuk', 'pulang']);

            // Foto — disimpan sebagai path relatif di storage/absensi/
            $table->string('photo')->nullable();

            // Koordinat GPS dari blade (latitude & longitude)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Alamat dari reverse geocode (nominatim)
            $table->text('address')->nullable();

            // Waktu absensi yang dicatat
            $table->dateTime('attendance_time');

            // Index untuk query performa
            $table->index('user_id',          'idx_user_id');
            $table->index('attendance_type',  'idx_attendance_type');
            $table->index('attendance_time',  'idx_attendance_time');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};
