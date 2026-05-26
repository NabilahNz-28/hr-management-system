<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel leave_documents — lampiran untuk pengajuan cuti/izin.
 *
 * PERBAIKAN:
 *   - leave_id      : diubah dari integer() → unsignedBigInteger()
 *                     agar cocok dengan leaves.id (bigIncrements)
 *   - permission_id : diubah dari integer() → unsignedBigInteger()
 *                     agar cocok dengan permissions.id (autoIncrement integer)
 *                     CATATAN: permissions.id masih integer, tapi kita gunakan
 *                     unsignedBigInteger agar future-proof.
 */
return new class extends Migration {
    public function up(): void {
        Schema::create('leave_documents', function (Blueprint $table) {
            $table->id();                                             // bigIncrements

            // FK ke leaves — harus unsignedBigInteger agar match dengan leaves.id
            $table->unsignedBigInteger('leave_id')
                  ->nullable()
                  ->index('idx_leave_id');

            // FK ke permissions — unsignedBigInteger (future-proof)
            $table->unsignedBigInteger('permission_id')
                  ->nullable()
                  ->index('idx_permission_id');

            $table->string('document_name');
            $table->string('document_path');
            $table->string('document_type', 50)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            // FK constraints
            $table->foreign('leave_id', 'fk_leave_documents_leaves')
                  ->references('id')
                  ->on('leaves')
                  ->onDelete('cascade');

            $table->foreign('permission_id', 'fk_leave_documents_permissions')
                  ->references('id')
                  ->on('permissions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('leave_documents');
    }
};
