<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $table = 'deal';

    protected $fillable = [
        'nama_proyek',
        'tanggal',
        'mitra',
        'biaya_penawaran',
        'durasi_proyek',
        'deskripsi',
        'dokumen',
    ];
}
