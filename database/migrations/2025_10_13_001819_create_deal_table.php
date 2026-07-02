<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek');
            $table->date('tanggal');
            $table->string('mitra');
            $table->integer('biaya_penawaran')->nullable();
            $table->string('durasi_proyek')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('dokumen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal');
    }
};
