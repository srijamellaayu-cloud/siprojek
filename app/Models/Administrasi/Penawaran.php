<?php

namespace App\Models\Administrasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    use HasFactory;

    protected $table = 'penawaran';

    protected $fillable = [
        'nama_proyek',
        'tanggal',
        'tanggal_mulai',
        'tanggal_selesai',
        'nomor_surat',
        'nomor_kontrak',
        'mitra',
        'status',
        'biaya_penawaran',
        'durasi_proyek',
        'dokumen',
        'dokumen_kontrak',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function tasks()
    {
        return $this->hasMany(DealTask::class, 'penawaran_id');
    }

    public function getNomorSuratAttribute($value)
    {
        return $value;
    }

    public function calculateProgress()
    {
        $tasks = $this->relationLoaded('tasks') ? $this->tasks : $this->tasks()->get();
        
        $normalTasks = $tasks->where('nama_tugas', '!=', 'Invoice Penagihan');
        $invoiceTask = $tasks->where('nama_tugas', 'Invoice Penagihan')->first();

        $totalNormal = $normalTasks->count();

        if ($totalNormal === 0) {
            return [
                'base' => 0,
                'penalty' => 0,
                'final' => 0,
            ];
        }

        $doneNormal = $normalTasks->where('status', 'Done')->count();
        $baseProgress = ($doneNormal / $totalNormal) * 90;

        $weight = 90 / $totalNormal;
        $totalPenalty = 0;

        $today = now('Asia/Jakarta')->startOfDay();

        foreach ($normalTasks as $task) {
            if ($task->status !== 'Done' && $task->tanggal_tugas) {
                $taskDate = \Carbon\Carbon::createFromFormat('Y-m-d', $task->tanggal_tugas->format('Y-m-d'), 'Asia/Jakarta')
                    ->startOfDay();
                
                $daysLeft = $today->diffInDays($taskDate, false);
                if ($daysLeft < 0) {
                    $daysLate = abs($daysLeft);
                    $penaltyPercent = min($daysLate * 0.02, 0.50);
                    $totalPenalty += $weight * $penaltyPercent;
                }
            }
        }

        $finalProgress = max(0, $baseProgress - $totalPenalty);

        if ($invoiceTask) {
            if ($invoiceTask->dokumen_invoice) {
                $finalProgress += 5;
            }
            if ($invoiceTask->status === 'Done') {
                $finalProgress += 5;
            }
        }

        return [
            'base' => round($baseProgress, 2),
            'penalty' => round($totalPenalty, 2),
            'final' => (int) round($finalProgress),
        ];
    }

    public function checkAndCreateInvoiceTask()
    {
        $normalTasks = $this->tasks()->where('nama_tugas', '!=', 'Invoice Penagihan')->get();
        $totalNormal = $normalTasks->count();

        if ($totalNormal > 0) {
            $doneNormal = $normalTasks->where('status', 'Done')->count();

            if ($doneNormal === $totalNormal) {
                $invoiceTaskExists = $this->tasks()->where('nama_tugas', 'Invoice Penagihan')->exists();
                if (!$invoiceTaskExists) {
                    $this->tasks()->create([
                        'nama_tugas' => 'Invoice Penagihan',
                        'anggota' => null,
                        'tanggal_tugas' => now()->toDateString(),
                        'durasi' => null,
                        'deskripsi' => null,
                        'status' => 'On Progress',
                    ]);
                }
            } else {
                $this->tasks()->where('nama_tugas', 'Invoice Penagihan')->delete();
            }
        }
    }

    public function getProgressAttribute()
    {
        return $this->calculateProgress()['final'];
    }

    public function getProgressDetailsAttribute()
    {
        return $this->calculateProgress();
    }
}
