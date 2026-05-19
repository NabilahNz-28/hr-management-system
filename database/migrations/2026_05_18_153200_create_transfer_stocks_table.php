<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('inventories')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('ke_gudang');
            $table->integer('jumlah');
            $table->enum('satuan', ['pcs', 'carton', 'box', 'pack'])->default('pcs');
            $table->string('status')->default('Selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_stocks');
    }
};