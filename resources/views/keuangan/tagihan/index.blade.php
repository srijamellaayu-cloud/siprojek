@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4 tagihan-page-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 tagihan-header">
        <div class="tagihan-heading-block">
            <h5 class="mb-0 tagihan-title">Tagihan Proyek</h5>
            <small class="tagihan-subtitle">Daftar proyek deal yang telah selesai (progress 100%) untuk penagihan.</small>
        </div>

        <form id="filterForm" method="GET" action="{{ route('keuangan.tagihan.index') }}" class="tagihan-filter-form">
            <div class="ml-auto d-flex align-items-center position-relative tagihan-filter-controls" id="tagihanContainer">
                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

                <div class="tagihan-search-wrap">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm tagihan-search-input"
                        placeholder="Search for anything...."
                        aria-label="Cari nama proyek">
                    <button type="submit" class="tagihan-search-button" aria-label="Search tagihan">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </div>

                <button type="button" id="tagihanDate" class="btn btn-sm btn-outline-secondary dashboard-date-button">
                    <i class="far fa-calendar-alt me-2"></i>
                    <span class="date-button-text">
                        {{ request('start_date') && request('end_date')
                            ? \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y').' - '.\Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y')
                            : 'Pilih Tanggal' }}
                    </span>
                </button>

                <button
                    type="button"
                    id="clearTagihanDate"
                    class="btn btn-sm tagihan-date-clear-btn {{ request('start_date') && request('end_date') ? '' : 'd-none' }}"
                    aria-label="Hapus tanggal"
                    title="Hapus tanggal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </form>
    </div>



    <div class="card-body p-0 tagihan-table-wrap">
        <table class="table table-hover mb-0 tagihan-table align-middle">
            <colgroup>
                <col class="tagihan-col-nama">
                <col class="tagihan-col-nominal">
                <col class="tagihan-col-status">
                <col class="tagihan-col-keterangan">
            </colgroup>
            <thead class="table-light">
                <tr>
                    <th>Nama Proyek</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Keterangan Bank Penagihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $item)
                <tr>
                    <td>
                        <div class="tagihan-project-name" title="{{ $item->nama_proyek }}">{{ \Illuminate\Support\Str::words($item->nama_proyek, 5, '...') }}</div>
                        <small class="text-muted tagihan-project-date">
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                        </small>
                    </td>
                    <td>
                        {{ $item->biaya_penawaran !== null ? 'Rp ' . number_format($item->biaya_penawaran, 0, ',', '.') : '-' }}
                    </td>
                    <td>
                        @php
                            $invoiceTask = $item->tasks->where('nama_tugas', 'Invoice Penagihan')->first();
                            $statusText = $invoiceTask && $invoiceTask->status === 'Done' ? 'sudah dibayarkan' : 'proses penagihan';
                            $isPaid = ($statusText === 'sudah dibayarkan');
                        @endphp
                        @if($isPaid)
                            <div class="custom-dropdown position-relative d-inline-block">
                                <button type="button" class="btn btn-sm custom-dropdown-trigger" style="background: #e5f7ea !important; color: #2c7a42 !important; cursor: default !important; pointer-events: none !important; border: 1px solid #b9e5c3 !important; min-width: 175px !important;">
                                    <span class="d-flex align-items-center">
                                        <span class="status-dot is-paid" style="background: #2c7a42 !important;"></span>
                                        <span>Sudah Dibayarkan</span>
                                    </span>
                                </button>
                            </div>
                        @else
                            <form method="POST" action="{{ route('keuangan.tagihan.status.update', $item->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <div class="custom-dropdown position-relative d-inline-block">
                                    <input type="hidden" name="status" value="{{ $statusText }}">
                                    <button type="button" class="btn btn-sm custom-dropdown-trigger">
                                        <span class="d-flex align-items-center">
                                            <span class="status-dot is-pending"></span>
                                            <span>Proses Penagihan</span>
                                        </span>
                                        <i class="fas fa-chevron-down ms-2 dropdown-arrow"></i>
                                    </button>
                                    <div class="custom-dropdown-menu">
                                        <button type="button" class="custom-dropdown-item active" data-value="proses penagihan">
                                            <span class="status-dot is-pending"></span> Proses Penagihan
                                        </button>
                                        <button type="button" class="custom-dropdown-item" data-value="sudah dibayarkan">
                                            <span class="status-dot is-paid"></span> Sudah Dibayarkan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $bankText = $invoiceTask ? ($invoiceTask->bank_penagihan ?: 'Belum ditentukan') : '-';
                        @endphp
                        <span>{{ $bankText }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada data proyek selesai</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="tagihan-pagination-wrap">
            <nav aria-label="Tagihan pagination">
                <ul class="pagination justify-content-center mb-0 dashboard-pagination">
                    @if ($tagihans->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo;</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $tagihans->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                    @endif

                    @foreach ($tagihans->getUrlRange(1, $tagihans->lastPage()) as $page => $url)
                    @if ($page == $tagihans->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                    @endforeach

                    @if ($tagihans->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $tagihans->nextPageUrl() }}" rel="next">&raquo;</a></li>
                    @else
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">&raquo;</span></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const formatDateRange = (selectedDates) => {
        if (selectedDates.length !== 2) {
            return 'Pilih Tanggal';
        }

        const formatter = new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

        return `${formatter.format(selectedDates[0])} - ${formatter.format(selectedDates[1])}`;
    };

    const updateButtonLabel = (buttonId, selectedDates) => {
        const buttonText = document.querySelector(`#${buttonId} .date-button-text`);
        if (buttonText) {
            buttonText.textContent = formatDateRange(selectedDates);
        }
    };

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

    const tagihanPickerInput = createHiddenPickerInput('tagihanInput');
    const tagihanFP = flatpickr(tagihanPickerInput, {
        mode: 'range',
        dateFormat: 'd M Y',
        appendTo: document.body,
        positionElement: document.getElementById('tagihanDate'),
        position: 'auto left',
        clickOpens: false,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                updateButtonLabel('tagihanDate', selectedDates);

                const startISO = selectedDates[0].toISOString().split('T')[0];
                const endISO = selectedDates[1].toISOString().split('T')[0];

                document.getElementById('start_date').value = startISO;
                document.getElementById('end_date').value = endISO;
                document.getElementById('filterForm').submit();
                instance.close();
            }
        }
    });

    document.getElementById('tagihanDate').addEventListener('click', () => tagihanFP.open());

    const clearTagihanDateButton = document.getElementById('clearTagihanDate');
    if (clearTagihanDateButton) {
        clearTagihanDateButton.addEventListener('click', () => {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('filterForm').submit();
        });
    }

    // Custom dropdown logic
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle dropdown open/close
        document.querySelectorAll('.custom-dropdown-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const currentDropdown = trigger.closest('.custom-dropdown');
                
                // Close other dropdowns
                document.querySelectorAll('.custom-dropdown').forEach(d => {
                    if (d !== currentDropdown) d.classList.remove('show');
                });

                currentDropdown.classList.toggle('show');
            });
        });

        // Handle option selection
        document.querySelectorAll('.custom-dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const dropdown = item.closest('.custom-dropdown');
                const hiddenInput = dropdown.querySelector('input[name="status"]');
                const form = dropdown.closest('form');
                const selectedValue = item.getAttribute('data-value');

                hiddenInput.value = selectedValue;
                dropdown.classList.remove('show');
                
                // Submit form
                form.submit();
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('show'));
            }
        });
    });
</script>

@push('styles')
<style>
    .tagihan-page-card {
        border: 1px solid #dce4ef;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(36, 58, 87, 0.08);
    }

    .tagihan-header {
        background: #f8fafd;
        border-bottom: 1px solid #e7edf6;
        padding: 0.55rem 0.75rem;
    }

    .tagihan-title {
        font-size: 1.85rem;
        font-weight: 700;
        color: #243d5e;
        line-height: 1.1;
    }

    .tagihan-subtitle {
        display: none;
    }

    .tagihan-filter-form {
        flex: 1 1 auto;
        margin-left: 1rem;
    }

    .tagihan-filter-controls {
        justify-content: flex-end;
        gap: 0.45rem;
    }

    .tagihan-search-wrap {
        position: relative;
        width: min(100%, 250px);
    }

    .tagihan-search-input {
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

    .tagihan-search-input:focus {
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(131, 145, 176, 0.22);
    }

    .tagihan-search-button {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #7f8ea3;
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }

    .tagihan-search-button:hover,
    .tagihan-search-button:focus {
        color: #4d627f;
    }

    .tagihan-search-button:focus-visible {
        outline: none;
        box-shadow: 0 0 0 0.12rem rgba(131, 145, 176, 0.22);
    }

    @media (max-width: 576px) {
        .tagihan-filter-form {
            width: 100%;
            margin-left: 0;
        }

        .tagihan-filter-controls {
            justify-content: flex-start;
        }

        .tagihan-search-wrap {
            width: 100%;
        }
    }

    #tagihanDate {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.38rem !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        border: 1px solid #bcc3cd !important;
        border-radius: 8px !important;
        background: #e5e8ed !important;
        color: #515e73 !important;
        font-size: 0.92rem !important;
        font-weight: 500 !important;
        padding: 0.42rem 0.72rem !important;
        box-shadow: none !important;
    }

    #tagihanDate:hover {
        background: #dce0e8 !important;
        border-color: #c9d0db !important;
        color: #3f4a60 !important;
    }

    #tagihanDate:focus {
        outline: none;
        box-shadow: 0 0 0 0.12rem rgba(74, 104, 214, 0.18);
    }

    #tagihanDate .date-button-text {
        white-space: nowrap;
    }

    .tagihan-date-clear-btn {
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

    .tagihan-date-clear-btn:hover {
        background: #f4f7fb;
        color: #4b607c;
    }

    .tagihan-table-wrap {
        border-top: 1px solid #edf2f8;
    }

    .tagihan-table thead th {
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

    .tagihan-table thead th+th {
        border-left: 1px solid #e8eef7;
    }

    .tagihan-table tbody td {
        padding: 0.9rem 0.9rem;
        border-color: #edf2f8;
        color: #3a5373;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .tagihan-col-nama {
        width: 30%;
    }

    .tagihan-col-nominal {
        width: 20%;
    }

    .tagihan-col-status {
        width: 20%;
    }

    .tagihan-col-keterangan {
        width: 30%;
    }

    .tagihan-table thead th:nth-child(2),
    .tagihan-table tbody td:nth-child(2) {
        text-align: right;
    }

    .tagihan-table thead th:nth-child(3),
    .tagihan-table tbody td:nth-child(3) {
        text-align: center;
    }

    .tagihan-table tbody tr:hover {
        background: #fafcff;
    }

    .tagihan-project-name {
        font-size: inherit;
        font-weight: 400;
        color: inherit;
        line-height: 1.2;
    }

    .tagihan-project-date {
        font-size: 0.82rem;
        color: #9aaabe !important;
    }

    .tagihan-status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 126px;
        padding: 0.38rem 0.8rem;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 0.8rem;
        font-weight: 700;
        line-height: 1.1;
        letter-spacing: 0.01em;
        white-space: nowrap;
        box-shadow: none;
    }

    .tagihan-status-pill {
        border: 0;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.72rem;
        min-width: 74px;
        line-height: 1.5;
        letter-spacing: normal;
    }

    .tagihan-status-badge.is-approved {
        background: #e5f7ea;
        border-color: #b9e5c3;
        color: #2c7a42;
    }

    .tagihan-pagination-wrap {
        padding: 0.2rem 0 0.35rem;
    }

    .tagihan-pagination-wrap .dashboard-pagination .page-link {
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

    .tagihan-pagination-wrap .dashboard-pagination .page-item.active .page-link {
        background: #007375;
        border-color: #007375;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0, 115, 117, 0.32);
    }

    .tagihan-pagination-wrap .dashboard-pagination .page-item.disabled .page-link {
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

    /* Custom Dropdown Styling */
    .custom-dropdown {
        display: inline-block;
    }

    .custom-dropdown-trigger {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        min-width: 175px;
        padding: 0.38rem 0.8rem !important;
        border-radius: 999px !important;
        border: 0 !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        background: #d9dcf8 !important;
        color: #4a68d6 !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        box-shadow: none !important;
        line-height: 1.5 !important;
    }

    .custom-dropdown-trigger:hover,
    .custom-dropdown-trigger:focus,
    .custom-dropdown-trigger:active {
        background: #4a68d6 !important;
        color: #ffffff !important;
    }

    .custom-dropdown-trigger:hover .status-dot.is-pending,
    .custom-dropdown-trigger:focus .status-dot.is-pending {
        background: #ffe082 !important;
    }

    .custom-dropdown-trigger:hover .status-dot.is-paid,
    .custom-dropdown-trigger:focus .status-dot.is-paid {
        background: #a3ffd6 !important;
    }

    .custom-dropdown-trigger .dropdown-arrow {
        font-size: 0.7rem;
        transition: transform 0.2s ease;
    }

    .custom-dropdown.show .custom-dropdown-trigger .dropdown-arrow {
        transform: rotate(180deg);
    }

    .custom-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        z-index: 1050;
        min-width: 170px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        padding: 0.3rem;
        display: none;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s ease, transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .custom-dropdown.show .custom-dropdown-menu {
        display: block;
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }

    .custom-dropdown-item {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 0.45rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #475569;
        background: transparent;
        border: none;
        border-radius: 6px;
        text-align: left;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .custom-dropdown-item:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .custom-dropdown-item.active {
        background: #e2e8f0;
        color: #0f172a;
    }

    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .status-dot.is-pending {
        background: #f0ad17;
    }

    .status-dot.is-paid {
        background: #22b573;
    }
</style>
@endpush
@endsection
