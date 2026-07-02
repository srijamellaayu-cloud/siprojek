@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Header dengan profil 
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Dashboard Proyek</h2>
        <div class="d-flex align-items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" 
                 alt="profile" class="rounded-circle me-2" width="40" height="40">
            <span>{{ Auth::user()->name }}</span>
        </div>
    </div> -->

    <!-- Row Statistik -->
    <div class="row mb-3 dashboard-stats-row">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm dashboard-stat-card">
                <div class="card-body dashboard-stat-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 dashboard-stat-label">Proyek Selesai</p>
                        <h3 class="mb-0 dashboard-stat-value">{{ $proyekSelesai }}</h3>
                    </div>
                    <i class="far fa-clipboard dashboard-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm dashboard-stat-card">
                <div class="card-body dashboard-stat-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 dashboard-stat-label">Proyek Berjalan</p>
                        <h3 class="mb-0 dashboard-stat-value">{{ $proyekBerjalan }}</h3>
                    </div>
                    <i class="fas fa-drafting-compass dashboard-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm dashboard-stat-card">
                <div class="card-body dashboard-stat-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 dashboard-stat-label">Total Penawaran</p>
                        <h3 class="mb-0 dashboard-stat-value">{{ $totalPenawaran }}</h3>
                    </div>
                    <i class="fas fa-tags dashboard-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm dashboard-stat-card">
                <div class="card-body dashboard-stat-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 dashboard-stat-label">Total Proyek Deal</p>
                        <h3 class="mb-0 dashboard-stat-value">{{ $totalProyekDeal }}</h3>
                    </div>
                    <i class="fas fa-handshake dashboard-stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="row mb-3 dashboard-summary-row">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <div class="card shadow-sm dashboard-summary-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="dashboard-summary-title">TOTAL PROYEK</small>
                        <small class="dashboard-summary-filter">This Week <i class="fa fa-chevron-down ms-1"></i></small>
                    </div>
                    <h3 class="dashboard-summary-value">9 Proyek</h3>
                    <p class="dashboard-summary-sub">Status Proyek</p>
                    <div class="dashboard-donut-area mt-2">
                        <div class="dashboard-donut-chart"></div>
                        <ul class="dashboard-legend list-unstyled mb-0">
                            <li><span class="dot dot-blue"></span>Selesai</li>
                            <li><span class="dot dot-red"></span>Penawaran</li>
                            <li><span class="dot dot-green"></span>Deal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>-->

    <!--<div class="col-12 col-md-6">
            <div class="card shadow-sm dashboard-summary-card h-100">
                <div class="card-body">
                    <small class="dashboard-summary-title">PROYEK DEAL PER 6 BULAN</small>
                    <h3 class="dashboard-summary-value">9 Proyek</h3>
                    <p class="dashboard-summary-sub">Periode Jan - Jun 2025</p>
                    <div class="dashboard-line-wrap mt-2">
                        <svg viewBox="0 0 420 180" class="dashboard-line-svg" aria-hidden="true">
                            <polyline points="20,130 85,115 150,105 215,98 280,93 345,88 400,80" class="line"></polyline>
                            <g class="points">
                                <circle cx="20" cy="130" r="3"></circle>
                                <circle cx="85" cy="115" r="3"></circle>
                                <circle cx="150" cy="105" r="3"></circle>
                                <circle cx="215" cy="98" r="3"></circle>
                                <circle cx="280" cy="93" r="3"></circle>
                                <circle cx="345" cy="88" r="3"></circle>
                                <circle cx="400" cy="80" r="3"></circle>
                            </g>
                        </svg>
                        <small class="dashboard-line-legend"><span class="dot dot-green"></span>Deal</small>
                    </div>
                </div>
            </div>
        </div>-->

<div class="row mb-4">
    <!-- Chart 1: Proyek Penawaran & Deal per Bulan -->
    <div class="col-12 col-lg-4 mb-4 mb-lg-0">
        <div class="card shadow-sm dashboard-panel-card dashboard-chart-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center dashboard-panel-header">
                <h5 class="mb-0 dashboard-panel-title">Proyek Penawaran &<br>Deal per Bulan</h5>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-sm btn-light chart-nav-btn py-0 px-2" id="prevMonthsBtn" title="Geser ke Kiri">
                        <i class="fas fa-chevron-left" style="font-size: 0.75rem;"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light chart-nav-btn py-0 px-2" id="nextMonthsBtn" title="Geser ke Kanan">
                        <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
            </div>
            <div class="card-body dashboard-chart-body">
                <div class="dashboard-chart-wrap">
                    <canvas id="monthlyProjectsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Status Proyek Deal -->
    <div class="col-12 col-lg-3 mb-4 mb-lg-0">
        <div class="card shadow-sm dashboard-panel-card dashboard-chart-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center dashboard-panel-header">
                <h5 class="mb-0 dashboard-panel-title">Status Proyek Deal</h5>
            </div>
            <div class="card-body dashboard-chart-body d-flex flex-column align-items-center justify-content-center">
                <div class="dashboard-chart-wrap" style="display: flex; align-items: center; justify-content: center; width: 100%; min-height: 160px; height: 160px;">
                    <div style="width: 150px; height: 150px; position: relative;">
                        <canvas id="dealStatusDoughnutChart"></canvas>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-2">
                    <small class="d-flex align-items-center" style="font-weight: 600; color: #2f4057;">
                        <span style="background-color: #22b573; width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px;"></span> Selesai
                    </small>
                    <small class="d-flex align-items-center" style="font-weight: 600; color: #2f4057;">
                        <span style="background-color: #f0ad17; width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px;"></span> On Progress
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 3: Proyek per Mitra -->
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm dashboard-panel-card dashboard-chart-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center dashboard-panel-header">
                <h5 class="mb-0 dashboard-panel-title">Proyek per Mitra</h5>
            </div>
            <div class="card-body dashboard-chart-body">
                <div class="dashboard-chart-wrap" style="max-height: 220px; overflow-y: auto; overflow-x: hidden; position: relative;">
                    <div id="partnerProjectsChartContainer" style="width: 100%; position: relative;">
                        <canvas id="partnerProjectsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Proyek Penawaran -->
    <div class="col-12 col-md-6 mb-4">
        <div class="card shadow-sm h-100 dashboard-panel-card">
            <div class="card-header d-flex justify-content-between align-items-center dashboard-panel-header">
                <h5 class="mb-0 dashboard-panel-title">Proyek Penawaran</h5>
            </div>
            <div class="card-body p-0 dashboard-table-wrap">
                <table class="table table-hover mb-0 dashboard-table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Proyek</th>
                            <th class="text-center">Status</th>
                            <th>Mitra</th>
                            <th class="text-center">Kelola</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penawarans as $item)
                        <tr>
                            <td>
                                <span class="dashboard-project-name" title="{{ $item->nama_proyek }}">{{ \Illuminate\Support\Str::words($item->nama_proyek, 3, '...') }}</span><br>
                                <small class="text-muted dashboard-project-date">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}</small>
                            </td>
                            <td class="text-center">
                                @php
                                $statusClass = match ($item->status) {
                                'Menunggu Persetujuan' => 'is-waiting',
                                'Disetujui' => 'is-approved',
                                'Ditolak' => 'is-rejected',
                                default => 'is-neutral',
                                };
                                @endphp
                                <span class="penawaran-status-badge {{ $statusClass }}">
                                    @if($item->status === 'Menunggu Persetujuan')
                                    Menunggu<br>Persetujuan
                                    @else
                                    {{ $item->status }}
                                    @endif
                                </span>
                            </td>
                            <td>{{ $item->mitra }}</td>
                            <td class="text-center">
                                <a href="{{ route('eksekutif.penawaran.show', $item->id) }}" class="btn btn-sm btn-primary dashboard-action-btn dashboard-action-btn-primary">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data penawaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer dashboard-panel-footer">
                <nav aria-label="Penawaran pagination">
                    <ul class="pagination justify-content-center mb-0 dashboard-pagination">
                        @if ($penawarans->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo;</span></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ $penawarans->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        @foreach ($penawarans->getUrlRange(1, $penawarans->lastPage()) as $page => $url)
                        @if ($page == $penawarans->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                        @endforeach

                        @if ($penawarans->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $penawarans->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Proyek Deal -->
    <div class="col-12 col-md-6 mb-4">
        <div class="card shadow-sm h-100 dashboard-panel-card">
            <div class="card-header d-flex justify-content-between align-items-center dashboard-panel-header">
                <h5 class="mb-0 dashboard-panel-title">Proyek Deal</h5>
            </div>
            <div class="card-body p-0 dashboard-table-wrap">
                <table class="table table-hover mb-0 dashboard-table dashboard-table-deal align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Proyek</th>
                            <th class="text-center">Status</th>
                            <th>Mitra</th>
                            <th class="text-center">Kelola</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deals as $item)
                        <tr>
                            <td>
                                <span class="dashboard-project-name" title="{{ $item->nama_proyek }}">{{ \Illuminate\Support\Str::words($item->nama_proyek, 3, '...') }}</span><br>
                                <small class="text-muted dashboard-project-date">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}</small>
                            </td>
                            <td class="text-center">
                                @php
                                $progress = (int) $item->progress;
                                $progressClass = $progress === 0 ? 'is-zero' : ($progress >= 100 ? 'is-complete' : 'is-active');
                                @endphp
                                <div class="dashboard-progress {{ $progressClass }}" aria-label="Progress {{ $progress }} persen">
                                    <div class="dashboard-progress-head">
                                        <span class="dashboard-progress-value">{{ $progress }}%</span>
                                    </div>
                                    <progress class="dashboard-progress-track" value="{{ $progress }}" max="100">{{ $progress }}%</progress>
                                </div>
                            </td>
                            <td>{{ $item->mitra }}</td>
                            <td class="text-center">
                                <a href="{{ route('eksekutif.deal.show', $item->id) }}" class="btn btn-sm btn-primary dashboard-action-btn dashboard-action-btn-primary">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data deal</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer dashboard-panel-footer">
                <nav aria-label="Deal pagination">
                    <ul class="pagination justify-content-center mb-0 dashboard-pagination">
                        @if ($deals->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo;</span></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ $deals->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        @foreach ($deals->getUrlRange(1, $deals->lastPage()) as $page => $url)
                        @if ($page == $deals->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                        @endforeach

                        @if ($deals->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $deals->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

</div>

@push('styles')
<style>
    .dashboard-stat-card,
    .dashboard-summary-card,
    .dashboard-panel-card {
        border: 1px solid #dce4ef;
        border-radius: 12px !important;
        background: #fff;
        box-shadow: 0 2px 8px rgba(28, 52, 83, 0.08) !important;
        overflow: hidden;
    }

    .dashboard-stat-body {
        padding: 0.65rem 0.75rem;
    }

    .dashboard-stat-label {
        font-family: 'Inter', sans-serif !important;
        font-size: 0.78rem !important;
        color: #172b42 !important;
        font-weight: 800 !important;
        letter-spacing: 0.01em !important;
    }

    .dashboard-stat-value {
        font-family: 'Inter', sans-serif !important;
        font-size: 1.45rem !important;
        color: #172b42 !important;
        font-weight: 800 !important;
    }

    .dashboard-stat-icon {
        font-size: 1.45rem;
        color: #1f2b3a;
        opacity: 0.82;
    }

    .dashboard-summary-card .card-body {
        padding: 0.7rem 0.8rem;
    }

    .dashboard-summary-title {
        font-size: 0.76rem;
        color: #2f4057;
        font-weight: 700;
    }

    .dashboard-summary-filter {
        font-size: 0.7rem;
        color: #5f728a;
        font-weight: 600;
    }

    .dashboard-summary-value {
        margin: 0.25rem 0 0;
        font-size: 1.45rem;
        font-weight: 700;
        color: #2f6db7;
    }

    .dashboard-summary-sub {
        margin: 0 0 0.15rem;
        font-size: 0.8rem;
        color: #3f536d;
    }

    .dashboard-donut-chart {
        width: 138px;
        height: 138px;
        border-radius: 50%;
        background: conic-gradient(#6a81e8 0 50%, #ff4b4b 50% 65%, #37be5b 65% 100%);
        position: relative;
    }

    .dashboard-donut-chart::after {
        content: "";
        position: absolute;
        inset: 22px;
        background: #fff;
        border-radius: 50%;
    }

    .dashboard-legend li {
        font-size: 0.8rem;
        color: #3c4f67;
        margin-bottom: 0.28rem;
        font-weight: 600;
    }

    .dashboard-donut-area {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
    }

    .dashboard-legend {
        display: flex;
        gap: 0.95rem;
    }

    .dashboard-legend li {
        margin-bottom: 0;
    }

    .dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .dot-blue {
        background: #6a81e8;
    }

    .dot-red {
        background: #ff4b4b;
    }

    .dot-green {
        background: #37be5b;
    }

    .dashboard-line-wrap {
        border: 1px solid #e5ebf5;
        border-radius: 4px;
        padding: 0.45rem 0.4rem 0.2rem;
    }

    .dashboard-line-svg {
        width: 100%;
        height: 175px;
    }

    .dashboard-line-svg .line {
        fill: none;
        stroke: #37be5b;
        stroke-width: 2;
    }

    .dashboard-line-svg .points circle {
        fill: #37be5b;
    }

    .dashboard-line-legend {
        display: inline-block;
        margin-top: 0.18rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #3c4f67;
    }

    .dashboard-stats-row,
    .dashboard-summary-row {
        margin-bottom: 0.75rem !important;
    }

    .dashboard-panel-header {
        background: #f8fafd;
        border-bottom: 1px solid #e7edf6;
        padding: 0.42rem 0.6rem;
        border-top-left-radius: 11px !important;
        border-top-right-radius: 11px !important;
    }

    .dashboard-chart-card {
        overflow: hidden;
    }

    .dashboard-chart-body {
        padding: 0.95rem 1rem 1.1rem;
    }

    .dashboard-chart-wrap {
        position: relative;
        width: 100%;
        min-height: 220px;
    }

    .dashboard-chart-wrap::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .dashboard-chart-wrap::-webkit-scrollbar-track {
        background: transparent;
    }
    .dashboard-chart-wrap::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 4px;
    }
    .dashboard-chart-wrap::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.25);
    }

    .dashboard-panel-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        color: #172b42;
        line-height: 1.3;
    }

    .dashboard-table {
        width: 100%;
        table-layout: fixed;
    }

    .dashboard-table thead th {
        font-size: 0.76rem;
        font-weight: 500;
        color: #7f8ea3;
        background: #f4f6fa;
        border-bottom: 1px solid #edf2f8;
        padding: 0.45rem 0.65rem;
        text-align: center;
    }

    .dashboard-table thead th:nth-child(1),
    .dashboard-table tbody td:nth-child(1) {
        width: 34%;
    }

    .dashboard-table thead th:nth-child(2),
    .dashboard-table tbody td:nth-child(2) {
        width: 20%;
    }

    .dashboard-table thead th:nth-child(3),
    .dashboard-table tbody td:nth-child(3) {
        width: 24%;
    }

    .dashboard-table thead th:nth-child(4),
    .dashboard-table tbody td:nth-child(4) {
        width: 22%;
    }

    .dashboard-table-deal thead th:nth-child(1),
    .dashboard-table-deal tbody td:nth-child(1) {
        width: 28%;
    }

    .dashboard-table-deal thead th:nth-child(2),
    .dashboard-table-deal tbody td:nth-child(2) {
        width: 24%;
    }

    .dashboard-table-deal thead th:nth-child(3),
    .dashboard-table-deal tbody td:nth-child(3) {
        width: 24%;
    }

    .dashboard-table-deal thead th:nth-child(4),
    .dashboard-table-deal tbody td:nth-child(4) {
        width: 22%;
        text-align: center !important;
    }

    .dashboard-table thead th+th {
        border-left: 1px solid #e8eef7;
    }

    .dashboard-table tbody td {
        padding: 0.56rem 0.65rem;
        border-color: #edf2f8;
        color: #3a5373;
        font-size: 0.78rem;
        vertical-align: middle;
    }

    .dashboard-table tbody tr {
        height: 66px;
    }

    .dashboard-project-name {
        font-size: inherit;
        font-weight: 400;
        color: inherit;
        line-height: 1.2;
    }

    .dashboard-project-date {
        font-size: 0.72rem;
        color: #9aaabe !important;
    }

    .penawaran-status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        min-height: auto;
        padding: 0.28rem 0.64rem;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 0.72rem;
        font-weight: 600;
        line-height: 1.15;
        letter-spacing: normal;
        white-space: normal;
        box-shadow: none;
        text-align: center;
    }

    .dashboard-table thead th:nth-child(2),
    .dashboard-table tbody td:nth-child(2),
    .dashboard-table thead th:nth-child(4),
    .dashboard-table tbody td:nth-child(4) {
        text-align: center !important;
    }

    .penawaran-status-badge.is-waiting {
        background: #fff4d8;
        color: #9a6b00;
    }

    .penawaran-status-badge.is-approved {
        background: #e5f7ea;
        border-color: #b9e5c3;
        color: #2c7a42;
    }

    .penawaran-status-badge.is-rejected {
        background: #f4dddd;
        color: #d55e5e;
    }

    .penawaran-status-badge.is-neutral {
        background: #eef2f7;
        border-color: #dbe4ef;
        color: #5c6d84;
    }

    .dashboard-progress {
        min-width: 96px;
        max-width: 112px;
        margin: 0 auto;
        padding: 0.22rem 0.4rem;
        border-radius: 10px;
        border: 1px solid transparent;
        background: #f4f7fc;
    }

    .dashboard-progress-head {
        display: flex;
        justify-content: center;
        margin-bottom: 0.2rem;
        line-height: 1;
    }

    .dashboard-progress-value {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .dashboard-progress-track {
        width: 100%;
        height: 5px;
        border-radius: 999px;
        overflow: hidden;
        background: rgba(51, 79, 113, 0.14);
        border: 0;
        appearance: none;
        -webkit-appearance: none;
    }

    .dashboard-progress-track::-webkit-progress-bar {
        background: rgba(51, 79, 113, 0.14);
        border-radius: 999px;
    }

    .dashboard-progress-track::-webkit-progress-value {
        border-radius: 999px;
        transition: width 0.25s ease;
    }

    .dashboard-progress-track::-moz-progress-bar {
        border-radius: 999px;
        transition: width 0.25s ease;
    }

    .dashboard-progress.is-zero {
        background: #f3f4f7;
        border-color: #e1e5ec;
    }

    .dashboard-progress.is-zero .dashboard-progress-value {
        color: #6f7d90;
    }

    .dashboard-progress.is-zero .dashboard-progress-track::-webkit-progress-value {
        background: #9aa8bb;
    }

    .dashboard-progress.is-zero .dashboard-progress-track::-moz-progress-bar {
        background: #9aa8bb;
    }

    .dashboard-progress.is-active {
        background: #fff8e8;
        border-color: #f4e0a6;
    }

    .dashboard-progress.is-active .dashboard-progress-value {
        color: #9b7000;
    }

    .dashboard-progress.is-active .dashboard-progress-track::-webkit-progress-value {
        background: linear-gradient(90deg, #f8c43b 0%, #f0ad17 100%);
    }

    .dashboard-progress.is-active .dashboard-progress-track::-moz-progress-bar {
        background: linear-gradient(90deg, #f8c43b 0%, #f0ad17 100%);
    }

    .dashboard-progress.is-complete {
        background: #ecf9ef;
        border-color: #bde8c6;
    }

    .dashboard-progress.is-complete .dashboard-progress-value {
        color: #217a3c;
    }

    .dashboard-progress.is-complete .dashboard-progress-track::-webkit-progress-value {
        background: linear-gradient(90deg, #37c964 0%, #2bb454 100%);
    }

    .dashboard-progress.is-complete .dashboard-progress-track::-moz-progress-bar {
        background: linear-gradient(90deg, #37c964 0%, #2bb454 100%);
    }

    .dashboard-action-btn {
        min-width: 70px;
        border-radius: 999px;
        border: 0;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.28rem 0.64rem;
        box-shadow: none;
        background: #d9dcf8;
        color: #4a68d6;
        transition: all 0.15s ease;
    }

    .dashboard-action-btn:hover,
    .dashboard-action-btn:focus,
    .dashboard-action-btn:active {
        background: #4a68d6 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .dashboard-pagination .page-link {
        border: 1px solid #e0e8f3;
        border-radius: 4px;
        min-width: 28px;
        height: 28px;
        margin: 0 2px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #617591;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(24, 43, 70, 0.14);
    }

    .dashboard-pagination .page-item.active .page-link {
        background: #007375;
        border-color: #007375;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0, 115, 117, 0.32);
    }

    /* DARK MODE STYLES (disabled) */
    .disabled-dark-mode .dashboard-stat-card,
    .disabled-dark-mode .dashboard-summary-card,
    .disabled-dark-mode .dashboard-panel-card {
        border: 1px solid #444;
        background: #2e2e2e;
    }

    .disabled-dark-mode .dashboard-stat-body {
        background: #2e2e2e;
    }

    .disabled-dark-mode .dashboard-stat-label {
        color: #e0e0e0;
    }

    .disabled-dark-mode .dashboard-stat-value {
        color: #ffffff;
    }

    .disabled-dark-mode .dashboard-stat-icon {
        color: #cccccc;
        opacity: 1;
    }

    .disabled-dark-mode .dashboard-panel-header {
        background: #3a3a3a;
        border-bottom-color: #444;
    }

    .disabled-dark-mode .dashboard-panel-title {
        color: #ffffff;
    }

    .disabled-dark-mode .dashboard-panel-footer {
        background: #3a3a3a;
        border-top-color: #444;
    }

    .disabled-dark-mode .dashboard-table,
    .disabled-dark-mode .dashboard-table thead,
    .disabled-dark-mode .dashboard-table tbody {
        background: #2e2e2e !important;
    }

    .disabled-dark-mode .dashboard-table thead th {
        background: #3a3a3a !important;
        color: #e0e0e0 !important;
        border-color: #444 !important;
    }

    .disabled-dark-mode .dashboard-table tbody {
        background: #2e2e2e !important;
    }

    .disabled-dark-mode .dashboard-table tbody tr {
        background: #2e2e2e !important;
        color: #e0e0e0 !important;
    }

    .disabled-dark-mode .dashboard-table tbody tr td {
        background-color: #2e2e2e !important;
        border-color: #444 !important;
        color: #e0e0e0 !important;
    }

    .disabled-dark-mode .dashboard-table tbody tr:hover {
        background: #3e3e3e !important;
    }

    .disabled-dark-mode .dashboard-table tbody tr:hover td {
        background-color: #3e3e3e !important;
    }

    .disabled-dark-mode .dashboard-table thead th+th {
        border-left-color: #444;
    }

    .disabled-dark-mode .dashboard-project-name {
        color: #ffffff;
    }

    .disabled-dark-mode .dashboard-project-date {
        color: #a0a0a0;
    }

    .disabled-dark-mode .penawaran-status-badge.is-waiting {
        background: #4a4000;
        color: #ffd700;
    }

    .disabled-dark-mode .penawaran-status-badge.is-approved {
        background: #1a4d2e;
        color: #52ff52;
    }

    .disabled-dark-mode .penawaran-status-badge.is-rejected {
        background: #4a2222;
        color: #ff6b6b;
    }

    .disabled-dark-mode .penawaran-status-badge.is-neutral {
        background: #3a3a3a;
        color: #b0b0b0;
    }

    .disabled-dark-mode .dashboard-progress {
        background: #3a3a3a;
        border-color: #555;
    }

    .disabled-dark-mode .dashboard-progress-value {
        color: #e0e0e0;
    }

    .disabled-dark-mode .dashboard-progress.is-zero {
        background: #3a3a3a;
        border-color: #555;
    }

    .disabled-dark-mode .dashboard-progress.is-zero .dashboard-progress-value {
        color: #a0a0a0;
    }

    .disabled-dark-mode .dashboard-progress.is-zero .dashboard-progress-track {
        background: rgba(100, 100, 100, 0.5);
    }

    .disabled-dark-mode .dashboard-progress.is-zero .dashboard-progress-track::-webkit-progress-bar {
        background: rgba(100, 100, 100, 0.5);
    }

    .disabled-dark-mode .dashboard-progress.is-active {
        background: #3a3a3a;
        border-color: #555;
    }

    .disabled-dark-mode .dashboard-progress.is-active .dashboard-progress-value {
        color: #f0ad17;
    }

    .disabled-dark-mode .dashboard-progress.is-complete {
        background: #3a3a3a;
        border-color: #555;
    }

    .disabled-dark-mode .dashboard-progress.is-complete .dashboard-progress-value {
        color: #37c964;
    }

    .disabled-dark-mode .dashboard-action-btn {
        background: #1f4788;
        color: #ffffff;
        border-color: #2a5bb8;
    }

    .disabled-dark-mode .dashboard-action-btn:hover {
        background: #2a5bb8;
    }

    .disabled-dark-mode .dashboard-pagination .page-link {
        background: #3a3a3a;
        border-color: #555;
        color: #e0e0e0;
    }

    .disabled-dark-mode .dashboard-pagination .page-link:hover {
        background: #4a4a4a;
        border-color: #666;
        color: #fff;
    }

    .disabled-dark-mode .dashboard-pagination .page-item.active .page-link {
        background: #1f4788;
        border-color: #1f4788;
        color: #ffffff;
    }

    .disabled-dark-mode .dashboard-pagination .page-item.disabled .page-link {
        background: #3a3a3a;
        border-color: #555;
        color: #666;
    }

    .disabled-dark-mode .dashboard-summary-title,
    .disabled-dark-mode .dashboard-summary-filter {
        color: #e0e0e0;
    }

    .disabled-dark-mode .dashboard-legend li {
        color: #e0e0e0;
    }

    .disabled-dark-mode .dashboard-line-wrap {
        border-color: #444;
    }

    .chart-nav-btn {
        border: 1px solid #dce4ef;
        background-color: #ffffff;
        color: #5f728a;
        width: 24px;
        height: 24px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    .chart-nav-btn:hover:not(:disabled) {
        background-color: #f4f7fc;
        color: #2f7cff;
        border-color: #2f7cff;
    }
    .chart-nav-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
        background-color: #f5f5f5;
        border-color: #e0e0e0;
    }
</style>
@endpush

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function() {
        const chartCanvas = document.getElementById('monthlyProjectsChart');
        if (!chartCanvas || typeof Chart === 'undefined') {
            return;
        }

        Chart.defaults.font.family = 'Inter';

        const labels = @json($chartLabels);
        const penawaranData = @json($chartPenawaran);
        const dealData = @json($chartDeal);

        const ctx = chartCanvas.getContext('2d');
        const windowSize = 6;
        let startIndex = Math.max(0, labels.length - windowSize);

        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.slice(startIndex, startIndex + windowSize),
                datasets: [{
                    label: 'Proyek Penawaran',
                    data: penawaranData.slice(startIndex, startIndex + windowSize),
                    borderColor: '#00adb5',
                    backgroundColor: 'rgba(0, 173, 181, 0.12)',
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                }, {
                    label: 'Proyek Deal',
                    data: dealData.slice(startIndex, startIndex + windowSize),
                    borderColor: '#22b573',
                    backgroundColor: 'rgba(34, 181, 115, 0.12)',
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 10,
                            color: '#2f4057',
                            font: {
                                weight: '600'
                            }
                        }
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y} proyek`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(145, 160, 181, 0.14)'
                        },
                        ticks: {
                            color: '#5f728a',
                            font: {
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        precision: 0,
                        ticks: {
                            stepSize: 1,
                            color: '#5f728a',
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            color: 'rgba(145, 160, 181, 0.18)'
                        }
                    }
                }
            }
        });

        const updateChartData = () => {
            myChart.data.labels = labels.slice(startIndex, startIndex + windowSize);
            myChart.data.datasets[0].data = penawaranData.slice(startIndex, startIndex + windowSize);
            myChart.data.datasets[1].data = dealData.slice(startIndex, startIndex + windowSize);
            myChart.update();

            const prevBtn = document.getElementById('prevMonthsBtn');
            const nextBtn = document.getElementById('nextMonthsBtn');
            if (prevBtn) prevBtn.disabled = (startIndex === 0);
            if (nextBtn) nextBtn.disabled = (startIndex + windowSize >= labels.length);
        };

        const prevBtn = document.getElementById('prevMonthsBtn');
        const nextBtn = document.getElementById('nextMonthsBtn');

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (startIndex > 0) {
                    startIndex--;
                    updateChartData();
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (startIndex + windowSize < labels.length) {
                    startIndex++;
                    updateChartData();
                }
            });
        }

        // Initialize button states
        updateChartData();

        // Doughnut Chart untuk Status Proyek Deal
        const doughnutCanvas = document.getElementById('dealStatusDoughnutChart');
        if (doughnutCanvas) {
            const selesaiCount = @json($proyekSelesai);
            const berjalanCount = @json($proyekBerjalan);
            const totalDeal = selesaiCount + berjalanCount;

            const hasData = totalDeal > 0;
            const chartData = hasData ? [selesaiCount, berjalanCount] : [1];
            const bgColors = hasData ? ['#22b573', '#f0ad17'] : ['#e0e0e0'];
            const hoverBgColors = hasData ? ['#1d9a60', '#d89b14'] : ['#e0e0e0'];
            const chartLabels = hasData ? ['Selesai', 'On Progress'] : ['Tidak Ada Proyek Deal'];

            const doughnutCtx = doughnutCanvas.getContext('2d');
            new Chart(doughnutCtx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: bgColors,
                        hoverBackgroundColor: hoverBgColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: hasData,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const percentage = totalDeal > 0 ? Math.round((value / totalDeal) * 100) : 0;
                                    return `${context.label}: ${value} proyek (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        // Horizontal Bar Chart untuk Jumlah Proyek per Mitra
        const barCanvas = document.getElementById('partnerProjectsChart');
        if (barCanvas) {
            const partnerLabels = @json($partnerLabels ?? []);
            const partnerCounts = @json($partnerCounts ?? []);

            const hasData = partnerLabels.length > 0;
            const chartLabels = hasData ? partnerLabels : ['Belum Ada Mitra'];
            const chartData = hasData ? partnerCounts : [0];

            // Dynamically adjust height of chart container based on number of labels to avoid overlapping/squeezing
            const chartContainer = document.getElementById('partnerProjectsChartContainer');
            if (chartContainer) {
                // Base height: 50px for axes & legend. Each bar gets 38px. Min height is 220px.
                const calculatedHeight = Math.max(220, (chartLabels.length * 38) + 50);
                chartContainer.style.height = calculatedHeight + 'px';
            }

            const barCtx = barCanvas.getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: chartData,
                        backgroundColor: 'rgba(0, 173, 181, 0.75)',
                        borderColor: '#00adb5',
                        borderRadius: 6,
                        barThickness: 18,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    elements: {
                        bar: {
                            borderWidth: 2,
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 5,
                            bottom: 5,
                            left: 10,
                            right: 15
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#2f4057',
                                font: {
                                    weight: '600'
                                }
                            }
                        },
                        title: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Jumlah Proyek: ${context.parsed.x} proyek`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            precision: 0,
                            ticks: {
                                stepSize: 1,
                                color: '#5f728a',
                                font: {
                                    weight: '600'
                                }
                            },
                            grid: {
                                color: 'rgba(145, 160, 181, 0.14)'
                            }
                        },
                        y: {
                            ticks: {
                                autoSkip: false, // Prevent skipping any partner labels when space is limited
                                color: '#2f4057',
                                font: {
                                    weight: '600'
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    })();
</script>

@endsection