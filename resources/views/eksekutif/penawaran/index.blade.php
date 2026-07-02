@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4 penawaran-page-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 penawaran-header">
        <div class="penawaran-heading-block">
            <h5 class="mb-0 penawaran-title">Proyek Penawaran</h5>
            <small class="penawaran-subtitle">Kelola data penawaran proyek dan pantau status persetujuan.</small>
        </div>

        <form id="filterForm" method="GET" action="{{ route('eksekutif.penawaran.index') }}" class="penawaran-filter-form">
            <div class="ml-auto d-flex align-items-center position-relative penawaran-filter-controls" id="penawaranContainer">
                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

                <div class="penawaran-search-wrap">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm penawaran-search-input"
                        placeholder="Search for anything...."
                        aria-label="Cari nama proyek">
                    <button type="submit" class="penawaran-search-button" aria-label="Search penawaran">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </div>

                <button type="button" id="penawaranDate" class="btn btn-sm btn-outline-secondary dashboard-date-button">
                    <i class="far fa-calendar-alt me-2"></i>
                    <span class="date-button-text">
                        {{ request('start_date') && request('end_date')
                            ? \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y').' - '.\Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y')
                            : 'Pilih Tanggal' }}
                    </span>
                </button>

                <button
                    type="button"
                    id="clearPenawaranDate"
                    class="btn btn-sm penawaran-date-clear-btn {{ request('start_date') && request('end_date') ? '' : 'd-none' }}"
                    aria-label="Hapus tanggal"
                    title="Hapus tanggal">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>


            </div>
        </form>
    </div>

    <div class="card-body p-0 penawaran-table-wrap">
        <table class="table table-hover mb-0 penawaran-table align-middle">
            <colgroup>
                <col class="penawaran-col-nama">
                <col class="penawaran-col-status">
                <col class="penawaran-col-mitra">
                <col class="penawaran-col-kelola">
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
                @forelse($penawarans as $item)
                @php
                $statusClass = match ($item->status) {
                'Menunggu Persetujuan' => 'is-waiting',
                'Disetujui' => 'is-approved',
                'Ditolak' => 'is-rejected',
                default => 'is-neutral',
                };
                @endphp
                <tr>
                    <td>
                        <div class="penawaran-project-name">{{ $item->nama_proyek }}</div>
                        <small class="text-muted penawaran-project-date">
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                        </small>
                    </td>
                    <td>
                        <span class="penawaran-status-badge penawaran-status-pill {{ $statusClass }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>{{ $item->mitra }}</td>
                    <td>
                        <div class="penawaran-actions {{ $item->status === 'Ditolak' ? 'is-rejected-row' : '' }}">
                            <a href="{{ route('eksekutif.penawaran.show', $item->id) }}" class="btn btn-sm app-chip btn-primary penawaran-action-btn penawaran-action-btn-primary">Detail</a>
                            @if($item->status === 'Ditolak')
                            <button
                                type="button"
                                class="btn btn-sm app-chip penawaran-action-btn penawaran-action-btn-warning js-open-reject-reason"
                                data-project="{{ $item->nama_proyek }}"
                                data-reason="{{ e($item->catatan_penolakan ?? '') }}">
                                Alasan
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted penawaran-empty-state">Belum ada data penawaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="penawaran-pagination-wrap">
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

<div id="rejectReasonModal" class="status-confirm-modal" aria-hidden="true" style="display: none;">
    <div class="status-confirm-modal__overlay" data-reason-close="true"></div>
    <div class="status-confirm-modal__dialog status-confirm-modal__dialog-reason" role="dialog" aria-modal="true" aria-labelledby="rejectReasonTitle">
        <div class="status-confirm-modal__header">
            <h5 id="rejectReasonTitle" class="status-confirm-modal__title">
                <span class="status-confirm-modal__icon is-reject" aria-hidden="true">i</span>
                Alasan Penolakan
            </h5>
        </div>
        <div class="status-confirm-modal__body status-confirm-modal__body-reason">
            <p class="status-confirm-modal__subtext status-confirm-modal__subtext-reason" id="rejectReasonProject"></p>
            <div class="status-confirm-modal__reason-box" id="rejectReasonText">Tidak ada alasan penolakan.</div>
        </div>
        <div class="status-confirm-modal__footer">
            <button type="button" class="btn btn-secondary btn-sm" data-reason-close="true">Tutup</button>
        </div>
    </div>
</div>

<div id="statusConfirmModal" class="status-confirm-modal" aria-hidden="true" style="display: none;">
    <div class="status-confirm-modal__overlay" data-modal-close="true"></div>
    <div class="status-confirm-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="statusConfirmTitle">
        <div class="status-confirm-modal__header">
            <h5 id="statusConfirmTitle" class="status-confirm-modal__title">
                <span class="status-confirm-modal__icon" id="statusConfirmIcon" aria-hidden="true">✔</span>
                <span id="statusConfirmHeading">Setujui Penawaran</span>
            </h5>
        </div>
        <div class="status-confirm-modal__body">
            <p class="status-confirm-modal__text" id="statusConfirmText">Apakah Anda yakin ingin menyetujui penawaran ini?</p>
            <p class="status-confirm-modal__subtext" id="statusConfirmSubtext">Penawaran akan dipindahkan ke daftar Deal.</p>
            <div id="statusRejectCommentWrap" class="status-confirm-modal__comment-wrap" hidden>
                <label for="statusRejectComment" class="status-confirm-modal__comment-label">Alasan penolakan</label>
                <textarea id="statusRejectComment" class="status-confirm-modal__comment-input" rows="2" maxlength="255"></textarea>
            </div>
        </div>
        <div class="status-confirm-modal__footer">
            <button type="button" class="btn btn-secondary btn-sm" data-modal-close="true">Batal</button>
            <button type="button" id="statusConfirmProceed" class="btn btn-success btn-sm">
                <i class="fas fa-check me-1" aria-hidden="true"></i>
                Ya, Setujui
            </button>
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

    const penawaranPickerInput = createHiddenPickerInput('penawaranInput');
    const penawaranFP = flatpickr(penawaranPickerInput, {
        mode: 'range',
        dateFormat: 'd M Y',
        appendTo: document.body,
        positionElement: document.getElementById('penawaranDate'),
        position: 'auto left',
        clickOpens: false,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                updateButtonLabel('penawaranDate', selectedDates);

                const startISO = selectedDates[0].toISOString().split('T')[0];
                const endISO = selectedDates[1].toISOString().split('T')[0];

                document.getElementById('start_date').value = startISO;
                document.getElementById('end_date').value = endISO;
                document.getElementById('filterForm').submit();
                instance.close();
            }
        }
    });

    document.getElementById('penawaranDate').addEventListener('click', () => penawaranFP.open());

    const clearPenawaranDateButton = document.getElementById('clearPenawaranDate');
    if (clearPenawaranDateButton) {
        clearPenawaranDateButton.addEventListener('click', () => {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('filterForm').submit();
        });
    }

    const statusModal = document.getElementById('statusConfirmModal');
    const rejectReasonModal = document.getElementById('rejectReasonModal');
    const statusHeading = document.getElementById('statusConfirmHeading');
    const statusText = document.getElementById('statusConfirmText');
    const statusSubtext = document.getElementById('statusConfirmSubtext');
    const statusIcon = document.getElementById('statusConfirmIcon');
    const statusProceed = document.getElementById('statusConfirmProceed');
    const rejectReasonProject = document.getElementById('rejectReasonProject');
    const rejectReasonText = document.getElementById('rejectReasonText');
    const statusRejectCommentWrap = document.getElementById('statusRejectCommentWrap');
    const statusRejectComment = document.getElementById('statusRejectComment');
    let pendingStatusForm = null;
    let pendingStatusValue = null;

    const openStatusModal = ({
        status,
        form
    }) => {
        pendingStatusForm = form;
        pendingStatusValue = status;
        const isApprove = status === 'Disetujui';

        statusHeading.textContent = isApprove ? 'Setujui Penawaran' : 'Tolak Penawaran';
        statusText.textContent = isApprove ?
            'Apakah Anda yakin ingin menyetujui penawaran ini?' :
            'Apakah Anda yakin ingin menolak penawaran ini?';
        statusSubtext.textContent = isApprove ?
            'Penawaran akan dipindahkan ke daftar Deal.' :
            'Status penawaran akan berubah menjadi ditolak.';

        statusProceed.classList.remove('btn-success', 'btn-danger');
        statusProceed.classList.add(isApprove ? 'btn-success' : 'btn-danger');
        statusProceed.innerHTML = isApprove ?
            '<i class="fas fa-check me-1" aria-hidden="true"></i>Ya, Setujui' :
            '<i class="fas fa-times me-1" aria-hidden="true"></i>Ya, Tolak';

        statusIcon.textContent = isApprove ? '✔' : '!';
        statusIcon.classList.remove('is-approve', 'is-reject');
        statusIcon.classList.add(isApprove ? 'is-approve' : 'is-reject');

        if (isApprove) {
            statusRejectCommentWrap.hidden = true;
            statusRejectComment.value = '';
            statusProceed.disabled = false;
        } else {
            statusRejectCommentWrap.hidden = false;
            statusRejectComment.value = '';
            statusProceed.disabled = true;
            setTimeout(() => statusRejectComment.focus(), 0);
        }

        statusModal.classList.add('is-open');
        statusModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('status-modal-open');
    };

    const closeStatusModal = () => {
        pendingStatusForm = null;
        pendingStatusValue = null;
        statusRejectComment.value = '';
        statusRejectCommentWrap.hidden = true;
        statusProceed.disabled = false;
        statusModal.classList.remove('is-open');
        statusModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('status-modal-open');
    };

    statusRejectComment.addEventListener('input', () => {
        if (pendingStatusValue === 'Ditolak') {
            statusProceed.disabled = statusRejectComment.value.trim().length === 0;
        }
    });

    // Attach listeners to specific containers only (not global document)
    // to prevent capturing clicks from page navigation
    const pageCard = document.querySelector('.penawaran-page-card');
    const modalsContainer = document.querySelectorAll('.status-confirm-modal');

    if (pageCard) {
        pageCard.addEventListener('click', (event) => {
            const trigger = event.target.closest('.js-status-confirm');
            if (trigger) {
                event.preventDefault();
                const activeStatus = trigger.closest('.penawaran-status-hover');
                if (activeStatus) {
                    activeStatus.classList.remove('is-open');
                }
                openStatusModal({
                    status: trigger.dataset.status,
                    form: trigger.closest('form')
                });
                return;
            }

            const reasonTrigger = event.target.closest('.js-open-reject-reason');
            if (reasonTrigger) {
                const projectName = reasonTrigger.dataset.project || 'Proyek';
                const reasonText = (reasonTrigger.dataset.reason || '').trim();
                rejectReasonProject.textContent = `Proyek: ${projectName}`;
                rejectReasonText.textContent = reasonText || 'Tidak ada alasan penolakan.';
                rejectReasonModal.classList.add('is-open');
                rejectReasonModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('status-modal-open');
                return;
            }

            const statusHover = event.target.closest('.penawaran-status-hover');
            if (statusHover) {
                if (event.target.closest('.penawaran-status-actions')) {
                    return;
                }

                const isOpen = statusHover.classList.contains('is-open');
                pageCard.querySelectorAll('.penawaran-status-hover.is-open').forEach((item) => {
                    item.classList.remove('is-open');
                });

                if (!isOpen) {
                    statusHover.classList.add('is-open');
                }
                return;
            }

            pageCard.querySelectorAll('.penawaran-status-hover.is-open').forEach((item) => {
                item.classList.remove('is-open');
            });
        });
    }

    // Modal close buttons on the modals themselves
    modalsContainer.forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target.closest('[data-modal-close="true"]')) {
                closeStatusModal();
                return;
            }
            if (event.target.closest('[data-reason-close="true"]')) {
                rejectReasonModal.classList.remove('is-open');
                rejectReasonModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('status-modal-open');
                return;
            }
        });
    });

    statusProceed.addEventListener('click', () => {
        if (pendingStatusForm) {
            if (pendingStatusValue === 'Ditolak') {
                const noteValue = statusRejectComment.value.trim();
                if (!noteValue) {
                    statusRejectComment.focus();
                    return;
                }

                let noteInput = pendingStatusForm.querySelector('.js-reject-note');
                if (!noteInput) {
                    noteInput = document.createElement('input');
                    noteInput.type = 'hidden';
                    noteInput.name = 'catatan_penolakan';
                    noteInput.className = 'js-reject-note';
                    pendingStatusForm.appendChild(noteInput);
                }
                noteInput.value = noteValue;
            }
            pendingStatusForm.submit();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && statusModal.classList.contains('is-open')) {
            closeStatusModal();
        }

        if (event.key === 'Escape' && rejectReasonModal.classList.contains('is-open')) {
            rejectReasonModal.classList.remove('is-open');
            rejectReasonModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('status-modal-open');
        }
    });
</script>

@push('styles')
<style>
    .penawaran-page-card {
        border: 1px solid #dce4ef;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(36, 58, 87, 0.08);
    }

    .penawaran-header {
        background: #f8fafd;
        border-bottom: 1px solid #e7edf6;
        padding: 0.55rem 0.75rem;
    }

    .penawaran-title {
        font-size: 1.85rem;
        font-weight: 700;
        color: #243d5e;
        line-height: 1.1;
    }

    .penawaran-subtitle {
        display: none;
    }

    .penawaran-filter-form {
        flex: 1 1 auto;
        margin-left: 1rem;
    }

    .penawaran-filter-controls {
        justify-content: flex-end;
        gap: 0.45rem;
    }

    .penawaran-search-wrap {
        position: relative;
        width: min(100%, 250px);
    }

    .penawaran-search-input {
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

    .penawaran-search-input:focus {
        outline: none;
        background: #fff;
        box-shadow: 0 0 0 2px rgba(131, 145, 176, 0.22);
    }

    .penawaran-search-button {
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

    .penawaran-search-button:hover,
    .penawaran-search-button:focus {
        color: #4d627f;
    }

    .penawaran-search-button:focus-visible {
        outline: none;
        box-shadow: 0 0 0 0.12rem rgba(131, 145, 176, 0.22);
    }

    @media (max-width: 576px) {
        .penawaran-filter-form {
            width: 100%;
            margin-left: 0;
        }

        .penawaran-filter-controls {
            justify-content: flex-start;
        }

        .penawaran-search-wrap {
            width: 100%;
        }
    }

    #penawaranDate {
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

    #penawaranDate:hover {
        background: #dce0e8 !important;
        border-color: #c9d0db !important;
        color: #3f4a60 !important;
    }

    #penawaranDate:focus {
        outline: none;
        box-shadow: 0 0 0 0.12rem rgba(74, 104, 214, 0.18);
    }

    #penawaranDate .date-button-text {
        white-space: nowrap;
    }

    .penawaran-date-clear-btn {
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

    .penawaran-date-clear-btn:hover {
        background: #f4f7fb;
        color: #4b607c;
    }

    .penawaran-add-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.38rem !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        border: 1px solid #b9e5c3 !important;
        border-radius: 8px !important;
        background: #d8f2e4 !important;
        color: #3d9663 !important;
        font-size: 0.92rem !important;
        font-weight: 500 !important;
        padding: 0.42rem 0.72rem !important;
        text-decoration: none !important;
        box-shadow: none !important;
    }

    .penawaran-add-btn:hover {
        background: #c8edd9 !important;
        border-color: #a8ddb3 !important;
        color: #347f55 !important;
        box-shadow: none !important;
    }

    .penawaran-table-wrap {
        border-top: 1px solid #edf2f8;
    }

    .penawaran-table thead th {
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

    .penawaran-table thead th+th {
        border-left: 1px solid #e8eef7;
    }

    .penawaran-table tbody td {
        padding: 0.9rem 0.9rem;
        border-color: #edf2f8;
        color: #3a5373;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .penawaran-col-nama {
        width: 34%;
    }

    .penawaran-col-status {
        width: 20%;
    }

    .penawaran-col-mitra {
        width: 20%;
    }

    .penawaran-col-kelola {
        width: 26%;
    }

    .penawaran-table thead th:nth-child(2),
    .penawaran-table tbody td:nth-child(2) {
        white-space: nowrap;
        text-align: center;
    }

    .penawaran-table tbody td:last-child {
        text-align: center;
    }

    .penawaran-table tbody tr:hover {
        background: #fafcff;
    }

    .penawaran-project-name {
        font-size: inherit;
        font-weight: 400;
        color: inherit;
        line-height: 1.2;
    }

    .penawaran-project-date {
        font-size: 0.82rem;
        color: #9aaabe !important;
    }

    .penawaran-status-badge {
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

    .penawaran-status-pill {
        border: 0;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.72rem;
        min-width: 74px;
        line-height: 1.5;
        letter-spacing: normal;
    }

    .penawaran-status-badge.is-waiting {
        background: #fff4d8;
        color: #9a6b00;
        min-width: 170px;
    }

    .penawaran-status-badge.is-approved {
        background: #e5f7ea;
        border-color: #b9e5c3;
        color: #2c7a42;
    }

    .penawaran-status-badge.is-rejected {
        background: #f4dddd;
        color: #d55e5e;
        min-width: 170px;
    }

    .penawaran-status-badge.is-neutral {
        background: #eef2f7;
        border-color: #dbe4ef;
        color: #5c6d84;
    }

    .penawaran-status-hover {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 170px;
        outline: none;
    }

    .penawaran-status-hover .penawaran-status-badge {
        transition: opacity 0.15s ease;
    }

    .penawaran-status-actions {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: nowrap;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%) scale(0.98);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.15s ease, transform 0.15s ease, visibility 0.15s ease;
    }

    .penawaran-status-hover.is-open .penawaran-status-actions {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translate(-50%, -50%) scale(1);
    }

    .penawaran-status-hover.is-open .penawaran-status-badge {
        opacity: 0;
    }

    .penawaran-status-hover {
        cursor: pointer;
    }

    .penawaran-status-form {
        margin: 0;
    }

    .penawaran-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .penawaran-actions.is-single .penawaran-action-btn {
        min-width: 152px;
    }

    .penawaran-actions.is-rejected-row {
        gap: 0.3rem;
    }

    .penawaran-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.78rem;
        margin-right: 0.24rem;
        white-space: nowrap;
        box-shadow: none;
        opacity: 1;
        transition: background-color 0.15s ease, color 0.15s ease, transform 0.15s ease;
        -webkit-tap-highlight-color: transparent;
        -webkit-appearance: none;
        appearance: none;
        line-height: 1.5;
    }

    .penawaran-action-btn:hover,
    .penawaran-action-btn:focus,
    .penawaran-action-btn:active {
        opacity: 1;
        transform: translateY(-1px);
        box-shadow: none;
    }

    .penawaran-action-btn-primary {
        border: 0 !important;
        background: #d9dcf8 !important;
        color: #4a68d6 !important;
    }

    .penawaran-action-btn-primary:hover,
    .penawaran-action-btn-primary:focus,
    .penawaran-action-btn-primary:active {
        background: #4a68d6 !important;
        color: #ffffff !important;
    }

    .penawaran-action-btn-danger {
        border-color: #e6c0c0;
        background: #f4dddd;
        color: #d55e5e;
    }

    .penawaran-action-btn-danger:hover,
    .penawaran-action-btn-danger:focus,
    .penawaran-action-btn-danger:active {
        background: #f8cece;
        color: #ce4a4a;
    }

    .penawaran-action-btn-approved {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        border-radius: 999px;
        border: 1px solid #b9e5c3;
        background: #d8f2e4;
        color: #3d9663;
        padding: 0.34rem 0.78rem;
        margin-right: 0.24rem;
        white-space: nowrap;
        box-shadow: none;
        line-height: 1.5;
    }

    .penawaran-action-btn-approved:hover,
    .penawaran-action-btn-approved:focus,
    .penawaran-action-btn-approved:active {
        background: #c8edd9;
        color: #3d9663;
    }

    .penawaran-action-btn-rejected {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        border-radius: 999px;
        border: 1px solid #e6c0c0;
        background: #f4dddd;
        color: #d55e5e;
        padding: 0.34rem 0.78rem;
        margin-right: 0.24rem;
        white-space: nowrap;
        box-shadow: none;
        line-height: 1.5;
    }

    .penawaran-action-btn-rejected:hover,
    .penawaran-action-btn-rejected:focus,
    .penawaran-action-btn-rejected:active {
        background: #f8cece;
        color: #ce4a4a;
    }

    .penawaran-action-btn-warning {
        border-color: #e6d3a8;
        background: #fff4d8;
        color: #9a6b00;
        min-width: 88px;
    }

    .penawaran-action-btn-warning:hover,
    .penawaran-action-btn-warning:focus,
    .penawaran-action-btn-warning:active {
        background: #ffedbf;
        color: #875c00;
    }

    @media (hover: none) {
        .penawaran-status-hover .penawaran-status-actions {
            position: static;
            transform: none;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .penawaran-status-hover .penawaran-status-badge {
            display: none;
        }
    }

    .penawaran-empty-state {
        padding: 2rem 1rem;
    }

    body.status-modal-open {
        overflow: hidden;
    }

    .status-confirm-modal {
        position: fixed;
        inset: 0;
        z-index: 1200;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .status-confirm-modal.is-open {
        display: flex !important;
        opacity: 1;
        pointer-events: auto;
    }

    .status-confirm-modal__overlay {
        position: absolute;
        inset: 0;
        background: rgba(40, 55, 77, 0.26);
    }

    .status-confirm-modal__dialog {
        position: relative;
        width: min(96vw, 560px);
        margin: 0;
        max-height: calc(100vh - 2rem);
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(36, 58, 87, 0.2);
        border: 1px solid #dce4ef;
        overflow-y: auto;
        transform: translateY(8px) scale(0.98);
        transition: transform 0.2s ease;
    }

    .status-confirm-modal__dialog-reason {
        width: min(96vw, 500px);
        border-color: #dce4ef;
        box-shadow: 0 10px 24px rgba(36, 58, 87, 0.18);
    }

    .status-confirm-modal.is-open .status-confirm-modal__dialog {
        transform: translateY(0) scale(1);
    }

    .status-confirm-modal__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #e7edf6;
        background: #f8fafd;
    }

    .status-confirm-modal__title {
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 1.02rem;
        font-weight: 700;
        color: #2d4566;
    }

    .status-confirm-modal__icon {
        width: 1.35rem;
        height: 1.35rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        line-height: 1;
        font-weight: 800;
    }

    .status-confirm-modal__icon.is-approve {
        background: #d8f2e4;
        color: #2f8f62;
    }

    .status-confirm-modal__icon.is-reject {
        background: #f4dddd;
        color: #ce4a4a;
    }

    .status-confirm-modal__close {
        border: 0;
        background: transparent;
        color: #7e8ea4;
        font-size: 2rem;
        line-height: 1;
        width: 2rem;
        height: 2rem;
        padding: 0;
        border-radius: 6px;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .status-confirm-modal__close:hover {
        background: #eaf0f7;
        color: #4d627f;
    }

    .status-confirm-modal__body {
        text-align: center;
        padding: 1.5rem 1.2rem 1.2rem;
    }

    .status-confirm-modal__text {
        margin: 0;
        color: #3a5373;
        font-size: 1.03rem;
        font-weight: 500;
    }

    .status-confirm-modal__subtext {
        margin: 0.65rem 0 0;
        color: #7f8ea3;
        font-size: 0.93rem;
    }

    .status-confirm-modal__body-reason {
        text-align: left;
        padding: 1rem 1rem 0.9rem;
        background: #ffffff;
    }

    .status-confirm-modal__subtext-reason {
        margin: 0 0 0.6rem;
        font-size: 0.82rem;
        color: #637997;
        font-weight: 600;
        letter-spacing: 0.01em;
    }

    .status-confirm-modal__reason-box {
        border: 1px solid #dfe7f2;
        border-radius: 8px;
        background: #f9fbfd;
        color: #3a5373;
        padding: 0.78rem 0.85rem;
        font-size: 0.85rem;
        line-height: 1.5;
        white-space: pre-wrap;
        word-break: break-word;
        min-height: 56px;
    }

    .status-confirm-modal__comment-wrap {
        margin-top: 0.85rem;
        text-align: left;
    }

    .status-confirm-modal__comment-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: #5f728a;
    }

    .status-confirm-modal__comment-input {
        width: 100%;
        border: 1px solid #dfe7f1;
        border-radius: 6px;
        padding: 0.46rem 0.6rem;
        font-size: 0.84rem;
        color: #2f4360;
        background: #f9fbfe;
        resize: none;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .status-confirm-modal__comment-input:focus {
        border-color: #9fb4d0;
        box-shadow: 0 0 0 0.14rem rgba(131, 145, 176, 0.2);
    }

    .status-confirm-modal__footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        padding: 0.8rem 1rem;
        border-top: 1px solid #e7edf6;
        background: #f8fafd;
    }

    .status-confirm-modal__footer .btn {
        border: 0;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.78rem;
        min-width: 88px;
        box-shadow: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        line-height: 1.5;
        transition: transform 0.15s ease, background-color 0.15s ease, color 0.15s ease;
    }

    .status-confirm-modal__footer .btn:hover,
    .status-confirm-modal__footer .btn:focus {
        transform: translateY(-1px);
    }

    .status-confirm-modal__footer .btn-secondary {
        border: 0 !important;
        background: #e2e8f0 !important;
        color: #475569 !important;
    }

    .status-confirm-modal__footer .btn-secondary:hover,
    .status-confirm-modal__footer .btn-secondary:focus {
        background: #475569 !important;
        color: #ffffff !important;
    }

    .status-confirm-modal__footer .btn-success {
        border: 0 !important;
        background: #d8f2e4 !important;
        color: #2c7a42 !important;
    }

    .status-confirm-modal__footer .btn-success:hover,
    .status-confirm-modal__footer .btn-success:focus {
        background: #2c7a42 !important;
        color: #ffffff !important;
    }

    .status-confirm-modal__footer .btn-danger {
        border: 0 !important;
        background: #f4dddd !important;
        color: #d55e5e !important;
    }

    .status-confirm-modal__footer .btn-danger:hover,
    .status-confirm-modal__footer .btn-danger:focus {
        background: #d55e5e !important;
        color: #ffffff !important;
    }

    .penawaran-pagination-wrap {
        padding: 0.2rem 0 0.35rem;
    }

    .penawaran-pagination-wrap .dashboard-pagination .page-link {
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

    .penawaran-pagination-wrap .dashboard-pagination .page-item.active .page-link {
        background: #007375;
        border-color: #007375;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0, 115, 117, 0.32);
    }

    .penawaran-pagination-wrap .dashboard-pagination .page-item.disabled .page-link {
        color: #a3b2c4;
        background: #f6f8fb;
    }
</style>
@endpush
@endsection