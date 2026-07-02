@extends('layouts.app')

@section('content')
<form action="{{ route('administrasi.penawaran.update', $penawaran->id) }}" method="POST" enctype="multipart/form-data" class="js-track-changes-form">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm project-edit-card h-100">
                <div class="card-body project-edit-card-body">
                    <div class="form-group mb-3">
                        <label class="project-label">Nama Proyek</label>
                        <input type="text" name="nama_proyek" class="form-control project-input" value="{{ $penawaran->nama_proyek }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="project-create-label">Nomor Surat</label>
                        <div class="project-create-surat-wrap">
                            @php
                            $parts = explode('/', $penawaran->nomor_surat ?? '');
                            $awal = $parts[0] ?? '';
                            $sp = $parts[1] ?? '';
                            $romawi = $parts[3] ?? '';
                            $tahun = $parts[4] ?? '';
                            @endphp
                            <input type="text" id="nomor_surat_awal_edit" name="nomor_surat_awal" class="form-control project-create-input project-create-surat-segment project-create-surat-short" maxlength="3" inputmode="numeric" autocomplete="off" placeholder="001" value="{{ $awal }}">
                            <span class="project-create-surat-separator">/</span>
                            <input type="text" id="nomor_surat_sp_edit" name="nomor_surat_sp" class="form-control project-create-input project-create-surat-segment project-create-surat-sp" maxlength="10" autocomplete="off" placeholder="SP" value="{{ $sp }}">
                            <span class="project-create-surat-separator">/</span>
                            <span class="project-create-surat-fixed">Solustek</span>
                            <span class="project-create-surat-separator">/</span>
                            <div style="position: relative; flex: 1 1 0%; min-width: 0;">
                                <input type="text" id="nomor_surat_romawi_edit" name="nomor_surat_romawi" class="form-control project-create-input project-create-surat-segment project-create-surat-roman" style="width: 100%; cursor: pointer;" readonly placeholder="IV" value="{{ $romawi }}">
                                <!-- Dropdown Grid Romawi -->
                                <div class="roman-grid-dropdown" id="roman_grid_dropdown_edit" style="display: none;">
                                    <div class="roman-grid">
                                        @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'] as $rom)
                                            <button type="button" class="roman-grid-item {{ $romawi == $rom ? 'active' : '' }}" data-value="{{ $rom }}">{{ $rom }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <span class="project-create-surat-separator">/</span>
                            <input type="text" id="nomor_surat_tahun_edit" name="nomor_surat_tahun" class="form-control project-create-input project-create-surat-segment project-create-surat-year" maxlength="4" inputmode="numeric" autocomplete="off" placeholder="2026" value="{{ $tahun }}">
                        </div>
                        <input type="hidden" name="nomor_surat" id="nomor_surat" value="{{ $penawaran->nomor_surat }}">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="project-label">Mitra</label>
                        <input type="text" name="mitra" class="form-control project-input" value="{{ $penawaran->mitra }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="project-label">Biaya Penawaran</label>
                        <input type="text" class="form-control project-input" id="biaya" name="biaya_penawaran" value="{{ $penawaran->biaya_penawaran }}">
                    </div>

                    <div class="form-group mb-0">
                        <label class="project-label">Durasi Proyek</label>
                        @php
                        $durasiValue = $penawaran->durasi_proyek ?? '';
                        $durasiParts = explode(' ', $durasiValue);
                        $durasiAngkaVal = (int) ($durasiParts[0] ?? 1);
                        if ($durasiAngkaVal < 1) $durasiAngkaVal = 1;
                        $durasiSatuanVal = $durasiParts[1] ?? 'Bulan';
                        @endphp
                        <div class="modern-duration-container">
                            <!-- Stepper Angka (Kiri) -->
                            <div class="modern-stepper">
                                <button type="button" class="stepper-btn" id="btn_minus_edit" aria-label="Kurangi durasi">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="text" id="durasi_angka_val_edit" class="stepper-input" value="{{ $durasiAngkaVal }}" readonly>
                                <button type="button" class="stepper-btn" id="btn_plus_edit" aria-label="Tambah durasi">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <!-- Segmented Toggle Satuan (Kanan) -->
                            <div class="modern-segmented-control">
                                <button type="button" class="segment-option {{ $durasiSatuanVal == 'Bulan' ? 'active' : '' }}" data-unit="Bulan">Bulan</button>
                                <button type="button" class="segment-option {{ $durasiSatuanVal == 'Tahun' ? 'active' : '' }}" data-unit="Tahun">Tahun</button>
                            </div>

                            <!-- Hidden Input untuk dikirim ke Backend -->
                            <input type="hidden" name="durasi_proyek" id="durasi_proyek" value="{{ $penawaran->durasi_proyek }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6"> 
            <div class="card shadow-sm project-edit-card h-100">
                <div class="card-body project-edit-card-body">
                    <div class="form-group mb-3">
                        <label class="project-label">Dokumen Penawaran (.pdf)</label>
                        <input type="file" name="dokumen" class="form-control project-input project-file-input">
                        @if($penawaran->dokumen)
                        <div class="project-file-link-wrap mt-2">
                            <a href="{{ asset('storage/' . $penawaran->dokumen) }}" target="_blank" class="project-file-link">{{ basename($penawaran->dokumen) }}</a>
                        </div>
                        @endif
                    </div>

                    <div class="form-group mb-0">
                        <label class="project-label">Deskripsi Proyek</label>
                        <textarea class="form-control project-description-input" name="deskripsi" rows="11">{{ $penawaran->deskripsi }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex align-items-center gap-2 project-form-actions">
        <a href="{{ route('administrasi.penawaran.index') }}" class="btn app-chip project-cancel-btn">Keluar</a>
        <button type="submit" class="btn app-chip project-save-btn">Simpan</button>
    </div>
</form>

<style>
    .project-edit-page {
        background: #e9edf4;
        border-radius: 6px;
        padding: 0.6rem;
    }

    .project-edit-card {
        border: 1px solid #dce4ef;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(28, 52, 83, 0.04) !important;
    }

    .project-edit-card-body {
        padding: 1.2rem;
    }

    .project-label {
        display: block;
        margin-bottom: 0.42rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #314964;
    }

    .project-input,
    .project-description-input {
        border: 1px solid #d3dce8;
        border-radius: 8px;
        color: #354e6d;
        font-size: 0.86rem;
        background: #f8fbff;
    }

    .project-input {
        height: 45px;
        padding: 0.52rem 0.78rem;
    }

    .project-select {
        padding-right: 2rem;
    }

    .project-file-input {
        padding: 0.38rem 0.52rem;
        height: auto;
    }

    .project-file-link-wrap {
        font-size: 0.82rem;
    }

    .project-file-link {
        color: #2d4563;
        text-decoration: underline;
        font-weight: 600;
    }

    .project-description-input {
        resize: none;
        min-height: 295px;
        line-height: 1.4;
        padding: 0.65rem 0.78rem;
    }

    .project-form-actions {
        padding-left: 0.15rem;
    }

    .project-cancel-btn,
    .project-save-btn {
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

    .project-cancel-btn {
        background: #e2e8f0 !important;
        color: #475569 !important;
        transition: all 0.15s ease;
    }

    .project-save-btn {
        background: #e2f7f7 !important;
        color: #007375 !important;
        transition: all 0.15s ease;
    }

    .project-cancel-btn:hover,
    .project-cancel-btn:focus,
    .project-cancel-btn:active {
        background: #475569 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .project-save-btn:hover,
    .project-save-btn:focus,
    .project-save-btn:active {
        background: #007375 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* Bring create-style inputs and surat layout to edit page so design matches create */
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
    document.getElementById('nomor_surat_awal_edit').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
    });

    document.getElementById('nomor_surat_sp_edit').addEventListener('input', function(e) {
        let val = this.value.replace(/[^a-zA-Z]/g, '');
        if (val.length > 0) {
            val = val.charAt(0).toUpperCase() + val.slice(1);
        }
        this.value = val;
    });

    document.getElementById('nomor_surat_tahun_edit').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
    });

    // sync nomor surat fields in edit form
    const nomorSuratHidden = document.getElementById('nomor_surat');
    const nomorSuratAwalEdit = document.getElementById('nomor_surat_awal_edit');
    const nomorSuratSpEdit = document.getElementById('nomor_surat_sp_edit');
    const nomorSuratRomawiEdit = document.getElementById('nomor_surat_romawi_edit');
    const nomorSuratTahunEdit = document.getElementById('nomor_surat_tahun_edit');

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

    const syncNomorSuratEdit = () => {
        nomorSuratHidden.value = buildNomor(nomorSuratAwalEdit?.value, nomorSuratSpEdit?.value, nomorSuratRomawiEdit?.value, nomorSuratTahunEdit?.value);
        nomorSuratHidden.dispatchEvent(new Event('change', { bubbles: true }));
    };

    [nomorSuratAwalEdit, nomorSuratSpEdit, nomorSuratRomawiEdit, nomorSuratTahunEdit].forEach((input) => {
        if (!input) return;
        input.addEventListener('input', syncNomorSuratEdit);
        input.addEventListener('blur', syncNomorSuratEdit);
    });

    syncNomorSuratEdit();

    // Custom Roman Grid Dropdown logic for edit page
    const romanDropdownEdit = document.getElementById('roman_grid_dropdown_edit');
    const romanItemsEdit = document.querySelectorAll('#roman_grid_dropdown_edit .roman-grid-item');

    nomorSuratRomawiEdit.addEventListener('click', function(e) {
        e.stopPropagation();
        romanDropdownEdit.style.display = romanDropdownEdit.style.display === 'none' ? 'block' : 'none';
    });

    romanItemsEdit.forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            const val = this.getAttribute('data-value');
            nomorSuratRomawiEdit.value = val;
            
            romanItemsEdit.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            nomorSuratRomawiEdit.dispatchEvent(new Event('input'));
            romanDropdownEdit.style.display = 'none';
        });
    });

    document.addEventListener('click', function(e) {
        if (!romanDropdownEdit.contains(e.target) && e.target !== nomorSuratRomawiEdit) {
            romanDropdownEdit.style.display = 'none';
        }
    });

    const durasiProyekHidden = document.getElementById('durasi_proyek');
    const durasiInputEdit = document.getElementById('durasi_angka_val_edit');
    const btnMinusEdit = document.getElementById('btn_minus_edit');
    const btnPlusEdit = document.getElementById('btn_plus_edit');
    const segmentButtonsEdit = document.querySelectorAll('.modern-segmented-control .segment-option');

    let currentValEdit = parseInt(durasiInputEdit.value) || 1;
    let currentUnitEdit = document.querySelector('.modern-segmented-control .segment-option.active')?.getAttribute('data-unit') || 'Bulan';

    const updateDurasiValEdit = () => {
        durasiInputEdit.value = currentValEdit;
        durasiProyekHidden.value = `${currentValEdit} ${currentUnitEdit}`;
        durasiProyekHidden.dispatchEvent(new Event('change', { bubbles: true }));
    };

    btnMinusEdit.addEventListener('click', () => {
        if (currentValEdit > 1) {
            currentValEdit--;
            updateDurasiValEdit();
        }
    });

    btnPlusEdit.addEventListener('click', () => {
        if (currentValEdit < 12) {
            currentValEdit++;
            updateDurasiValEdit();
        }
    });

    segmentButtonsEdit.forEach(btn => {
        btn.addEventListener('click', function() {
            segmentButtonsEdit.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentUnitEdit = this.getAttribute('data-unit');
            updateDurasiValEdit();
        });
    });

    updateDurasiValEdit();

    // ensure hidden value is up-to-date before submit (select the page form)
    const editForm = document.querySelector('form');
    if (editForm) {
        editForm.addEventListener('submit', function() {
            syncNomorSuratEdit();
            updateDurasiValEdit();
        });
    }
</script>
@endsection