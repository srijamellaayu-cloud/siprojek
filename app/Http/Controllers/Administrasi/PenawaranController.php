<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Administrasi\Penawaran;
use App\Support\SimplePdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PenawaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Penawaran::where('status', '!=', 'Disetujui');

        if ($request->filled('search')) {
            $query->where('nama_proyek', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $penawarans = $query->latest('id')->paginate(5)->withQueryString();

        return view('administrasi.penawaran.index', compact('penawarans'));
    }

    public function create()
    {
        return view('administrasi.penawaran.create');
    }

    public function store(Request $request)
    {
        if ($request->has('biaya_penawaran')) {
            $request->merge([
                'biaya_penawaran' => str_replace('.', '', $request->input('biaya_penawaran'))
            ]);
        }

        $data = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:255|not_in:___/__/Solustek/__/____',
            'mitra' => 'required|string|max:255',
            'biaya_penawaran' => 'required|numeric',
            'durasi_proyek' => 'required|string|max:255',
            'dokumen' => 'required|file|mimes:pdf,doc,docx',
            'deskripsi' => 'nullable|string',
        ]);

        $data['tanggal'] = Carbon::now('Asia/Jakarta')->toDateString();

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $storageDir = 'dokumen';
            $disk = 'public';

            $extension = $file->getClientOriginalExtension();
            $safeProjectName = trim(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $data['nama_proyek']));
            $safeProjectName = preg_replace('/\s+/', ' ', $safeProjectName);
            $newName = 'Dokumen Penawaran ' . $safeProjectName;

            $candidate = $newName . '.' . $extension;
            $counter = 1;
            while (Storage::disk($disk)->exists($storageDir . '/' . $candidate)) {
                $candidate = $newName . ' (' . $counter . ').' . $extension;
                $counter++;
            }

            $data['dokumen'] = $file->storeAs($storageDir, $candidate, $disk);
        }

        // normalize empty nomor_surat to null so it's truly optional
        if (isset($data['nomor_surat']) && trim($data['nomor_surat']) === '') {
            $data['nomor_surat'] = null;
        }

        // If hidden nomor_surat empty but segmented inputs provided, build server-side
        if (empty($data['nomor_surat'])) {
            $awal = $request->input('nomor_surat_awal');
            $sp = $request->input('nomor_surat_sp');
            $romawi = $request->input('nomor_surat_romawi');
            $tahun = $request->input('nomor_surat_tahun');

            $parts = [];
            if ($awal && trim($awal) !== '') $parts[] = preg_replace('/\D/', '', $awal);
            if ($sp && trim($sp) !== '') $parts[] = trim($sp);
            if (!empty($parts)) $parts[] = 'Solustek';
            if ($romawi && trim($romawi) !== '') $parts[] = strtoupper(preg_replace('/[^IVXLCDM]/i', '', $romawi));
            if ($tahun && trim($tahun) !== '') $parts[] = preg_replace('/\D/', '', $tahun);

            if (!empty($parts)) {
                $data['nomor_surat'] = implode('/', $parts);
            }
        }

        try {
            Penawaran::create($data);
        } catch (\Throwable $e) {
            // Log could be added here if desired
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan penawaran. Periksa kembali isian dan coba lagi.');
        }

        return redirect()->route('administrasi.penawaran.index')->with('success', 'Penawaran berhasil ditambahkan!');
    }


    public function show($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        return view('administrasi.penawaran.show', compact('penawaran'));
    }

    public function edit($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        return view('administrasi.penawaran.edit', compact('penawaran'));
    }

    public function update(Request $request, $id)
    {
        $penawaran = Penawaran::findOrFail($id);

        if ($request->has('biaya_penawaran')) {
            $request->merge([
                'biaya_penawaran' => str_replace('.', '', $request->input('biaya_penawaran'))
            ]);
        }

        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:255|not_in:___/__/Solustek/__/____',
            'mitra' => 'required|string|max:255',
            'biaya_penawaran' => 'nullable|numeric',
            'durasi_proyek' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,xlsx'
        ]);

        $penawaran->nama_proyek = $request->nama_proyek;
        // determine nomor_surat: prefer hidden full value, otherwise rebuild from segmented inputs
        $nomorSuratInput = $request->input('nomor_surat');
        $finalNomor = null;
        if (is_string($nomorSuratInput) && trim($nomorSuratInput) !== '') {
            $finalNomor = $nomorSuratInput;
        } else {
            $awal = $request->input('nomor_surat_awal');
            $sp = $request->input('nomor_surat_sp');
            $romawi = $request->input('nomor_surat_romawi');
            $tahun = $request->input('nomor_surat_tahun');

            $parts = [];
            if ($awal && trim($awal) !== '') $parts[] = preg_replace('/\D/', '', $awal);
            if ($sp && trim($sp) !== '') $parts[] = trim($sp);
            if (!empty($parts)) $parts[] = 'Solustek';
            if ($romawi && trim($romawi) !== '') $parts[] = strtoupper(preg_replace('/[^IVXLCDM]/i', '', $romawi));
            if ($tahun && trim($tahun) !== '') $parts[] = preg_replace('/\D/', '', $tahun);

            if (!empty($parts)) {
                $finalNomor = implode('/', $parts);
            }
        }

        $penawaran->nomor_surat = $finalNomor;
        $penawaran->mitra = $request->mitra;
        $penawaran->biaya_penawaran = $request->biaya_penawaran;
        $penawaran->durasi_proyek = $request->durasi_proyek;
        $penawaran->deskripsi = $request->deskripsi;

        // kalau ada upload dokumen baru
        if ($request->hasFile('dokumen')) {
            // hapus file lama kalau ada
            if ($penawaran->dokumen && file_exists(storage_path('app/public/' . $penawaran->dokumen))) {
                unlink(storage_path('app/public/' . $penawaran->dokumen));
            }

            // simpan file baru ke storage/public/dokumen
            $file = $request->file('dokumen');
            $storageDir = 'dokumen';
            $disk = 'public';

            $extension = $file->getClientOriginalExtension();
            $safeProjectName = trim(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $penawaran->nama_proyek));
            $safeProjectName = preg_replace('/\s+/', ' ', $safeProjectName);
            $newName = 'Dokumen Penawaran ' . $safeProjectName;

            $candidate = $newName . '.' . $extension;
            $counter = 1;
            while (Storage::disk($disk)->exists($storageDir . '/' . $candidate)) {
                $candidate = $newName . ' (' . $counter . ').' . $extension;
                $counter++;
            }

            $path = $file->storeAs($storageDir, $candidate, $disk);
            $penawaran->dokumen = $path;
        }

        $penawaran->save();

        return redirect()->route('administrasi.penawaran.index')->with('success', 'Data proyek berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Penawaran::destroy($id);
        return redirect()->route('administrasi.penawaran.index')->with('success', 'Penawaran berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $penawaran = Penawaran::findOrFail($id);

        if ($penawaran->status === 'Ditolak') {
            return redirect()->back()->with('error', 'Status ditolak tidak dapat diubah lagi.');
        }

        $data = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_penolakan' => 'nullable|string|required_if:status,Ditolak|max:255',
        ]);

        $penawaran->status = $data['status'];
        if ($data['status'] === 'Ditolak') {
            $penawaran->catatan_penolakan = trim((string) ($data['catatan_penolakan'] ?? ''));
        }
        $penawaran->save();

        if ($data['status'] === 'Disetujui') {
            return redirect()->route('administrasi.deal.index')
                ->with('success', 'Penawaran berhasil dijadikan Deal!');
        }

        return redirect()->back()->with('success', 'Status penawaran berhasil diubah menjadi Ditolak.');
    }

    public function invoice($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        $templatePath = base_path('dokumen/Data Proyek Penawaran.docx');

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Berkas template dokumen laporan tidak ditemukan.');
        }

        // Create a temporary file for the filled DOCX
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        copy($templatePath, $tempFile);

        $zip = new \ZipArchive();
        if ($zip->open($tempFile) === true) {
            // 1. Add hyperlink relationship to document.xml.rels if a document exists
            $hasDoc = !empty($penawaran->dokumen);
            if ($hasDoc) {
                $relsContent = $zip->getFromName('word/_rels/document.xml.rels');
                if ($relsContent !== false) {
                    $relsDom = new \DOMDocument();
                    $relsDom->preserveWhiteSpace = false;
                    if ($relsDom->loadXML($relsContent)) {
                        $relationshipsNode = $relsDom->documentElement;
                        
                        $relNode = $relsDom->createElement('Relationship');
                        $relNode->setAttribute('Id', 'rIdHyperlinkDoc');
                        $relNode->setAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink');
                        $relNode->setAttribute('Target', url('storage/' . $penawaran->dokumen));
                        $relNode->setAttribute('TargetMode', 'External');
                        $relationshipsNode->appendChild($relNode);
                        
                        $zip->deleteName('word/_rels/document.xml.rels');
                        $zip->addFromString('word/_rels/document.xml.rels', $relsDom->saveXML());
                    }
                }
            }

            // 2. Modify word/document.xml
            $xmlContent = $zip->getFromName('word/document.xml');
            if ($xmlContent !== false) {
                $dom = new \DOMDocument();
                $dom->preserveWhiteSpace = false;
                if ($dom->loadXML($xmlContent)) {
                    $xpath = new \DOMXPath($dom);
                    $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

                    // Helper to fill cell
                    $fillCell = function($label, $value) use ($xpath, $dom) {
                        $query = "//w:tr[w:tc[1]/w:p/w:r/w:t[text()=\"$label\"]]/w:tc[3]/w:p";
                        $pNode = $xpath->query($query)->item(0);
                        if ($pNode) {
                            // Remove existing runs in this paragraph
                            $runs = $xpath->query('w:r', $pNode);
                            foreach ($runs as $run) {
                                $pNode->removeChild($run);
                            }
                            // Create new run
                            $run = $dom->createElement('w:r');
                            $rPr = $dom->createElement('w:rPr');
                            
                            $rFonts = $dom->createElement('w:rFonts');
                            $rFonts->setAttribute('w:ascii', 'Cambria');
                            $rPr->appendChild($rFonts);
                            
                            $sz = $dom->createElement('w:sz');
                            $sz->setAttribute('w:val', '24');
                            $rPr->appendChild($sz);
                            
                            $run->appendChild($rPr);
                            
                            $t = $dom->createElement('w:t');
                            $t->nodeValue = htmlspecialchars($value);
                            $run->appendChild($t);
                            
                            $pNode->appendChild($run);
                        }
                    };

                    // Fill table fields
                    $fillCell('Nama Proyek', $penawaran->nama_proyek);
                    $fillCell('Nomor Surat Penawaran', $penawaran->nomor_surat ?: '-');
                    $fillCell('Mitra', $penawaran->mitra);
                    $biayaText = $penawaran->biaya_penawaran !== null ? 'Rp ' . number_format($penawaran->biaya_penawaran, 0, ',', '.') : '-';
                    $fillCell('Biaya Penawaran', $biayaText);
                    $fillCell('Durasi Proyek', $penawaran->durasi_proyek ?: '-');

                    // Update Dokumen Pendukung Link Paragraph
                    $docLinkQuery = '//w:p[w:r/w:t[contains(text(), "Dokumen")] and w:r/w:t[contains(text(), "Pendukung")]]/following-sibling::w:p[1]';
                    $docLinkP = $xpath->query($docLinkQuery)->item(0);
                    if ($docLinkP) {
                        $runs = $xpath->query('w:r', $docLinkP);
                        foreach ($runs as $run) {
                            $docLinkP->removeChild($run);
                        }
                        
                        if ($hasDoc) {
                            // Create a w:hyperlink node
                            $hyperlinkNode = $dom->createElement('w:hyperlink');
                            $hyperlinkNode->setAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'r:id', 'rIdHyperlinkDoc');
                            
                            $run = $dom->createElement('w:r');
                            $rPr = $dom->createElement('w:rPr');
                            $rFonts = $dom->createElement('w:rFonts');
                            $rFonts->setAttribute('w:ascii', 'Cambria');
                            $rPr->appendChild($rFonts);
                            $sz = $dom->createElement('w:sz');
                            $sz->setAttribute('w:val', '24');
                            $rPr->appendChild($sz);
                            
                            // Style as hyperlink (blue and underline)
                            $color = $dom->createElement('w:color');
                            $color->setAttribute('w:val', '0563C1');
                            $rPr->appendChild($color);
                            
                            $u = $dom->createElement('w:u');
                            $u->setAttribute('w:val', 'single');
                            $rPr->appendChild($u);
                            
                            $run->appendChild($rPr);

                            $t = $dom->createElement('w:t');
                            $t->nodeValue = htmlspecialchars(basename($penawaran->dokumen));
                            $run->appendChild($t);
                            
                            $hyperlinkNode->appendChild($run);
                            $docLinkP->appendChild($hyperlinkNode);
                        } else {
                            // Normal run if there is no doc
                            $run = $dom->createElement('w:r');
                            $rPr = $dom->createElement('w:rPr');
                            $rFonts = $dom->createElement('w:rFonts');
                            $rFonts->setAttribute('w:ascii', 'Cambria');
                            $rPr->appendChild($rFonts);
                            $sz = $dom->createElement('w:sz');
                            $sz->setAttribute('w:val', '24');
                            $rPr->appendChild($sz);
                            $run->appendChild($rPr);

                            $t = $dom->createElement('w:t');
                            $t->nodeValue = '-';
                            $run->appendChild($t);
                            $docLinkP->appendChild($run);
                        }
                    }

                    // Update Deskripsi Proyek
                    $descQuery = '//w:p[w:r/w:t[text()="Deskripsi Proyek"]]/following-sibling::w:p[1]';
                    $descP = $xpath->query($descQuery)->item(0);
                    if ($descP) {
                        $runs = $xpath->query('w:r', $descP);
                        foreach ($runs as $run) {
                            $descP->removeChild($run);
                        }
                        
                        $run = $dom->createElement('w:r');
                        $rPr = $dom->createElement('w:rPr');
                        $rFonts = $dom->createElement('w:rFonts');
                        $rFonts->setAttribute('w:ascii', 'Cambria');
                        $rPr->appendChild($rFonts);
                        $sz = $dom->createElement('w:sz');
                        $sz->setAttribute('w:val', '24');
                        $rPr->appendChild($sz);
                        $run->appendChild($rPr);

                        $lines = explode("\n", $penawaran->deskripsi ?? '');
                        foreach ($lines as $i => $line) {
                            if ($i > 0) {
                                $run->appendChild($dom->createElement('w:br'));
                            }
                            $t = $dom->createElement('w:t');
                            $t->nodeValue = htmlspecialchars($line);
                            $run->appendChild($t);
                        }
                        $descP->appendChild($run);
                    }

                    // Update Pekanbaru Date
                    $dateNode = $xpath->query('//w:r[w:t[contains(text(), "Pekanbaru,")]]/w:t')->item(0);
                    if ($dateNode) {
                        $months = [
                            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
                        $d = Carbon::now('Asia/Jakarta');
                        $formattedDate = 'Pekanbaru, ' . $d->day . ' ' . $months[$d->month] . ' ' . $d->year;
                        $dateNode->nodeValue = $formattedDate;
                    }

                    // Update PIC
                    $picP = $xpath->query('//w:p[w:r/w:t[contains(text(), "(Nama")]]')->item(0);
                    if ($picP) {
                        $runs = $xpath->query('w:r', $picP);
                        foreach ($runs as $run) {
                            $picP->removeChild($run);
                        }
                        
                        $run = $dom->createElement('w:r');
                        $rPr = $dom->createElement('w:rPr');
                        $rFonts = $dom->createElement('w:rFonts');
                        $rFonts->setAttribute('w:ascii', 'Cambria');
                        $rPr->appendChild($rFonts);
                        $sz = $dom->createElement('w:sz');
                        $sz->setAttribute('w:val', '24');
                        $rPr->appendChild($sz);
                        $run->appendChild($rPr);

                        $t = $dom->createElement('w:t');
                        $t->nodeValue = auth()->check() ? auth()->user()->name : 'Administrator';
                        $run->appendChild($t);
                        $picP->appendChild($run);

                        // Preserve tab run
                        $runTab = $dom->createElement('w:r');
                        $runTab->appendChild($dom->createElement('w:tab'));
                        $picP->appendChild($runTab);
                    }

                    // Save modified XML back into zip
                    $zip->deleteName('word/document.xml');
                    $zip->addFromString('word/document.xml', $dom->saveXML());
                }
            }
            $zip->close();
        }

        $filename = 'Laporan Proyek Penawaran - ' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $penawaran->nama_proyek) . '.docx';
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
