@extends('layouts.app')

@section('content')
<form action="{{ route('administrasi.penawaran.store') }}" method="POST" enctype="multipart/form-data">
    @csrf



    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm project-create-card h-100">
                <div class="card-body project-create-card-body">
                    <div class="form-group mb-3">
                        <label class="project-create-label">Nama Proyek</label>
                        <input type="text" name="nama_proyek" class="form-control project-create-input" placeholder="Default input text" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="project-create-label" for="nomor_surat">Nomor Surat Penawaran</label>
                        <div class="project-create-surat-wrap">
                            <input type="text" id="nomor_surat_awal" name="nomor_surat_awal" class="form-control project-create-input project-create-surat-segment project-create-surat-short" maxlength="3" inputmode="numeric" autocomplete="off" placeholder="001">
                            <span class="project-create-surat-separator">/</span>
                            <input type="text" id="nomor_surat_sp" name="nomor_surat_sp" class="form-control project-create-input project-create-surat-segment project-create-surat-sp" maxlength="10" autocomplete="off" placeholder="SP">
                            <span class="project-create-surat-separator">/</span>
                            <span class="project-create-surat-fixed">Solustek</span>
                            <span class="project-create-surat-separator">/</span>
                            <div style="position: relative; flex: 1 1 0%; min-width: 0;">
                                <input type="text" id="nomor_surat_romawi" name="nomor_surat_romawi" class="form-control project-create-input project-create-surat-segment project-create-surat-roman" style="width: 100%; cursor: pointer;" readonly placeholder="IV">
                                <!-- Dropdown Grid Romawi -->
                                <div class="roman-grid-dropdown" id="roman_grid_dropdown" style="display: none;">
                                    <div class="roman-grid">
                                        @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'] as $rom)
                                            <button type="button" class="roman-grid-item" data-value="{{ $rom }}">{{ $rom }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <span class="project-create-surat-separator">/</span>
                            <input type="text" id="nomor_surat_tahun" name="nomor_surat_tahun" class="form-control project-create-input project-create-surat-segment project-create-surat-year" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="2026">
                        </div>
                        <input type="hidden" name="nomor_surat" id="nomor_surat">
                    </div>

                    <div class="form-group mb-3">
                        <label class="project-create-label">Mitra</label>
                        <input type="text" name="mitra" class="form-control project-create-input" placeholder="Default input text" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="project-create-label" for="biaya">Biaya Penawaran</label>
                        <input type="text" class="form-control project-create-input" id="biaya" name="biaya_penawaran" placeholder="Rp Default input text" required>
                    </div>

                    <div class="form-group mb-0">
                        <label class="project-create-label">Durasi Proyek</label>
                        <div class="modern-duration-container">
                            <!-- Stepper Angka (Kiri) -->
                            <div class="modern-stepper">
                                <button type="button" class="stepper-btn" id="btn_minus" aria-label="Kurangi durasi">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="text" id="durasi_angka_val" class="stepper-input" value="1" readonly>
                                <button type="button" class="stepper-btn" id="btn_plus" aria-label="Tambah durasi">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <!-- Segmented Toggle Satuan (Kanan) -->
                            <div class="modern-segmented-control">
                                <button type="button" class="segment-option active" data-unit="Bulan">Bulan</button>
                                <button type="button" class="segment-option" data-unit="Tahun">Tahun</button>
                            </div>

                            <!-- Hidden Input untuk dikirim ke Backend -->
                            <input type="hidden" name="durasi_proyek" id="durasi_proyek" value="1 Bulan" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm project-create-card h-100">
                <div class="card-body project-create-card-body">
                    <div class="form-group mb-3">
                        <label class="project-create-label">Dokumen Penawaran (.pdf)</label>
                        <input type="file" name="dokumen" class="form-control project-create-input project-create-file" required>
                    </div>

                    <div class="form-group mb-0">
                        <label class="project-create-label">Deskripsi Proyek</label>
                        <textarea class="form-control project-create-description" id="deskripsi" name="deskripsi" rows="11" placeholder="Default input text"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 mt-4 project-create-actions">
        <a href="{{ route('administrasi.penawaran.index') }}" class="btn app-chip project-create-cancel">Keluar</a>
        <button type="submit" class="btn app-chip project-create-save">Simpan</button>
    </div>
</form>

<style>
    .project-create-page {
        background: #e9edf4;
        border-radius: 6px;
        padding: 0.6rem;
    }

    .project-create-card {
        border: 1px solid #dce4ef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;
    }

    .project-create-card-body {
        padding: 1.2rem;
    }

    .project-create-label {
        display: block;
        margin-bottom: 0.42rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #314964;
    }

    .project-create-input,
    .project-create-description {
        border: 1px solid #d3dce8;
        border-radius: 8px;
        color: #354e6d;
        font-size: 0.86rem;
        background: #f8fbff;
    }

    .project-create-input {
        height: 45px;
        padding: 0.52rem 0.78rem;
    }

    .project-create-input[type="date"] {
        background: #f8fbff;
        border: 1px solid #d3dce8;
        color: #354e6d;
        font-weight: 600;
        box-shadow: none;
    }

    .project-create-input[type="date"]:focus {
        background: #ffffff;
        border-color: #5f84b6;
        box-shadow: 0 0 0 0.12rem rgba(95, 132, 182, 0.14);
    }

    .project-create-input::placeholder,
    .project-create-description::placeholder {
        color: #9fb0c2;
    }

    .project-create-select {
        padding-right: 2rem;
    }

    .project-create-surat-wrap {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        width: 100%;
        flex-wrap: nowrap !important;
    }

    .project-create-surat-segment {
        height: 45px !important;
        min-width: 0;
        flex: 1 1 0%;
        text-align: center;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        letter-spacing: 0.05em;
        font-weight: 400;
    }

    .project-create-surat-fixed {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 45px !important;
        padding: 0 0.5rem;
        color: #354e6d;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .project-create-surat-separator {
        color: #6f8094;
        font-weight: 400;
        user-select: none;
        flex-shrink: 0;
    }

    .project-create-file {
        padding: 0.38rem 0.52rem;
        height: auto;
    }

    .modern-duration-container {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        width: 100%;
    }

    .modern-stepper {
        display: flex;
        align-items: center;
        background: #f8fbff;
        border-radius: 30px;
        padding: 4px;
        border: 1px solid #cbd5e1;
        height: 45px;
        flex: 1;
        justify-content: space-between;
    }

    .stepper-btn {
        background: #ffffff;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 0;
    }

    .stepper-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .stepper-btn:active {
        transform: scale(0.92);
    }

    .stepper-input {
        border: none;
        background: transparent;
        width: 40px;
        text-align: center;
        font-weight: 700;
        color: #0f172a;
        font-size: 1rem;
        outline: none;
        pointer-events: none;
        user-select: none;
    }

    .modern-segmented-control {
        display: flex;
        background: #f8fbff;
        padding: 4px;
        border-radius: 30px;
        border: 1px solid #cbd5e1;
        height: 45px;
        flex: 1.2;
    }

    .segment-option {
        flex: 1;
        border: none;
        background: transparent;
        border-radius: 30px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .segment-option.active {
        background: #007375;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 115, 117, 0.24);
    }

    /* Roman Grid Dropdown Styles */
    .roman-grid-dropdown {
        position: absolute;
        top: 110%;
        left: 50%;
        transform: translateX(-50%);
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(36, 58, 87, 0.15);
        padding: 0.6rem;
        z-index: 1000;
        width: 190px;
    }

    .roman-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.35rem;
    }

    .roman-grid-item {
        background: #f8fbff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
    }

    .roman-grid-item:hover {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .roman-grid-item.active {
        background: #007375;
        color: #ffffff;
        border-color: #007375;
        box-shadow: 0 2px 4px rgba(0, 115, 117, 0.25);
    }

    .project-create-description {
        resize: none;
        min-height: 300px;
        line-height: 1.4;
        padding: 0.65rem 0.78rem;
    }

    .project-create-actions {
        padding-left: 0.1rem;
    }

    .project-create-cancel,
    .project-create-save {
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

    .project-create-cancel {
        background: #e2e8f0 !important;
        color: #475569 !important;
        transition: all 0.15s ease;
    }

    .project-create-save {
        background: #e2f7f7 !important;
        color: #007375 !important;
        transition: all 0.15s ease;
    }

    .project-create-cancel:hover,
    .project-create-cancel:focus,
    .project-create-cancel:active {
        background: #475569 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .project-create-save:hover,
    .project-create-save:focus,
    .project-create-save:active {
        background: #007375 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }
</style>

<script>
    // Format biaya dengan pemisah ribuan (titik)
    const formatNumber = (value) => {
        return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    const biayaInput = document.getElementById('biaya');
    if (biayaInput) {
        biayaInput.value = formatNumber(biayaInput.value);
        biayaInput.addEventListener('input', function(e) {
            this.value = formatNumber(this.value);
        });
    }

    // Validasi input nomor surat segment
    document.getElementById('nomor_surat_awal').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
    });

    document.getElementById('nomor_surat_sp').addEventListener('input', function(e) {
        let val = this.value.replace(/[^a-zA-Z]/g, '');
        if (val.length > 0) {
            val = val.charAt(0).toUpperCase() + val.slice(1);
        }
        this.value = val;
    });

    document.getElementById('nomor_surat_tahun').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
    });

    const nomorSuratHidden = document.getElementById('nomor_surat');
    const nomorSuratAwal = document.getElementById('nomor_surat_awal');
    const nomorSuratSu = document.getElementById('nomor_surat_sp');
    const nomorSuratRomawi = document.getElementById('nomor_surat_romawi');
    const nomorSuratTahun = document.getElementById('nomor_surat_tahun');

    const buildNomor = (awalRaw, suRaw, romawiRaw, tahunRaw) => {
        const awal = (awalRaw || '').replace(/\D/g, '').slice(0, 3);
        const su = (suRaw || '').trim();
        const romawi = (romawiRaw || '').trim();
        const tahun = (tahunRaw || '').replace(/\D/g, '').slice(0, 4);

        if (!awal && !su && !romawi && !tahun) return '';

        const parts = [];
        if (awal) parts.push(awal);
        if (su) parts.push(su);
        parts.push('Solustek');
        if (romawi) parts.push(romawi);
        if (tahun) parts.push(tahun);

        return parts.join('/');
    };

    const syncNomorSurat = () => {
        nomorSuratHidden.value = buildNomor(nomorSuratAwal?.value, nomorSuratSu?.value, nomorSuratRomawi?.value, nomorSuratTahun?.value);
    };

    [nomorSuratAwal, nomorSuratSu, nomorSuratRomawi, nomorSuratTahun].forEach((input) => {
        if (!input) return;
        input.addEventListener('input', syncNomorSurat);
        input.addEventListener('blur', syncNomorSurat);
    });

    syncNomorSurat();

    // Custom Roman Grid Dropdown logic
    const romanDropdown = document.getElementById('roman_grid_dropdown');
    const romanItems = document.querySelectorAll('.roman-grid-item');

    nomorSuratRomawi.addEventListener('click', function(e) {
        e.stopPropagation();
        romanDropdown.style.display = romanDropdown.style.display === 'none' ? 'block' : 'none';
    });

    romanItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            const val = this.getAttribute('data-value');
            nomorSuratRomawi.value = val;
            
            romanItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            nomorSuratRomawi.dispatchEvent(new Event('input'));
            romanDropdown.style.display = 'none';
        });
    });

    document.addEventListener('click', function(e) {
        if (!romanDropdown.contains(e.target) && e.target !== nomorSuratRomawi) {
            romanDropdown.style.display = 'none';
        }
    });

    const durasiProyekHidden = document.getElementById('durasi_proyek');
    const durasiInput = document.getElementById('durasi_angka_val');
    const btnMinus = document.getElementById('btn_minus');
    const btnPlus = document.getElementById('btn_plus');
    const segmentButtons = document.querySelectorAll('.modern-segmented-control .segment-option');

    let currentVal = parseInt(durasiInput.value) || 1;
    let currentUnit = 'Bulan';

    const updateDurasiVal = () => {
        durasiInput.value = currentVal;
        durasiProyekHidden.value = `${currentVal} ${currentUnit}`;
    };

    btnMinus.addEventListener('click', () => {
        if (currentVal > 1) {
            currentVal--;
            updateDurasiVal();
        }
    });

    btnPlus.addEventListener('click', () => {
        if (currentVal < 12) {
            currentVal++;
            updateDurasiVal();
        }
    });

    segmentButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            segmentButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentUnit = this.getAttribute('data-unit');
            updateDurasiVal();
        });
    });

    updateDurasiVal();

    // ensure hidden value is up-to-date before submit (select the page form)
    const penawaranForm = document.querySelector('form');
    if (penawaranForm) {
        penawaranForm.addEventListener('submit', function() {
            syncNomorSurat();
            updateDurasiVal();
        });
    }
</script>
@endsection