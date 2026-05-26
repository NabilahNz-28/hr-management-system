<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel employee_leave_quotas — kuota cuti per karyawan per tahun (future feature).
 *
 * PERBAIKAN:
 *   - id: diubah dari integer autoIncrement → id() (bigIncrements unsigned)
 *   - employee_id: diubah ke unsignedBigInteger agar match users.id
 *   - leave_type_id: diubah ke unsignedBigInteger agar match leave_types.id
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('employee_leave_quotas', function (Blueprint $table) {
            $table->id();                                       // bigIncrements unsigned

            // FK ke users
            $table->unsignedBigInteger('employee_id')->index('idx_employee_id');

            // FK ke leave_types
            $table->unsignedBigInteger('leave_type_id')->index('idx_leave_type_id');

            $table->year('year')->index('idx_year');
            $table->integer('total_quota')->default(0);
            $table->integer('used_quota')->default(0);
            $table->integer('remaining_quota')->storedAs('total_quota - used_quota');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['employee_id', 'leave_type_id', 'year'], 'unique_employee_leave_year');
        });
    }

    public function down(): void {
        Schema::dropIfExists('employee_leave_quotas');
    }
};
