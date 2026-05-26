<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel leave_status_history — riwayat perubahan status izin/cuti (future feature).
 *
 * PERBAIKAN:
 *   - leave_id      : integer → unsignedBigInteger (match leaves.id bigIncrements)
 *   - permission_id : integer → unsignedBigInteger (match permissions.id bigIncrements)
 *   - admin_id      : integer tanpa FK → foreignId() dengan FK ke users
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('leave_status_history', function (Blueprint $table) {
            $table->id();                                       // bigIncrements unsigned

            $table->unsignedBigInteger('leave_id')
                  ->nullable()
                  ->index('idx_leave_id');

            $table->unsignedBigInteger('permission_id')
                  ->nullable()
                  ->index('idx_permission_id');

            $table->enum('old_status', ['pending', 'approved', 'rejected', 'cancelled'])
                  ->nullable();
            $table->enum('new_status', ['pending', 'approved', 'rejected', 'cancelled'])
                  ->nullable();

            // Admin yang melakukan perubahan (FK ke users)
            $table->unsignedBigInteger('admin_id')->index('idx_admin_id');

            $table->text('change_reason')->nullable();
            $table->timestamp('changed_at')->useCurrent();

            // FK constraints
            $table->foreign('leave_id', 'fk_status_history_leaves')
                  ->references('id')->on('leaves')->onDelete('cascade');

            $table->foreign('permission_id', 'fk_status_history_permissions')
                  ->references('id')->on('permissions')->onDelete('cascade');

            $table->foreign('admin_id', 'fk_status_history_admin')
                  ->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('leave_status_history');
    }
};
