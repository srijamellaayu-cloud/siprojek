<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Administrasi\Penawaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenawaran = Penawaran::count();
        $totalProyekDeal = Penawaran::where('status', 'Disetujui')->count();

        // Proyek selesai: status deal, punya task, dan semua task sudah Done.
        $proyekSelesai = Penawaran::where('status', 'Disetujui')
            ->whereHas('tasks')
            ->whereDoesntHave('tasks', function ($taskQuery) {
                $taskQuery->where('status', '!=', 'Done');
            })
            ->count();

        // Sisanya dari proyek deal dianggap proyek berjalan.
        $proyekBerjalan = max($totalProyekDeal - $proyekSelesai, 0);

        $penawaranQuery = Penawaran::where('status', '!=', 'Disetujui');

        $penawarans = $penawaranQuery
            ->latest('tanggal')
            ->paginate(4, ['*'], 'penawaran_page')
            ->withQueryString();

        $dealQuery = Penawaran::where('status', 'Disetujui');

        $deals = $dealQuery
            ->withCount([
                'tasks as total_tasks_count',
                'tasks as done_tasks_count' => function ($taskQuery) {
                    $taskQuery->where('status', 'Done');
                },
            ])
            ->with('tasks')
            ->latest('tanggal')
            ->paginate(4, ['*'], 'deal_page')
            ->withQueryString();

        $chartEnd = Carbon::now()->endOfMonth();
        $chartStart = Carbon::now()->subMonths(11)->startOfMonth();

        $months = [];
        for ($cursor = $chartStart->copy(); $cursor->lte($chartEnd); $cursor->addMonth()) {
            $key = $cursor->format('Y-m');
            $months[$key] = [
                'label' => $cursor->translatedFormat('M Y'),
                'penawaran' => 0,
                'deal' => 0,
            ];
        }

        $penawaranMonthly = Penawaran::query()
            ->whereBetween('tanggal', [$chartStart->toDateString(), $chartEnd->toDateString()])
            ->get(['tanggal', 'status']);

        foreach ($penawaranMonthly as $item) {
            $monthKey = $item->tanggal?->format('Y-m');
            if ($monthKey && isset($months[$monthKey])) {
                $months[$monthKey]['penawaran']++;
                if ($item->status === 'Disetujui') {
                    $months[$monthKey]['deal']++;
                }
            }
        }

        $chartLabels = array_values(array_map(fn($month) => $month['label'], $months));
        $chartPenawaran = array_values(array_map(fn($month) => $month['penawaran'], $months));
        $chartDeal = array_values(array_map(fn($month) => $month['deal'], $months));

        // Statistik Proyek per Mitra
        $partnerStats = Penawaran::selectRaw('mitra, count(*) as count')
            ->whereNotNull('mitra')
            ->where('mitra', '!=', '')
            ->groupBy('mitra')
            ->orderByDesc('count')
            ->get();

        $partnerLabels = $partnerStats->pluck('mitra')->toArray();
        $partnerCounts = $partnerStats->pluck('count')->toArray();

        return view('administrasi.dashboard', compact(
            'penawarans',
            'deals',
            'proyekSelesai',
            'proyekBerjalan',
            'totalPenawaran',
            'totalProyekDeal',
            'chartLabels',
            'chartPenawaran',
            'chartDeal',
            'partnerLabels',
            'partnerCounts'
        ));
    }
}
