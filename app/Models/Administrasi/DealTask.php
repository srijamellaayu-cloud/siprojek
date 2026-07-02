<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DealTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'penawaran_id',
        'nama_tugas',
        'anggota',
        'tanggal_tugas',
        'durasi',
        'deskripsi',
        'status',
        'bank_penagihan',
        'dokumen_invoice',
        'dokumen_faktur_pajak',
    ];

    protected static function booted()
    {
        static::saved(function ($task) {
            if (isset($GLOBALS['checking_invoice_task'])) {
                return;
            }
            $deal = $task->deal;
            if ($deal) {
                $GLOBALS['checking_invoice_task'] = true;
                try {
                    $deal->checkAndCreateInvoiceTask();
                } finally {
                    unset($GLOBALS['checking_invoice_task']);
                }
            }
        });
        static::deleted(function ($task) {
            if (isset($GLOBALS['checking_invoice_task'])) {
                return;
            }
            $deal = $task->deal;
            if ($deal) {
                $GLOBALS['checking_invoice_task'] = true;
                try {
                    $deal->checkAndCreateInvoiceTask();
                } finally {
                    unset($GLOBALS['checking_invoice_task']);
                }
            }
        });
    }

    protected $casts = [
        'tanggal_tugas' => 'date',
        'anggota' => 'array',
    ];

    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->tanggal_tugas) {
            return null;
        }

        $today = now('Asia/Jakarta')->startOfDay();

        // Compare only the date parts in the same timezone to avoid off-by-one
        // issues caused by timezone differences or time components.
        $taskDate = Carbon::createFromFormat('Y-m-d', $this->tanggal_tugas->format('Y-m-d'), 'Asia/Jakarta')
            ->startOfDay();

        return $today->diffInDays($taskDate, false);
    }

    public function getDeadlineLabelAttribute(): ?string
    {
        $daysLeft = $this->days_left;

        if ($daysLeft === null) {
            return null;
        }

        if ($daysLeft < 0) {
            return 'Terlambat ' . abs($daysLeft) . ' hari';
        }

        if ($daysLeft === 0) {
            return 'Hari ini';
        }

        if ($daysLeft === 1) {
            return 'H-1 hari';
        }

        return 'H-' . $daysLeft . ' hari';
    }

    public function deal()
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id');
    }
}
