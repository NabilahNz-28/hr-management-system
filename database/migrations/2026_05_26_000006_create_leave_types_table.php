<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel leave_types — jenis-jenis izin/cuti (future feature).
 *
 * Nilai type_code yang perlu di-seed agar cocok dengan blade:
 *   Cuti : tahunan, melahirkan, besar, sakit, penting
 *   Izin : sakit, urusan_keluarga, urusan_pribadi, lainnya
 *
 * PERBAIKAN:
 *   - id: diubah dari integer autoIncrement → id() (bigIncrements unsigned)
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();                                       // bigIncrements unsigned

            $table->string('type_code', 50)->unique('type_code');
            $table->string('name', 100);
            $table->string('name_en', 100);
            $table->text('description')->nullable();
            $table->integer('max_days')->default(0);
            $table->boolean('requires_document')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('leave_types');
    }
};
