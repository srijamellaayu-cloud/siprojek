@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm project-show-card h-100" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;">
            <div class="card-body project-show-card-body p-4">
                <div class="mb-4">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Nama Proyek</span>
                    <div style="font-size: 1rem; font-weight: 700; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $penawaran->nama_proyek }}</div>
                </div>

                <div class="mb-4">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Nomor Surat Penawaran</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $penawaran->nomor_surat ?: '-' }}</div>
                </div>

                <div class="mb-4">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Mitra</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $penawaran->mitra }}</div>
                </div>

                <div class="mb-4">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Biaya Penawaran</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Rp {{ number_format($penawaran->biaya_penawaran, 0, ',', '.') }}</div>
                </div>

                <div class="mb-0">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;">Durasi Proyek</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #1e293b; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">{{ $penawaran->durasi_proyek ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm project-show-card h-100" style="border-radius: 12px; border: 1px solid #dce4ef; box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;">
            <div class="card-body project-show-card-body p-4 d-flex flex-column">
                <div class="mb-4">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Dokumen Penawaran (.pdf)</span>
                    @if($penawaran->dokumen)
                    <div class="d-flex align-items-center p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; gap: 1rem;">
                        <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 40px; height: 40px; min-width: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="20" height="20" fill="currentColor">
                                <path d="M181.9 256.1c-5-16.4-24.2-59.8-15.4-105.5c12.2-11.5 29.8-11.5 42 0c8.8 45.7-10.4 89.1-15.4 105.5c-3.5 11.3-9.5 25.4-11.2 36.3c-1.8-10.9-7.8-25-11.2-36.3zM369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zM332.1 128H256V51.9l76.1 76.1zM48 464V48h160v104c0 13.3 10.7 24 24 24h104v288H48z"/>
                            </svg>
                        </div>
                        <div class="flex-grow-1 overflow-hidden" style="line-height: 1.3;">
                            <div class="text-truncate" style="font-weight: 600; font-size: 0.86rem; color: #334155;">{{ basename($penawaran->dokumen) }}</div>
                        </div>
                        <div>
                            <a href="{{ asset('storage/' . $penawaran->dokumen) }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius: 999px; font-weight: 600; padding: 0.25rem 0.75rem; border: 1px solid #cbd5e1 !important; color: #475569;" onmouseover="this.style.background='#475569'; this.style.color='#ffffff';" onmouseout="this.style.background='none'; this.style.color='#475569';">
                                <i class="fas fa-external-link-alt me-1"></i> Buka
                            </a>
                        </div>
                    </div>
                    @else
                    <div style="font-size: 0.86rem; color: #64748b; font-style: italic; padding: 0.55rem 0.85rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">Tidak ada dokumen terlampir</div>
                    @endif
                </div>

                <div class="mb-0 flex-grow-1 d-flex flex-column">
                    <span class="project-show-label" style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Deskripsi Proyek</span>
                    <div class="flex-grow-1 p-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #334155; font-size: 0.88rem; line-height: 1.5; min-height: 250px; white-space: pre-wrap;">{{ $penawaran->deskripsi ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center gap-2 mt-4 project-show-actions">
    <a href="{{ route('administrasi.penawaran.index') }}" class="btn app-chip project-show-cancel-btn">Keluar</a>
    <a href="{{ route('administrasi.penawaran.invoice', $penawaran->id) }}" target="_blank" rel="noopener" class="btn app-chip project-show-invoice-btn">Laporan</a>
</div>

<style>
    .project-show-page {
        background: #e9edf4;
        border-radius: 12px;
        padding: 0.6rem;
    }

    .project-show-card {
        border: 1px solid #dce4ef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;
    }

    .project-show-card-body {
        padding: 1.2rem;
    }

    .project-show-cancel-btn,
    .project-show-invoice-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.34rem 0.78rem;
        border: 0 !important;
        white-space: nowrap;
        box-shadow: none;
        line-height: 1.5;
    }

    .project-show-cancel-btn {
        background: #e2e8f0 !important;
        color: #475569 !important;
        transition: all 0.15s ease;
    }

    .project-show-invoice-btn {
        background: #d8f2e4 !important;
        color: #2c7a42 !important;
        transition: all 0.15s ease;
    }

    .project-show-cancel-btn:hover {
        background: #475569 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .project-show-invoice-btn:hover {
        background: #2c7a42 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
</style>
@endsection