<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom face_descriptor ke tabel users.
     * Menyimpan 128-dimensi face descriptor (JSON array float)
     * hasil ekstraksi face-api.js untuk pengenalan wajah saat absensi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('face_descriptor')->nullable()->after('foto_profile')
                ->comment('128-dim face descriptor JSON array dari face-api.js');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('face_descriptor');
        });
    }
};
