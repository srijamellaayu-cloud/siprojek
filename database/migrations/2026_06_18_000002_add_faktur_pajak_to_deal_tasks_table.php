<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_tasks', function (Blueprint $table) {
            $table->string('dokumen_faktur_pajak')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('deal_tasks', function (Blueprint $table) {
            $table->dropColumn('dokumen_faktur_pajak');
        });
    }
};
