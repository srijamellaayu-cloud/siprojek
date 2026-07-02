<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\Penawaran;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        // Query proyek deal (status = Disetujui) yang sudah sampai tahap invoice penagihan
        $query = Penawaran::where('status', 'Disetujui')
            ->whereHas('tasks', function ($taskQuery) {
                $taskQuery->where('nama_tugas', 'Invoice Penagihan');
            });

        if ($request->filled('search')) {
            $query->where('nama_proyek', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date)
                  ->whereDate('tanggal', '<=', $request->end_date);
        }

        $tagihans = $query->latest('id')->paginate(5)->withQueryString();

        return view('keuangan.tagihan.index', compact('tagihans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $deal = Penawaran::findOrFail($id);
        $task = $deal->tasks()->where('nama_tugas', 'Invoice Penagihan')->first();

        if (!$task) {
            return redirect()->back()->with('error', 'Tugas Invoice Penagihan tidak ditemukan.');
        }

        if ($task->status === 'Done') {
            return redirect()->back()->with('error', 'Status tagihan tidak dapat diubah lagi karena pembayaran sudah selesai.');
        }

        $request->validate([
            'status' => 'required|in:proses penagihan,sudah dibayarkan',
        ]);

        if ($request->status === 'proses penagihan') {
            $task->update(['status' => 'On Progress']);
        } elseif ($request->status === 'sudah dibayarkan') {
            $task->update(['status' => 'Done']);
        }

        return redirect()->back()->with('success', 'Status tagihan berhasil diperbarui.');
    }
}
