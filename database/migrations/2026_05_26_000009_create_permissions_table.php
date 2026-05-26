<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel permissions — pengajuan izin versi enterprise (future feature).
 *
 * PERBAIKAN:
 *   - id: diubah dari integer autoIncrement → id() (bigIncrements unsigned)
 *         agar konsisten dan bisa di-FK dari leave_documents & leave_status_history
 *   - employee_id & leave_type_id: diubah ke unsignedBigInteger agar
 *     future-proof saat FK di-enable
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();                                       // bigIncrements unsigned

            $table->string('permission_number', 50)->unique('permission_number');

            // FK ke users (employee) — unsignedBigInteger agar match users.id
            $table->unsignedBigInteger('employee_id')->index('idx_employee_id');

            // FK ke leave_types — unsignedBigInteger agar match leave_types.id
            $table->unsignedBigInteger('leave_type_id')->index('idx_leave_type_id');

            $table->date('permission_date');
            $table->text('reason');
            $table->string('document_path')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])
                  ->default('pending')
                  ->index('idx_status');

            $table->dateTime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('permissions');
    }
};
