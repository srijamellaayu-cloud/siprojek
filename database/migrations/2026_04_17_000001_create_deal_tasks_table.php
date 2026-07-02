<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deal_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penawaran_id')->constrained('penawaran')->cascadeOnDelete();
            $table->string('nama_tugas');
            $table->string('anggota')->nullable();
            $table->date('tanggal_tugas');
            $table->string('durasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('On Progress');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_tasks');
    }
};
