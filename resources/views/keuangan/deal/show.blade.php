@extends('layouts.app')

@push('navbar_left')
<div id="sticky-project-header" class="d-flex align-items-center" style="opacity: 0; transform: translateY(-10px); transition: all 0.25s ease-in-out; pointer-events: none;">
    <span class="text-truncate sticky-project-title" style="font-weight: 700; font-size: 0.95rem; color: #1e293b; max-width: 800px; display: inline-block;" title="{{ $deal->nama_proyek }}">
        {{ \Illuminate\Support\Str::limit($deal->nama_proyek, 100) }}
    </span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stickyHeader = document.getElementById('sticky-project-header');
        if (stickyHeader) {
            const handleScroll = function() {
                if (window.scrollY > 80) {
                    stickyHeader.style.opacity = '1';
                    stickyHeader.style.transform = 'translateY(0)';
                    stickyHeader.style.pointerEvents = 'auto';
                } else {
                    stickyHeader.style.opacity = '0';
                    stickyHeader.style.transform = 'translateY(-10px)';
                    stickyHeader.style.pointerEvents = 'none';
                }
            };
            window.addEventListener('scroll', handleScroll);
            handleScroll();
        }
    });
</script>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm deal-block-card h-100" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;">
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Nama Proyek</span>
                    <div style="font-size: 1rem; font-weight: 700; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $deal->nama_proyek }}</div>
                </div>

                <div class="mb-3">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Nomor Surat Penawaran</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $deal->nomor_surat ?: '-' }}</div>
                </div>

                <div class="mb-3">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Tanggal Proyek</span>
                    <div class="d-flex align-items-center gap-2 p-2 bg-light rounded" style="border: 1px solid #e2e8f0; background: #f8fafc !important; border-radius: 8px !important; padding: 0.55rem 0.85rem !important;">
                        <span style="font-weight: 600; color: #334155;">{{ $deal->tanggal_mulai ? $deal->tanggal_mulai->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                        <span class="text-muted px-1">s/d</span>
                        <span style="font-weight: 600; color: #334155;">{{ $deal->tanggal_selesai ? $deal->tanggal_selesai->locale('id')->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Nomor Kontrak</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $deal->nomor_kontrak ?: '-' }}</div>
                </div>

                <div class="mb-3">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Mitra</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $deal->mitra }}</div>
                </div>

                <div class="mb-3">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Biaya Penawaran</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Rp {{ number_format($deal->biaya_penawaran ?? 0, 0, ',', '.') }}</div>
                </div>

                <div class="mb-0">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Durasi Proyek</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $deal->durasi_proyek ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm deal-block-card h-100" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;">
            <div class="card-body p-4 d-flex flex-column">
                <div class="mb-4">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Dokumen Penawaran (.pdf)</span>
                    @if($deal->dokumen)
                    <div class="d-flex align-items-center p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; gap: 1rem;">
                        <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 40px; height: 40px; min-width: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20" fill="currentColor">
                                <path d="M181.9 256.1c-5-16.4-24.2-59.8-15.4-105.5c12.2-11.5 29.8-11.5 42 0c8.8 45.7-10.4 89.1-15.4 105.5c-3.5 11.3-9.5 25.4-11.2 36.3c-1.8-10.9-7.8-25-11.2-36.3zM369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zM332.1 128H256V51.9l76.1 76.1zM48 464V48h160v104c0 13.3 10.7 24 24 24h104v288H48z"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1 overflow-hidden" style="line-height: 1.3;">
                            <div class="text-truncate" style="font-weight: 600; font-size: 0.86rem; color: #334155;">{{ basename($deal->dokumen) }}</div>
                        </div>
                        <div>
                            <a href="{{ asset('storage/' . $deal->dokumen) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius: 999px; font-weight: 600; padding: 0.25rem 0.75rem; border: 1px solid #cbd5e1 !important; color: #475569;" onmouseover="this.style.background='#475569'; this.style.color='#ffffff';" onmouseout="this.style.background='none'; this.style.color='#475569';">
                                <i class="fas fa-external-link-alt me-1"></i> Buka
                            </a>
                        </div>
                    </div>
                    @else
                    <div style="font-size: 0.86rem; color: #64748b; font-style: italic; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Tidak ada dokumen terlampir</div>
                    @endif
                </div>

                <div class="mb-4">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Dokumen Kontrak (.pdf)</span>
                    @if($deal->dokumen_kontrak)
                    <div class="d-flex align-items-center p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; gap: 1rem;">
                        <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 40px; height: 40px; min-width: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20" fill="currentColor">
                                <path d="M181.9 256.1c-5-16.4-24.2-59.8-15.4-105.5c12.2-11.5 29.8-11.5 42 0c8.8 45.7-10.4 89.1-15.4 105.5c-3.5 11.3-9.5 25.4-11.2 36.3c-1.8-10.9-7.8-25-11.2-36.3zM369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zM332.1 128H256V51.9l76.1 76.1zM48 464V48h160v104c0 13.3 10.7 24 24 24h104v288H48z"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1 overflow-hidden" style="line-height: 1.3;">
                            <div class="text-truncate" style="font-weight: 600; font-size: 0.86rem; color: #334155;">{{ basename($deal->dokumen_kontrak) }}</div>
                        </div>
                        <div>
                            <a href="{{ asset('storage/' . $deal->dokumen_kontrak) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius: 999px; font-weight: 600; padding: 0.25rem 0.75rem; border: 1px solid #cbd5e1 !important; color: #475569;" onmouseover="this.style.background='#475569'; this.style.color='#ffffff';" onmouseout="this.style.background='none'; this.style.color='#475569';">
                                <i class="fas fa-external-link-alt me-1"></i> Buka
                            </a>
                        </div>
                    </div>
                    @else
                    <div style="font-size: 0.86rem; color: #64748b; font-style: italic; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Tidak ada dokumen terlampir</div>
                    @endif
                </div>

                <div class="mb-0 flex-grow-1 d-flex flex-column">
                    <span class="deal-field-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Deskripsi Proyek</span>
                    <div class="flex-grow-1 p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #334155; font-size: 0.88rem; line-height: 1.5; min-height: 120px; white-space: pre-wrap;">{{ $deal->deskripsi ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $progressDetails = $deal->progress_details;
@endphp
<div class="card shadow-sm mt-3" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="font-weight: 700; color: #2d4566;">Progress Proyek</h5>
            <span class="badge" style="background: {{ $progressDetails['final'] >= 100 ? '#d8f2e4' : ($progressDetails['final'] > 0 ? '#fff8e8' : '#f3f4f7') }}; color: {{ $progressDetails['final'] >= 100 ? '#2c7a42' : ($progressDetails['final'] > 0 ? '#9b7000' : '#6f7d90') }}; font-weight: 700; font-size: 0.9rem; padding: 0.35rem 0.75rem; border-radius: 8px;">
                {{ $progressDetails['final'] }}%
            </span>
        </div>

        <div class="progress mb-4" style="height: 12px; border-radius: 999px; background: rgba(51, 79, 113, 0.08); overflow: hidden;">
            <div class="progress-bar" role="progressbar" 
                 style="width: {{ $progressDetails['final'] }}%; 
                        background: {{ $progressDetails['final'] >= 100 
                                      ? 'linear-gradient(90deg, #37c964 0%, #2bb454 100%)' 
                                      : ($progressDetails['final'] > 0 
                                         ? 'linear-gradient(90deg, #f8c43b 0%, #f0ad17 100%)' 
                                         : '#9aa8bb') }}; 
                        transition: width 0.4s ease;" 
                 aria-valuenow="{{ $progressDetails['final'] }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="p-3 rounded text-center" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <span style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Progress Dasar</span>
                    <span style="font-size: 1.25rem; font-weight: 700; color: #334155;">{{ $progressDetails['base'] }}%</span>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-3 rounded text-center" style="background: #fdf2f2; border: 1px solid #fde2e2;">
                    <span style="font-size: 0.78rem; font-weight: 700; color: #b91c1c; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Penalti Keterlambatan</span>
                    <span style="font-size: 1.25rem; font-weight: 700; color: #b91c1c;">-{{ $progressDetails['penalty'] }}%</span>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-3 rounded text-center" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                    <span style="font-size: 0.78rem; font-weight: 700; color: #15803d; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Progress Akhir</span>
                    <span style="font-size: 1.25rem; font-weight: 700; color: #15803d;">{{ $progressDetails['final'] }}%</span>
                </div>
            </div>
        </div>

        @if($progressDetails['penalty'] > 0)
        <div class="mt-4 p-3 rounded" style="background: #fff5f5; border: 1px solid #fed7d7;">
            <h6 style="font-weight: 700; color: #9b2c2c; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;">
                <i class="fas fa-exclamation-triangle"></i> Detail Penalti Keterlambatan
            </h6>
            <ul class="mb-0 ps-3" style="color: #c53030; font-size: 0.88rem; line-height: 1.6;">
                @foreach($deal->tasks as $task)
                    @if($task->status !== 'Done' && $task->days_left !== null && $task->days_left < 0)
                        @php
                            $daysLate = abs($task->days_left);
                            $weight = 100 / max(1, $deal->tasks->count());
                            $penaltyForThisTask = $weight * min($daysLate * 0.02, 0.5);
                        @endphp
                        <li>
                            Tugas <strong>"{{ $task->nama_tugas }}"</strong> terlambat <strong>{{ $daysLate }} hari</strong> (Penalti: -{{ round($penaltyForThisTask, 2) }}%)
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

<div class="card deal-task-card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 deal-task-header">
        <h5 class="mb-0 deal-task-title">Tugas Proyek</h5>

        <form id="taskFilterForm" method="GET" action="{{ route('keuangan.deal.show', $deal->id) }}" class="d-flex align-items-center justify-content-end gap-2 flex-wrap ms-auto" aria-label="Filter tugas proyek">
            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

            <div class="position-relative date-filter-container" id="taskDateContainer">
                <button type="button" id="taskDate" class="dashboard-date-button">
                    <i class="far fa-calendar-alt me-2" aria-hidden="true"></i>
                    <span class="date-button-text">
                        {{ request('start_date') && request('end_date')
                                ? \Carbon\Carbon::parse(request('start_date'))->locale('id')->translatedFormat('d M Y').' - '.\Carbon\Carbon::parse(request('end_date'))->locale('id')->translatedFormat('d M Y')
                                : 'Pilih Tanggal' }}
                    </span>
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table mb-0 align-middle deal-task-table">
            <thead>
                <tr>
                    <th>Nama Tugas</th>
                    <th>Anggota</th>
                    <th>Durasi</th>
                    <th>Kelola</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dealTasks as $task)
                <tr>
                    <td>
                        <div class="deal-task-name">{{ $task->nama_tugas }}</div>
                        <small class="deal-task-date">{{ \Carbon\Carbon::parse($task->tanggal_tugas)->translatedFormat('d F Y') }}</small>
                    </td>
                    <td>
                        @php
                        $taskAnggota = $task->anggota;
                        if (is_string($taskAnggota)) {
                        $decoded = json_decode($taskAnggota, true);
                        if (is_array($decoded)) {
                        $taskAnggota = $decoded;
                        } elseif (is_string($decoded)) {
                        $nestedDecoded = json_decode($decoded, true);
                        $taskAnggota = is_array($nestedDecoded) ? $nestedDecoded : [];
                        } else {
                        $taskAnggota = [];
                        }
                        }
                        $taskAnggota = is_array($taskAnggota) ? $taskAnggota : [];
                        @endphp
                        <div class="deal-multiline-text deal-scrollable-text">{{ !empty($taskAnggota) ? implode(', ', $taskAnggota) : '-' }}</div>
                    </td>
                    <td>
                        {{ $task->deadline_label ?? '-' }}
                    </td>
                    <td>
                        @php
                            $isInvoicePenagihan = ($task->nama_tugas === 'Invoice Penagihan');
                            $isReadOnly = (!$isInvoicePenagihan || $task->status === 'Done' || $deal->progress >= 100);
                        @endphp
                        @if($isInvoicePenagihan && !$isReadOnly)
                        <button type="button" class="btn btn-sm app-chip deal-action-btn deal-action-btn-danger" onclick="openModalCustom('#updateTaskModal{{ $task->id }}')">
                            Update
                        </button>
                        @else
                        <button type="button" class="btn btn-sm app-chip deal-action-btn deal-action-btn-primary" onclick="openModalCustom('#updateTaskModal{{ $task->id }}')">
                            Detail
                        </button>
                        @endif
                    </td>
                    <td>
                        @php $taskStatus = ($task->status === 'Done') ? 'Done' : 'On Progress'; @endphp
                        <span class="deal-chip {{ $taskStatus === 'Done' ? 'deal-chip-done' : 'deal-chip-update' }}">
                            {{ $taskStatus }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada tugas proyek</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach($dealTasks as $task)
    @php
    $selectedAnggota = $task->anggota;
    if (is_string($selectedAnggota)) {
        $decodedSelectedAnggota = json_decode($selectedAnggota, true);
        if (is_array($decodedSelectedAnggota)) {
            $selectedAnggota = $decodedSelectedAnggota;
        } elseif (is_string($decodedSelectedAnggota)) {
            $nestedDecodedSelectedAnggota = json_decode($decodedSelectedAnggota, true);
            $selectedAnggota = is_array($nestedDecodedSelectedAnggota) ? $nestedDecodedSelectedAnggota : [];
        } else {
            $selectedAnggota = [];
        }
    }
    $selectedAnggota = is_array($selectedAnggota) ? $selectedAnggota : [];
    $isTaskOldInput = old('task_form') === 'update' && (int) old('task_id') === $task->id;
    $selectedAnggotaFromOld = (array) old('anggota', []);
    $effectiveSelectedAnggota = $isTaskOldInput ? $selectedAnggotaFromOld : $selectedAnggota;
    $knownUserNames = $anggotaOptions;
    $otherAnggota = array_values(array_diff($effectiveSelectedAnggota, $knownUserNames));
    $isInvoicePenagihan = ($task->nama_tugas === 'Invoice Penagihan');
    $isReadOnly = (!$isInvoicePenagihan || $task->status === 'Done' || $deal->progress >= 100);
    @endphp
    <div class="modal fade" id="updateTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="updateTaskModalLabel{{ $task->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content deal-modal-content">
                @if(!$isReadOnly)
                <form method="POST" action="{{ route('keuangan.deal.tasks.invoice.update', [$deal->id, $task->id]) }}" enctype="multipart/form-data" class="js-track-changes-form">
                @endif
                    @csrf
                    @method('PATCH')
                    <div class="modal-header deal-modal-header">
                        <h5 class="modal-title" id="updateTaskModalLabel{{ $task->id }}">{{ $isReadOnly ? 'Detail Tugas Proyek' : 'Update Invoice Penagihan' }}</h5>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Tugas</label>
                            <input type="text" class="form-control" value="{{ $task->nama_tugas }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label deal-add-task-label">Nama Anggota</label>
                            <div class="deal-add-task-members-box">
                                @foreach($anggotaOptions as $anggotaName)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="keu_user_{{ $task->id }}_{{ $loop->index }}"
                                        name="anggota[]"
                                        value="{{ $anggotaName }}"
                                        @if(in_array($anggotaName, $effectiveSelectedAnggota)) checked @endif
                                        disabled>
                                    <label class="form-check-label" for="keu_user_{{ $task->id }}_{{ $loop->index }}">
                                        {{ $anggotaName }}
                                    </label>
                                </div>
                                @endforeach

                                <input
                                    type="text"
                                    name="anggota_lainnya"
                                    class="form-control form-control-sm mt-2 deal-add-task-input"
                                    value="{{ implode(', ', $otherAnggota) }}"
                                    placeholder="Others"
                                    readonly
                                    disabled>
                            </div>
                        </div>

                        @if($isInvoicePenagihan)
                        <div class="mb-3">
                            <label for="tanggal_tugas_{{ $task->id }}" class="form-label">Deadline Tugas</label>
                            <input type="date" id="tanggal_tugas_{{ $task->id }}" name="tanggal_tugas" class="form-control" value="{{ $task->tanggal_tugas ? \Carbon\Carbon::parse($task->tanggal_tugas)->format('Y-m-d') : '' }}" required @if($isReadOnly) readonly disabled @endif>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Bank Penagihan</label>
                            @if(!$isReadOnly)
                            <select name="bank_penagihan" class="form-select" required>
                                <option value="" disabled {{ !$task->bank_penagihan ? 'selected' : '' }}>Pilih Bank</option>
                                <option value="Mandiri" {{ $task->bank_penagihan === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BCA" {{ $task->bank_penagihan === 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="BRK" {{ $task->bank_penagihan === 'BRK' ? 'selected' : '' }}>BRK</option>
                            </select>
                            @else
                            <input type="text" class="form-control" value="{{ $task->bank_penagihan ?: 'Belum diisi oleh keuangan' }}" readonly disabled>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumen Invoice</label>
                            @if($task->dokumen_invoice)
                            <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between mb-2">
                                <span class="text-truncate me-2" style="font-size: 0.85rem; font-weight: 600;">{{ basename($task->dokumen_invoice) }}</span>
                                <a href="{{ asset('storage/' . $task->dokumen_invoice) }}" target="_blank" class="btn btn-xs btn-outline-primary" style="font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px;">Lihat Dokumen</a>
                            </div>
                            @endif
                            @if(!$isReadOnly)
                            <input type="file" name="dokumen_invoice" class="form-control" accept=".pdf,.doc,.docx,.xlsx,.png,.jpg,.jpeg">
                            @elseif(!$task->dokumen_invoice)
                            <div class="text-muted" style="font-style: italic; font-size: 0.85rem; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Belum diupload oleh keuangan</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dokumen Faktur Pajak</label>
                            @if($task->dokumen_faktur_pajak)
                            <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between mb-2">
                                <span class="text-truncate me-2" style="font-size: 0.85rem; font-weight: 600;">{{ basename($task->dokumen_faktur_pajak) }}</span>
                                <a href="{{ asset('storage/' . $task->dokumen_faktur_pajak) }}" target="_blank" class="btn btn-xs btn-outline-primary" style="font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px;">Lihat Dokumen</a>
                            </div>
                            @endif
                            @if(!$isReadOnly)
                            <input type="file" name="dokumen_faktur_pajak" class="form-control" accept=".pdf,.doc,.docx,.xlsx,.png,.jpg,.jpeg">
                            @elseif(!$task->dokumen_faktur_pajak)
                            <div class="text-muted" style="font-style: italic; font-size: 0.85rem; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Belum diupload oleh keuangan</div>
                            @endif
                        </div>
                        @else
                        <div class="mb-3">
                            <label class="form-label">Deadline Tugas</label>
                            <input type="date" class="form-control" value="{{ $task->tanggal_tugas ? \Carbon\Carbon::parse($task->tanggal_tugas)->format('Y-m-d') : '' }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" rows="3" readonly disabled>{{ $task->deskripsi ?: '-' }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label d-block">Status</label>
                            @if($task->status === 'Done')
                                <div class="d-inline-flex align-items-center px-3 py-2 rounded-3 border" style="background: #e5f7ea; border-color: #b9e5c3 !important; color: #2c7a42; font-weight: 600; font-size: 0.9rem; gap: 0.5rem;">
                                    <span class="status-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #2c7a42; display: inline-block;"></span>
                                    <span>Done</span>
                                </div>
                            @else
                                <div class="d-inline-flex align-items-center px-3 py-2 rounded-3 border" style="background: #fff8e8; border-color: #ffe082 !important; color: #b7791f; font-weight: 600; font-size: 0.9rem; gap: 0.5rem;">
                                    <span class="status-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #f0ad17; display: inline-block; animation: pulse-yellow 2s infinite;"></span>
                                    <span>On Progress</span>
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm deal-cancel-btn" data-bs-dismiss="modal">{{ $isReadOnly ? 'Tutup' : 'Batal' }}</button>
                        @if(!$isReadOnly)
                        <button type="submit" class="btn btn-sm app-chip deal-action-btn deal-action-btn-danger">Update</button>
                        @endif
                    </div>
                @if(!$isReadOnly)
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div class="card-footer bg-white border-0 pt-2 pb-3">
        <nav aria-label="Tugas proyek pagination">
            <ul class="pagination justify-content-center mb-0 deal-task-pagination">
                @if ($dealTasks->onFirstPage())
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo;</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $dealTasks->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                @foreach ($dealTasks->getUrlRange(1, $dealTasks->lastPage()) as $page => $url)
                @if ($page == $dealTasks->currentPage())
                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach

                @if ($dealTasks->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $dealTasks->nextPageUrl() }}" rel="next">&raquo;</a></li>
                @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
</div>

<div class="d-flex align-items-center gap-2 mt-3">
    <a href="{{ route('keuangan.deal.index') }}" class="btn btn-sm app-chip deal-cancel-btn">Keluar</a>
    <a href="{{ route('keuangan.deal.invoice', $deal->id) }}" target="_blank" rel="noopener" class="btn btn-sm app-chip deal-invoice-bottom-btn">Laporan</a>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const createHiddenPickerInput = (id, containerId) => {
        const container = document.getElementById(containerId);
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
        container.appendChild(input);
        return input;
    };

    const placeCalendarExactlyUnderButton = (instance, buttonId, containerId, offsetX = 0) => {
        const calendar = instance.calendarContainer;
        const button = document.getElementById(buttonId);
        const container = document.getElementById(containerId);

        if (!calendar || !button || !container) {
            return;
        }

        const calendarWidth = calendar.offsetWidth || 0;
        const preferredLeft = button.offsetLeft + button.offsetWidth - calendarWidth + offsetX;
        const safeLeft = Math.max(8, Math.min(preferredLeft, container.clientWidth - calendarWidth - 8));
        const top = button.offsetTop + button.offsetHeight + 6;

        calendar.style.position = 'absolute';
        calendar.style.left = `${safeLeft}px`;
        calendar.style.top = `${top}px`;
        calendar.style.right = 'auto';
        calendar.style.marginTop = '0';
        calendar.style.zIndex = '9999';
    };

    const taskPickerInput = createHiddenPickerInput('taskDateInput', 'taskDateContainer');
    const taskFP = flatpickr(taskPickerInput, {
        mode: 'range',
        dateFormat: 'd M Y',
        appendTo: document.getElementById('taskDateContainer'),
        positionElement: document.getElementById('taskDate'),
        position: 'below left',
        clickOpens: false,
        onOpen: function(selectedDates, dateStr, instance) {
            requestAnimationFrame(() => {
                placeCalendarExactlyUnderButton(instance, 'taskDate', 'taskDateContainer');
            });
        },
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                const startISO = selectedDates[0].toISOString().split('T')[0];
                const endISO = selectedDates[1].toISOString().split('T')[0];

                document.getElementById('start_date').value = startISO;
                document.getElementById('end_date').value = endISO;
                document.getElementById('taskFilterForm').submit();
                instance.close();
            }
        }
    });

    document.getElementById('taskDate').addEventListener('click', () => taskFP.open());

    // Move modals to body to avoid stacking-context and scrolling issues from nested containers.
    document.querySelectorAll('.deal-add-task-modal, [id^="updateTaskModal"]').forEach((modalEl) => {
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
    });

    // Prevent flatpickr overlay from sitting above Bootstrap modals.
    document.querySelectorAll('.modal').forEach((modalEl) => {
        modalEl.addEventListener('show.bs.modal', () => {
            taskFP.close();
        });
    });

    // Define global openModalCustom to bypass any event delegation bindings
    window.openModalCustom = function(targetId) {
        const targetModalEl = document.querySelector(targetId);
        if (!targetModalEl) {
            console.error('Modal element not found:', targetId);
            return;
        }

        console.log('openModalCustom called for:', targetId);
        let success = false;

        // Try Bootstrap 5 Modal API
        if (window.bootstrap && window.bootstrap.Modal) {
            try {
                let modalInstance = bootstrap.Modal.getInstance(targetModalEl);
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(targetModalEl);
                }
                modalInstance.show();
                success = true;
                console.log('Opened via Bootstrap 5 API');
                return;
            } catch (err) {
                console.warn('Bootstrap 5 modal show failed, trying jQuery:', err);
            }
        }

        // Try jQuery Bootstrap 4 Modal API
        if (window.$ && typeof window.$.fn.modal === 'function') {
            try {
                window.$(targetModalEl).modal('show');
                success = true;
                console.log('Opened via jQuery Bootstrap API');
                return;
            } catch (err) {
                console.error('jQuery modal show failed:', err);
            }
        }

        // Absolute fallback: manually toggle CSS visibility if JS libraries are blocked
        if (!success) {
            try {
                targetModalEl.style.display = 'block';
                targetModalEl.classList.add('show');
                targetModalEl.style.backgroundColor = 'rgba(0,0,0,0.5)';
                targetModalEl.style.opacity = '1';
                document.body.classList.add('modal-open');
                success = true;
                console.log('Opened via manual style fallback');
            } catch (err) {
                console.error('Manual style fallback failed:', err);
                alert('Gagal membuka modal. Pustaka bootstrap belum siap.');
            }
        }
    };

    // Close button and click-outside listener
    document.addEventListener('click', (e) => {
        const isCloseButton = e.target.closest('[data-bs-dismiss="modal"]') || e.target.closest('[data-dismiss="modal"]');
        const isModalOverlay = e.target.classList.contains('modal') && e.target.classList.contains('show');
        
        if (isCloseButton || isModalOverlay) {
            const modalEl = isModalOverlay ? e.target : e.target.closest('.modal');
            if (modalEl) {
                let success = false;
                if (window.bootstrap && window.bootstrap.Modal) {
                    try {
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) {
                            modalInstance.hide();
                            success = true;
                        }
                    } catch (err) {}
                }
                if (!success && window.$ && typeof window.$.fn.modal === 'function') {
                    try {
                        window.$(modalEl).modal('hide');
                        success = true;
                    } catch (err) {}
                }
                // Manual fallback close
                modalEl.style.display = 'none';
                modalEl.classList.remove('show');
                modalEl.style.backgroundColor = '';
                document.body.classList.remove('modal-open');
            }
        }
    });

    const initModalFunctionality = () => {
        // Auto-open modal if validation errors exist
        const configNode = document.getElementById('dealTaskConfig');
        const shouldOpenTaskModal = configNode && configNode.dataset.openTaskModal === '1';
        if (shouldOpenTaskModal && !window.autoModalOpened) {
            window.autoModalOpened = true;
            const taskFormType = configNode.dataset.taskForm;
            const taskId = configNode.dataset.taskId;

            let targetModalId = null;
            if (taskFormType === 'update' && taskId) {
                targetModalId = `#updateTaskModal${taskId}`;
            } else {
                targetModalId = '#addTaskModal';
            }

            if (targetModalId) {
                openModalCustom(targetModalId);
            }
        }
    };

    // Run immediately for auto-open logic
    initModalFunctionality();

    // Keep checking if Bootstrap or jQuery becomes available to bind functions
    const bootstrapInterval = setInterval(() => {
        if (window.bootstrap || (window.$ && typeof window.$.fn.modal === 'function')) {
            clearInterval(bootstrapInterval);
            initModalFunctionality();
        }
    }, 50);
</script>

<style>
    .modal {
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }

    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }

    /* Scrollable modal configuration to handle overflows on any screen sizes - stretching fully to the absolute limits */
    .modal-dialog-scrollable {
        display: flex !important;
        flex-direction: column !important;
        height: 100vh !important;
        max-height: 100vh !important;
        margin: 0 auto !important;
    }

    .modal-dialog-scrollable .modal-content {
        height: 100vh !important;
        max-height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
        border-radius: 0 !important;
    }

    .modal-dialog-scrollable .modal-body {
        overflow-y: auto !important;
        max-height: calc(100vh - 120px) !important;
        flex: 1 1 auto !important;
    }

    .modal-dialog-scrollable .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
        background: #ced8e6;
        border-radius: 999px;
    }

    .modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .deal-detail-page {
        background: #eff3f9;
        border: 1px solid #e1e8f2;
        border-radius: 6px;
        padding: 1rem;
    }

    .deal-block-card,
    .deal-task-card {
        border: 1px solid #dce5f0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04);
    }

    .deal-block-card .card-body {
        padding: 1.5rem;
    }

    .deal-field-label {
        display: block;
        font-size: 0.95rem;
        color: #4b5f79;
        font-weight: 600;
        margin-bottom: 0.44rem;
    }

    .deal-field-input,
    .deal-description-box,
    .deal-file-field {
        font-size: 0.92rem;
        border-color: #dfe7f1;
        color: #2f4360;
        background: #f8fafd;
    }

    .deal-field-input {
        height: 42px;
        padding: 0.5rem 0.75rem;
    }

    .deal-file-field {
        height: 42px;
        border: 1px solid #dfe7f1;
        border-radius: 2px;
        padding: 0.62rem 0.75rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .deal-file-link {
        color: #334a6a;
        text-decoration: underline;
    }

    .deal-description-box {
        line-height: 1.6;
        min-height: 400px;
        height: auto !important;
        white-space: pre-wrap;
        word-break: break-word;
        background: #f8fafd;
    }

    .deal-task-header {
        background: #f5f8fc;
        border-bottom: 1px solid #e6edf6;
        padding: 0.8rem 0.95rem;
    }

    .deal-task-title {
        font-size: 1.28rem;
        font-weight: 700;
        color: #2d4566;
    }

    #taskDate {
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

    #taskDate:hover {
        background: #dce0e8 !important;
        border-color: #c9d0db !important;
        color: #3f4a60 !important;
    }

    #taskDate .date-button-text {
        white-space: nowrap;
    }

    .deal-add-task-btn {
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

    .deal-add-task-btn:hover {
        background: #c8edd9 !important;
        border-color: #a8ddb3 !important;
        color: #347f55 !important;
        box-shadow: none !important;
    }

    .deal-task-table thead th {
        font-size: 0.86rem;
        color: #7d8fa5;
        font-weight: 600;
        background: #f9fbfe;
        border-bottom: 1px solid #ebf1f8;
        padding: 0.68rem 0.8rem;
        text-transform: none;
        text-align: center;
    }

    .deal-task-table tbody td {
        font-size: 0.9rem;
        color: #35506f;
        border-color: #edf2f8;
        padding: 0.62rem 0.72rem;
        font-weight: 600;
        vertical-align: top;
    }

    .deal-task-table {
        table-layout: fixed;
        width: 100%;
    }

    .deal-task-table:not(.no-kelola-col) thead th:nth-child(1),
    .deal-task-table:not(.no-kelola-col) tbody td:nth-child(1) {
        width: 25%;
    }

    .deal-task-table:not(.no-kelola-col) thead th:nth-child(2),
    .deal-task-table:not(.no-kelola-col) tbody td:nth-child(2) {
        width: 27%;
    }

    .deal-task-table:not(.no-kelola-col) thead th:nth-child(3),
    .deal-task-table:not(.no-kelola-col) tbody td:nth-child(3) {
        width: 12%;
    }

    .deal-task-table:not(.no-kelola-col) thead th:nth-child(4),
    .deal-task-table:not(.no-kelola-col) tbody td:nth-child(4) {
        width: 12%;
    }

    .deal-task-table:not(.no-kelola-col) thead th:nth-child(5),
    .deal-task-table:not(.no-kelola-col) tbody td:nth-child(5) {
        width: 14%;
    }

    /* Widths when Kelola column is hidden */
    .deal-task-table.no-kelola-col thead th:nth-child(1),
    .deal-task-table.no-kelola-col tbody td:nth-child(1) {
        width: 32%;
    }

    .deal-task-table.no-kelola-col thead th:nth-child(2),
    .deal-task-table.no-kelola-col tbody td:nth-child(2) {
        width: 38%;
    }

    .deal-task-table.no-kelola-col thead th:nth-child(3),
    .deal-task-table.no-kelola-col tbody td:nth-child(3) {
        width: 15%;
    }

    .deal-task-table.no-kelola-col thead th:nth-child(4),
    .deal-task-table.no-kelola-col tbody td:nth-child(4) {
        width: 15%;
    }

    .deal-task-table tbody td:nth-child(3),
    .deal-task-table tbody td:nth-child(4),
    .deal-task-table tbody td:nth-child(5) {
        text-align: center;
        vertical-align: middle;
    }

    .deal-multiline-text {
        white-space: normal;
        overflow-wrap: anywhere;
        word-break: break-word;
        line-height: 1.45;
    }

    .deal-scrollable-text {
        max-height: 4.4em;
        overflow-y: auto;
        padding-right: 0.2rem;
    }

    .deal-scrollable-text::-webkit-scrollbar {
        width: 4px;
    }

    .deal-scrollable-text::-webkit-scrollbar-thumb {
        background: #ced8e6;
        border-radius: 999px;
    }

    .deal-scrollable-text::-webkit-scrollbar-track {
        background: transparent;
    }

    .deal-task-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #2f4667;
        line-height: 1.2;
        margin-bottom: 0.08rem;
    }

    .deal-task-date {
        display: block;
        font-size: 0.76rem;
        color: #a1b0c2;
        line-height: 1.15;
    }

    .deal-chip {
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
        line-height: 1.5;
    }

    .deal-chip-update {
        background: #e2f7f7;
        border-color: #bbf0f0;
        color: #007375;
    }

    .deal-chip-done {
        background: #d8f2e4;
        border-color: #b9e5c3;
        color: #3d9663;
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

    .deal-action-btn:hover,
    .deal-action-btn:focus,
    .deal-action-btn:active {
        opacity: 1;
        transform: translateY(-1px);
        box-shadow: none;
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
        background: #f4dddd;
        color: #d55e5e;
    }

    .deal-action-btn-danger:hover,
    .deal-action-btn-danger:focus,
    .deal-action-btn-danger:active {
        background: #f0d1d1;
        color: #c95353;
    }

    .deal-task-pagination .page-link {
        border: 1px solid #e0e8f3;
        border-radius: 4px;
        margin: 0 2px;
        min-width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #617591;
        font-size: 0.78rem;
        font-weight: 600;
        background: #fff;
        box-shadow: 0 1px 3px rgba(24, 43, 70, 0.14);
    }

    .deal-task-pagination .page-item.active .page-link {
        background: #007375;
        border-color: #007375;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0, 115, 117, 0.32);
    }

    .deal-modal-content {
        border-radius: 12px;
        border: 1px solid #dce5f0;
    }

    .deal-modal-header {
        background: #f5f8fc;
        border-bottom: 1px solid #e6edf6;
    }

    .deal-add-task-dialog {
        max-width: 560px;
    }

    .deal-add-task-content {
        box-shadow: 0 10px 24px rgba(36, 58, 87, 0.18);
    }

    .deal-add-task-header {
        padding: 0.82rem 0.95rem;
        background: #f8fafd;
    }

    .deal-add-task-title {
        font-size: 1.02rem;
        font-weight: 700;
        color: #2d4566;
    }

    .deal-add-task-close {
        opacity: 0.75;
    }

    .deal-add-task-body {
        padding: 0.95rem;
        background: #ffffff;
    }

    .deal-add-task-label {
        font-size: 0.84rem;
        font-weight: 700;
        color: #4b5f79;
        margin-bottom: 0.35rem;
    }

    .deal-add-task-input {
        border: 1px solid #dfe7f1;
        border-radius: 8px;
        background: #f9fbfe;
        color: #2f4360;
        font-size: 0.86rem;
    }

    .deal-add-task-input:focus {
        border-color: #9db3d2;
        box-shadow: 0 0 0 0.14rem rgba(131, 145, 176, 0.2);
        background: #fff;
    }

    .deal-add-task-textarea {
        min-height: 92px;
        resize: vertical;
    }

    .deal-add-task-members-box {
        border: 1px solid #dfe7f1;
        border-radius: 6px;
        background: #f9fbfe;
        padding: 0.7rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.8rem;
        max-height: 280px;
        overflow-y: auto;
        align-content: start;
    }

    .deal-add-task-members-box .form-check {
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .deal-add-task-members-box .form-check-label {
        font-size: 0.84rem;
        color: #425975;
        margin-bottom: 0;
        white-space: normal;
        word-wrap: break-word;
    }

    .deal-add-task-members-box input[type="text"] {
        grid-column: 1 / -1;
        margin-top: 0.5rem !important;
    }

    .deal-add-task-footer {
        border-top: 1px solid #e6edf6;
        background: #f8fafd;
        padding: 0.7rem 0.95rem;
    }

    .deal-cancel-btn,
    .deal-invoice-bottom-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.78rem;
        border: 0 !important;
        box-shadow: none;
        white-space: nowrap;
        line-height: 1.5;
    }

    .deal-cancel-btn {
        background: #e2e8f0 !important;
        color: #475569 !important;
        transition: all 0.15s ease;
    }

    .deal-cancel-btn:hover,
    .deal-cancel-btn:focus,
    .deal-cancel-btn:active {
        background: #475569 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .deal-invoice-bottom-btn {
        background: #d8f2e4 !important;
        color: #2c7a42 !important;
        transition: all 0.15s ease;
    }

    .deal-invoice-bottom-btn:hover,
    .deal-invoice-bottom-btn:focus,
    .deal-invoice-bottom-btn:active {
        background: #2c7a42 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .deal-task-table {
            min-width: 720px;
        }
    }

    /* Ensure Bootstrap modal appears above any custom overlays (fix dark unclickable modal) */
    .modal {
        z-index: 2100 !important;
        pointer-events: auto !important;
    }

    .modal-backdrop {
        z-index: 2090 !important;
        pointer-events: none !important;
    }

    .modal-dialog,
    .modal-content,
    .modal-body,
    .modal-footer,
    .modal-header {
        pointer-events: auto !important;
    }

    /* Specific dialog boost for add-task modal to be safe */
    .deal-add-task-modal .modal-dialog {
        z-index: 2110 !important;
    }

    @keyframes pulse-yellow {
        0% {
            box-shadow: 0 0 0 0 rgba(240, 173, 23, 0.4);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(240, 173, 23, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(240, 173, 23, 0);
        }
    }
</style>
@endsection