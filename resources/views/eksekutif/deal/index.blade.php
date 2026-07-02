@extends('layouts.app')

@section('content')
<!-- Proyek Deal -->
<div class="card shadow-sm mb-4 deal-page-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 deal-header">
        <div class="deal-heading-block">
            <h5 class="mb-0 deal-title">Proyek Deal</h5>
            <small class="deal-subtitle">Daftar proyek aktif yang sudah masuk tahap deal.</small>
        </div>
        <form id="filterForm" method="GET" action="{{ route('eksekutif.deal.index') }}" class="deal-filter-form">
            <div class="ml-auto d-flex align-items-center position-relative deal-filter-controls" id="dealContainer">

                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

                <div class="deal-search-wrap">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm deal-search-input"
                        placeholder="Search for anything...."
                        aria-label="Cari nama proyek">

                    <button type="submit" class="deal-search-button" aria-label="Search proyek">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </div>

                <button type="button" id="dealDate" class="btn btn-sm btn-outline-secondary dashboard-date-button">
                    <i class="far fa-calendar-alt me-2"></i>
                    <span class="date-button-text">
                        {{ request('start_date') && request('end_date')
                            ? \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y').' - '.\Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y')
                            : 'Pilih Tanggal' }}
                    </span>
                </button>

                <button
                    type="button"
                    id="clearDealDate"
                    class="btn btn-sm deal-date-clear-btn {{ request('start_date') && request('end_date') ? '' : 'd-none' }}"
                    aria-label="Hapus tanggal"
                    title="Hapus tanggal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>

            </div>
        </form>

    </div>

    <div class="card-body p-0 deal-table-wrap">
        <table class="table table-hover mb-0 deal-table align-middle">
            <colgroup>
                <col class="deal-col-nama">
                <col class="deal-col-status">
                <col class="deal-col-mitra">
                <col class="deal-col-kelola">
            </colgroup>
            <thead class="table-light">
                <tr>
                    <th>Nama Proyek</th>
                    <th>Status</th>
                    <th>Mitra</th>
                    <th>Kelola</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $item)
                <tr>
                    <td>
                        <div class="deal-project-name">{{ $item->nama_proyek }}</div>
                        <small class="text-muted deal-project-date">
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                        </small>
                    </td>
                    <td>
                        @php
                        $progress = (int) $item->progress;
                        $progressClass = $progress === 0 ? 'is-zero' : ($progress >= 100 ? 'is-complete' : 'is-active');
                        @endphp
                        <div class="deal-progress {{ $progressClass }}" aria-label="Progress {{ $progress }} persen">
                            <div class="deal-progress-head">
                                <span class="deal-progress-value">{{ $progress }}%</span>
                            </div>
                            <progress class="deal-progress-track" value="{{ $progress }}" max="100">{{ $progress }}%</progress>
                        </div>
                    </td>
                    <td>{{ $item->mitra }}</td>
                    <td>
                        <div class="deal-actions">
                            <a href="{{ route('eksekutif.deal.show', $item->id) }}" class="btn btn-sm app-chip btn-primary deal-action-btn deal-action-btn-primary">Detail</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted deal-empty-state">Belum ada data deal</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="deal-pagination-wrap">
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

<!-- CSS & JS flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const createHiddenPickerInput = (id) => {
        const input = document.createElement('input');
        input.type = 'text';
        input.readOnly = true;
        input.id = id;
        input.className = 'flatpickr-hidden-input';
        input.setAttribute('aria-hidden', 'true');
        input.tabIndex = -1;
        input.style.position = 'absolute';
        input.style.left = '0';
        input.style.width = '1px';
        input.style.height = '1px';
        input.style.opacity = '0';
        input.style.pointerEvents = 'none';
        input.style.border = '0';
        input.style.padding = '0';
        input.style.margin = '0';
        document.body.appendChild(input);
        return input;
    };

    // Kalender Filter untuk Proyek Deal
    const dealPickerInput = createHiddenPickerInput('dealInput');
    const dealFP = flatpickr(dealPickerInput, {
        mode: "range",
        dateFormat: "d M Y",
        appendTo: document.body,
        positionElement: document.getElementById("dealDate"),
        position: "auto left",
        clickOpens: false,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {

                const startISO = selectedDates[0].toISOString().split('T')[0];
                const endISO = selectedDates[1].toISOString().split('T')[0];

                // ISI HIDDEN INPUT
                document.getElementById('start_date').value = startISO;
                document.getElementById('end_date').value = endISO;

                // SUBMIT FORM
                document.getElementById('filterForm').submit();
                instance.close();
            }
        }
    });

    document.getElementById('dealDate').addEventListener('click', () => dealFP.open());

    const clearDealDateButton = document.getElementById('clearDealDate');
    if (clearDealDateButton) {
        clearDealDateButton.addEventListener('click', () => {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('filterForm').submit();
        });
    }
</script>

@push('styles')
<style>
    .deal-page-card {
        border: 1px solid #dce4ef;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(36, 58, 87, 0.08);
    }

    .deal-header {
        background: #f8fafd;
        border-bottom: 1px solid #e7edf6;
        padding: 0.55rem 0.75rem;
    }

    .deal-title {
        font-size: 1.85rem;
        font-weight: 700;
        color: #243d5e;
        line-height: 1.1;
    }

    .deal-subtitle {
        display: none;
    }

    .deal-filter-form {
        flex: 1 1 auto;
        margin-left: 1rem;
    }

    .deal-filter-controls {
        justify-content: flex-end;
        gap: 0.45rem;
    }

    .deal-search-wrap {
        position: relative;
        width: min(100%, 250px);
    }

    .deal-search-input {
        width: 100%;
        border: 1px solid transparent;
        border-radius: 16px;
        background: #f2f2f5;
        color: #72778a;
        font-size: 0.95rem;
        font-weight: 500;
        padding: 0.42rem 0.72rem;
        box-shadow: none;
        transition: background-color 0.2s ease;
    }

    .deal-search-input:focus {
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(131, 145, 176, 0.22);
    }

    .deal-search-button {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #7f8ea3;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }

    .deal-search-button:hover,
    .deal-search-button:focus {
        color: #4d627f;
    }

    .deal-search-button:focus-visible {
        outline: none;
        box-shadow: 0 0 0 0.12rem rgba(131, 145, 176, 0.22);
    }

    @media (max-width: 576px) {
        .deal-filter-form {
            width: 100%;
            margin-left: 0;
        }

        .deal-filter-controls {
            justify-content: flex-start;
        }

        .deal-search-wrap {
            width: 100%;
        }
    }

    #dealDate {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.38rem !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        border: 1px solid #c9d0db !important;
        border-radius: 8px !important;
        background: #e5e8ed !important;
        color: #515e73 !important;
        font-size: 0.92rem !important;
        font-weight: 500 !important;
        padding: 0.42rem 0.72rem !important;
        box-shadow: none !important;
    }

    #dealDate:hover {
        background: #dce0e8 !important;
        border-color: #c9d0db !important;
        color: #3f4a60 !important;
    }

    #dealDate:focus {
        outline: none;
        box-shadow: 0 0 0 0.12rem rgba(74, 104, 214, 0.18);
    }

    #dealDate .date-button-text {
        white-space: nowrap;
    }

    .deal-date-clear-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        flex-shrink: 0;
        border: 1px solid #d5deea;
        border-radius: 8px;
        background: #fff;
        color: #5f728a;
        width: 36px;
        height: 36px;
        padding: 0;
    }

    .deal-date-clear-btn:hover {
        background: #f4f7fb;
        color: #4b607c;
    }

    .deal-table-wrap {
        border-top: 1px solid #edf2f8;
    }

    .deal-table thead th {
        font-size: 0.9rem;
        font-weight: 500;
        color: #7f8ea3;
        background: #f4f6fa;
        border-bottom: 1px solid #edf2f8;
        padding: 0.58rem 0.9rem;
        text-align: center;
        text-transform: none;
        letter-spacing: normal;
    }

    .deal-table thead th+th {
        border-left: 1px solid #e8eef7;
    }

    .deal-col-nama {
        width: 34%;
    }

    .deal-col-status {
        width: 20%;
    }

    .deal-col-mitra {
        width: 20%;
    }

    .deal-col-kelola {
        width: 26%;
    }

    .deal-table thead th:nth-child(2),
    .deal-table tbody td:nth-child(2) {
        white-space: nowrap;
    }

    .deal-table tbody td:last-child {
        text-align: center;
    }

    .deal-table tbody td {
        padding: 0.9rem 0.9rem;
        border-color: #edf2f8;
        color: #3a5373;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .deal-table tbody tr:hover {
        background: #fafcff;
    }

    .deal-project-name {
        font-size: inherit;
        font-weight: 400;
        color: inherit;
        line-height: 1.2;
    }

    .deal-project-date {
        font-size: 0.82rem;
        color: #9aaabe !important;
    }

    .deal-progress {
        min-width: 104px;
        max-width: 124px;
        margin: 0 auto;
        padding: 0.24rem 0.4rem;
        border-radius: 10px;
        border: 1px solid transparent;
        background: #f4f7fc;
    }

    .deal-progress-head {
        display: flex;
        justify-content: center;
        margin-bottom: 0.18rem;
        line-height: 1;
    }

    .deal-progress-value {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    .deal-progress-track {
        height: 5px;
        width: 100%;
        border-radius: 999px;
        overflow: hidden;
        background: rgba(51, 79, 113, 0.14);
        border: 0;
        appearance: none;
        -webkit-appearance: none;
    }

    .deal-progress-track::-webkit-progress-bar {
        background: rgba(51, 79, 113, 0.14);
        border-radius: 999px;
    }

    .deal-progress-track::-webkit-progress-value {
        border-radius: 999px;
        transition: width 0.25s ease;
    }

    .deal-progress-track::-moz-progress-bar {
        border-radius: 999px;
        transition: width 0.25s ease;
    }

    .deal-progress.is-zero {
        background: #f3f4f7;
        border-color: #e1e5ec;
    }

    .deal-progress.is-zero .deal-progress-value {
        color: #6f7d90;
    }

    .deal-progress.is-zero .deal-progress-track::-webkit-progress-value {
        background: #9aa8bb;
    }

    .deal-progress.is-zero .deal-progress-track::-moz-progress-bar {
        background: #9aa8bb;
    }

    .deal-progress.is-active {
        background: #fff8e8;
        border-color: #f4e0a6;
    }

    .deal-progress.is-active .deal-progress-value {
        color: #9b7000;
    }

    .deal-progress.is-active .deal-progress-track::-webkit-progress-value {
        background: linear-gradient(90deg, #f8c43b 0%, #f0ad17 100%);
    }

    .deal-progress.is-active .deal-progress-track::-moz-progress-bar {
        background: linear-gradient(90deg, #f8c43b 0%, #f0ad17 100%);
    }

    .deal-progress.is-complete {
        background: #ecf9ef;
        border-color: #bde8c6;
    }

    .deal-progress.is-complete .deal-progress-value {
        color: #217a3c;
    }

    .deal-progress.is-complete .deal-progress-track::-webkit-progress-value {
        background: linear-gradient(90deg, #37c964 0%, #2bb454 100%);
    }

    .deal-progress.is-complete .deal-progress-track::-moz-progress-bar {
        background: linear-gradient(90deg, #37c964 0%, #2bb454 100%);
    }

    .deal-actions {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        gap: 0.4rem;
        flex-wrap: nowrap;
        white-space: nowrap;
     }

     .deal-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        height: 31px !important;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.78rem;
        margin: 0 !important;
        white-space: nowrap;
        box-shadow: none;
        opacity: 1;
        transition: background-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
        -webkit-tap-highlight-color: transparent;
        -webkit-appearance: none;
        appearance: none;
        line-height: 1.5;
     }

    .deal-action-btn-primary {
        border: 0 !important;
        background: #d9dcf8 !important;
        color: #4a68d6 !important;
    }

    .deal-action-btn-primary:hover,
    .deal-action-btn-primary:focus,
    .deal-action-btn-primary:active {
        background: #4a68d6 !important;
        color: #ffffff !important;
    }

    .deal-action-btn-danger {
        border-color: #e6c0c0;
        background: #f4dddd;
        color: #d55e5e;
    }

    .deal-action-btn-danger:hover,
    .deal-action-btn-danger:focus,
    .deal-action-btn-danger:active {
        background: #f0d1d1;
        color: #c95353;
    }

    .deal-pagination-wrap {
        padding: 0.2rem 0 0.35rem;
    }

    .deal-pagination-wrap .dashboard-pagination .page-link {
        border: 1px solid #e0e8f3;
        border-radius: 4px;
        min-width: 28px;
        height: 28px;
        margin: 0 2px;
        padding: 0;
        font-size: 0.78rem;
        font-weight: 600;
        color: #617591;
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(24, 43, 70, 0.14);
    }

    .deal-pagination-wrap .dashboard-pagination .page-item.active .page-link {
        background: #007375;
        border-color: #007375;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0, 115, 117, 0.32);
    }

    .deal-pagination-wrap .dashboard-pagination .page-item.disabled .page-link {
        color: #a3b2c4;
        background: #f6f8fb;
    }

    .flatpickr-hidden-input {
        position: absolute;
        width: 0;
        height: 0;
        opacity: 0;
        pointer-events: none;
        border: 0;
        padding: 0;
        margin: 0;
    }
</style>
@endpush
@endsection