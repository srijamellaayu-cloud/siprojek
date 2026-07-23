<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;

use App\Models\Administrasi\DealTask;
use App\Models\Administrasi\Penawaran;
use App\Models\User;
use App\Support\SimplePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DealController extends Controller
{
    public function index(Request $request)
    {
        $query = Penawaran::where('status', 'Disetujui')
            ->selectRaw('penawaran.*, GREATEST(penawaran.updated_at, COALESCE((SELECT MAX(updated_at) FROM deal_tasks WHERE deal_tasks.penawaran_id = penawaran.id), penawaran.updated_at)) as latest_activity')
            ->withCount([
                'tasks as total_tasks_count',
                'tasks as done_tasks_count' => function ($taskQuery) {
                    $taskQuery->where('status', 'Done');
                },
            ])
            ->with('tasks');

        if ($request->filled('search')) {
            $query->where('nama_proyek', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date)
                ->whereDate('tanggal', '<=', $request->end_date);
        }

        $deals = $query->orderByDesc('latest_activity')->paginate(5)->withQueryString();

        return view('administrasi.deal.index', compact('deals'));
    }

    public function show(Request $request, $id)
    {
        $deal = Penawaran::findOrFail($id);

        $taskQuery = $deal->tasks()->latest('updated_at');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $taskQuery->whereDate('tanggal_tugas', '>=', $request->start_date)
                ->whereDate('tanggal_tugas', '<=', $request->end_date);
        }

        $dealTasks = $taskQuery->paginate(5, ['*'], 'task_page')->withQueryString();
        $baseNames = User::orderBy('name')->pluck('name')->toArray();
        $customNames = DealTask::whereNotNull('anggota')
            ->pluck('anggota')
            ->flatMap(fn($anggota) => $this->normalizeAnggota($anggota))
            ->map(fn($name) => trim((string) $name))
            ->filter()
            ->values()
            ->toArray();

        $anggotaOptions = collect(array_merge($baseNames, $customNames))
            ->unique()
            ->values()
            ->all();

        return view('administrasi.deal.show', compact('deal', 'dealTasks', 'anggotaOptions'));
    }

    public function storeTask(Request $request, $id)
    {
        $deal = Penawaran::findOrFail($id);

        if ($deal->progress >= 100) {
            return redirect()->back()->with('error', 'Tidak dapat menambahkan tugas pada proyek deal yang sudah 100%.');
        }

        $data = $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'anggota' => 'nullable|array',
            'anggota.*' => 'string|max:255',
            'anggota_lainnya' => 'nullable|string|max:255',
            'tanggal_tugas' => 'required|date',
            'durasi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $allAnggota = $this->buildAnggotaNames(
            $data['anggota'] ?? [],
            $data['anggota_lainnya'] ?? null
        );

        unset($data['anggota_lainnya']);
        $data['anggota'] = !empty($allAnggota) ? array_values(array_unique($allAnggota)) : null;
        $data['status'] = 'On Progress';

        $deal->tasks()->create($data);

        return redirect()->route('administrasi.deal.show', $deal->id)
            ->with('success', 'Tugas proyek berhasil ditambahkan.');
    }

    public function updateTask(Request $request, $id, $taskId)
    {
        $deal = Penawaran::findOrFail($id);
        $task = $deal->tasks()->findOrFail($taskId);

        if ($deal->progress >= 100) {
            return redirect()->back()->with('error', 'Tugas proyek deal yang sudah 100% tidak dapat diperbarui.');
        }

        if ($task->status === 'Done') {
            return redirect()->back()->with('error', 'Tugas yang sudah Done tidak dapat diperbarui.');
        }

        $rules = [
            'nama_tugas' => 'required|string|max:255',
            'anggota' => 'nullable|array',
            'anggota.*' => 'string|max:255',
            'anggota_lainnya' => 'nullable|string|max:255',
            'tanggal_tugas' => $task->nama_tugas === 'Invoice Penagihan' ? 'nullable|date' : 'required|date',
            'durasi' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ];

        $data = $request->validate($rules);

        $allAnggota = $this->buildAnggotaNames(
            $data['anggota'] ?? [],
            $data['anggota_lainnya'] ?? null
        );

        unset($data['anggota_lainnya']);
        $data['anggota'] = !empty($allAnggota) ? array_values(array_unique($allAnggota)) : null;

        if ($task->nama_tugas === 'Invoice Penagihan') {
            unset($data['tanggal_tugas']);
            unset($data['deskripsi']);
            unset($data['status']);
        }

        $task->update($data);

        return redirect()->route('administrasi.deal.show', $deal->id)
            ->with('success', 'Tugas proyek berhasil diperbarui.');
    }

    public function markTaskDone($id, $taskId)
    {
        $deal = Penawaran::findOrFail($id);
        $task = $deal->tasks()->findOrFail($taskId);

        if ($deal->progress >= 100) {
            return redirect()->back()->with('error', 'Tugas proyek deal yang sudah 100% tidak dapat diperbarui.');
        }

        if ($task->status === 'Done') {
            return redirect()->back()->with('error', 'Tugas yang sudah Done tidak dapat diperbarui.');
        }

        $task->update(['status' => 'Done']);

        return redirect()->route('administrasi.deal.show', $deal->id)
            ->with('success', 'Status tugas berhasil diubah menjadi Done.');
    }

    private function buildAnggotaNames(array $selectedNames, ?string $anggotaLainnya): array
    {
        $allAnggota = [];

        if (!empty($selectedNames)) {
            $cleanSelectedNames = array_map('trim', array_map('strval', $selectedNames));
            $cleanSelectedNames = array_filter($cleanSelectedNames);
            $allAnggota = array_merge($allAnggota, $cleanSelectedNames);
        }

        if (!empty($anggotaLainnya)) {
            $others = array_map('trim', explode(',', $anggotaLainnya));
            $others = array_filter($others);
            $allAnggota = array_merge($allAnggota, $others);
        }

        return $allAnggota;
    }

    private function normalizeAnggota(mixed $anggota): array
    {
        if (is_array($anggota)) {
            return $anggota;
        }

        if (!is_string($anggota) || trim($anggota) === '') {
            return [];
        }

        $decoded = json_decode($anggota, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // Backward compatibility for legacy double-encoded JSON values.
        if (is_string($decoded)) {
            $nestedDecoded = json_decode($decoded, true);
            if (is_array($nestedDecoded)) {
                return $nestedDecoded;
            }
        }

        return [];
    }

    public function edit($id)
    {
        $deal = Penawaran::findOrFail($id);
        return view('administrasi.deal.edit', compact('deal'));
    }

    public function update(Request $request, $id)
    {
        $deal = Penawaran::findOrFail($id);

        if ($deal->progress >= 100) {
            return redirect()->back()->with('error', 'Proyek deal yang sudah 100% tidak dapat diperbarui.');
        }

        if ($request->has('biaya_penawaran')) {
            $request->merge([
                'biaya_penawaran' => str_replace('.', '', $request->input('biaya_penawaran'))
            ]);
        }

        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'nomor_kontrak' => 'nullable|string|max:255|not_in:___/__/Solustek/__/____',
            'mitra' => 'required|string|max:255',
            'biaya_penawaran' => 'nullable|numeric',
            'durasi_proyek' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,xlsx',
            'dokumen_kontrak' => 'nullable|file|mimes:pdf,doc,docx,xlsx'
        ]);

        $deal->nama_proyek = $request->nama_proyek;
        $deal->tanggal_mulai = $request->tanggal_mulai;
        $deal->tanggal_selesai = $request->tanggal_selesai;
        $deal->mitra = $request->mitra;
        $deal->biaya_penawaran = $request->biaya_penawaran;
        $deal->durasi_proyek = $request->durasi_proyek;
        $deal->deskripsi = $request->deskripsi;

        // determine nomor_kontrak: prefer hidden full value, otherwise rebuild from segmented inputs
        $nomorKontrakInput = $request->input('nomor_kontrak');
        $finalNomorKontrak = null;
        if (is_string($nomorKontrakInput) && trim($nomorKontrakInput) !== '') {
            $finalNomorKontrak = $nomorKontrakInput;
        } else {
            $awal = $request->input('nomor_kontrak_awal');
            $sp = $request->input('nomor_kontrak_sp');
            $romawi = $request->input('nomor_kontrak_romawi');
            $tahun = $request->input('nomor_kontrak_tahun');

            $parts = [];
            if ($awal && trim($awal) !== '') $parts[] = preg_replace('/\D/', '', $awal);
            if ($sp && trim($sp) !== '') $parts[] = trim($sp);
            if (!empty($parts)) $parts[] = 'Solustek';
            if ($romawi && trim($romawi) !== '') $parts[] = strtoupper(preg_replace('/[^IVXLCDM]/i', '', $romawi));
            if ($tahun && trim($tahun) !== '') $parts[] = preg_replace('/\D/', '', $tahun);

            if (!empty($parts)) {
                $finalNomorKontrak = implode('/', $parts);
            }
        }
        $deal->nomor_kontrak = $finalNomorKontrak;

        if ($request->hasFile('dokumen')) {
            if ($deal->dokumen) {
                Storage::disk('public')->delete($deal->dokumen);
            }

            $file = $request->file('dokumen');
            $storageDir = 'dokumen';
            $disk = 'public';

            $extension = $file->getClientOriginalExtension();
            $safeProjectName = trim(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $deal->nama_proyek));
            $safeProjectName = preg_replace('/\s+/', ' ', $safeProjectName);
            $newName = 'Dokumen Penawaran ' . $safeProjectName;

            $candidate = $newName . '.' . $extension;
            $counter = 1;
            while (Storage::disk($disk)->exists($storageDir . '/' . $candidate)) {
                $candidate = $newName . ' (' . $counter . ').' . $extension;
                $counter++;
            }

            $path = $file->storeAs($storageDir, $candidate, $disk);
            $deal->dokumen = $path;
        }

        if ($request->hasFile('dokumen_kontrak')) {
            if ($deal->dokumen_kontrak) {
                Storage::disk('public')->delete($deal->dokumen_kontrak);
            }

            $file = $request->file('dokumen_kontrak');
            $storageDir = 'dokumen';
            $disk = 'public';

            $extension = $file->getClientOriginalExtension();
            $safeProjectName = trim(preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $deal->nama_proyek));
            $safeProjectName = preg_replace('/\s+/', ' ', $safeProjectName);
            $newName = 'Dokumen Kontrak ' . $safeProjectName;

            $candidate = $newName . '.' . $extension;
            $counter = 1;
            while (Storage::disk($disk)->exists($storageDir . '/' . $candidate)) {
                $candidate = $newName . ' (' . $counter . ').' . $extension;
                $counter++;
            }

            $path = $file->storeAs($storageDir, $candidate, $disk);
            $deal->dokumen_kontrak = $path;
        }

        $deal->save();

        return redirect()->route('administrasi.deal.show', $deal->id)->with('success', 'Data deal berhasil diperbarui.');
    }

    public function invoice($id)
    {
        $deal = Penawaran::findOrFail($id);
        $templatePath = base_path('dokumen/Data Proyek Deal.docx');

        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Berkas template dokumen laporan tidak ditemukan.');
        }

        // Create a temporary file for the filled DOCX
        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        copy($templatePath, $tempFile);

        $progress = (int) $deal->progress;

        $zip = new \ZipArchive();
        if ($zip->open($tempFile) === true) {
            // 1. Add hyperlink relationships to document.xml.rels
            $hasDoc = !empty($deal->dokumen);
            $hasContract = !empty($deal->dokumen_kontrak);
            
            if ($hasDoc || $hasContract) {
                $relsContent = $zip->getFromName('word/_rels/document.xml.rels');
                if ($relsContent !== false) {
                    $relsDom = new \DOMDocument();
                    $relsDom->preserveWhiteSpace = false;
                    if ($relsDom->loadXML($relsContent)) {
                        $relationshipsNode = $relsDom->documentElement;
                        
                        if ($hasDoc) {
                            $relNode1 = $relsDom->createElement('Relationship');
                            $relNode1->setAttribute('Id', 'rIdHyperlinkDoc');
                            $relNode1->setAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink');
                            $relNode1->setAttribute('Target', url('storage/' . $deal->dokumen));
                            $relNode1->setAttribute('TargetMode', 'External');
                            $relationshipsNode->appendChild($relNode1);
                        }
                        
                        if ($hasContract) {
                            $relNode2 = $relsDom->createElement('Relationship');
                            $relNode2->setAttribute('Id', 'rIdHyperlinkKontrak');
                            $relNode2->setAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/hyperlink');
                            $relNode2->setAttribute('Target', url('storage/' . $deal->dokumen_kontrak));
                            $relNode2->setAttribute('TargetMode', 'External');
                            $relationshipsNode->appendChild($relNode2);
                        }
                        
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

                    $formatIndonesianDate = function($date) {
                        if (!$date) return '-';
                        $months = [
                            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
                        $d = \Carbon\Carbon::parse($date);
                        return $d->day . ' ' . $months[$d->month] . ' ' . $d->year;
                    };

                    // Map fields based on the cell 1 label text
                    $dateText = '-';
                    if ($deal->tanggal_mulai && $deal->tanggal_selesai) {
                        $dateText = $formatIndonesianDate($deal->tanggal_mulai) . ' s/d ' . $formatIndonesianDate($deal->tanggal_selesai);
                    }

                    $fields = [
                        'Nama Proyek' => $deal->nama_proyek,
                        'Nomor Surat Penawaran' => $deal->nomor_surat ?: '-',
                        'Mitra' => $deal->mitra,
                        'Biaya Penawaran' => $deal->biaya_penawaran !== null ? 'Rp ' . number_format($deal->biaya_penawaran, 0, ',', '.') : '-',
                        'Durasi Proyek' => $deal->durasi_proyek ?: '-',
                        'Nomor Kontrak' => $deal->nomor_kontrak ?: '-',
                        'Tanggal Proyek' => $dateText,
                    ];

                    $trNodes = $xpath->query('//w:tr');
                    foreach ($trNodes as $tr) {
                        $cell1 = $xpath->query('w:tc[1]', $tr)->item(0);
                        if ($cell1) {
                            $cellLabel = trim(str_replace("\xA0", ' ', $cell1->nodeValue));
                            foreach ($fields as $label => $value) {
                                if (strcasecmp($cellLabel, $label) === 0) {
                                    $cell3P = $xpath->query('w:tc[3]/w:p', $tr)->item(0);
                                    if ($cell3P) {
                                        // Clear existing runs
                                        $runs = $xpath->query('w:r', $cell3P);
                                        foreach ($runs as $run) {
                                            $cell3P->removeChild($run);
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
                                        
                                        $cell3P->appendChild($run);
                                    }
                                    break;
                                }
                            }
                        }
                    }

                    // Update Dokumen Pendukung Paragraphs (Penawaran and Kontrak)
                    $docLinkQuery1 = '//w:p[w:r/w:t[contains(text(), "Dokumen")] and w:r/w:t[contains(text(), "Pendukung")]]/following-sibling::w:p[1]';
                    $docLinkQuery2 = '//w:p[w:r/w:t[contains(text(), "Dokumen")] and w:r/w:t[contains(text(), "Pendukung")]]/following-sibling::w:p[2]';
                    
                    $docLinkP1 = $xpath->query($docLinkQuery1)->item(0);
                    $docLinkP2 = $xpath->query($docLinkQuery2)->item(0);
                    
                    $fillDocLink = function($pNode, $fileName, $relId) use ($dom, $xpath) {
                        if (!$pNode) return;
                        $runs = $xpath->query('w:r', $pNode);
                        foreach ($runs as $run) {
                            $pNode->removeChild($run);
                        }
                        
                        if (!empty($fileName)) {
                            $hyperlinkNode = $dom->createElement('w:hyperlink');
                            $hyperlinkNode->setAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'r:id', $relId);
                            
                            $run = $dom->createElement('w:r');
                            $rPr = $dom->createElement('w:rPr');
                            $rFonts = $dom->createElement('w:rFonts');
                            $rFonts->setAttribute('w:ascii', 'Cambria');
                            $rPr->appendChild($rFonts);
                            $sz = $dom->createElement('w:sz');
                            $sz->setAttribute('w:val', '24');
                            $rPr->appendChild($sz);
                            
                            $color = $dom->createElement('w:color');
                            $color->setAttribute('w:val', '0563C1');
                            $rPr->appendChild($color);
                            
                            $u = $dom->createElement('w:u');
                            $u->setAttribute('w:val', 'single');
                            $rPr->appendChild($u);
                            
                            $run->appendChild($rPr);

                            $t = $dom->createElement('w:t');
                            $t->nodeValue = htmlspecialchars(basename($fileName));
                            $run->appendChild($t);
                            
                            $hyperlinkNode->appendChild($run);
                            $pNode->appendChild($hyperlinkNode);
                        } else {
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
                            $pNode->appendChild($run);
                        }
                    };

                    $fillDocLink($docLinkP1, $deal->dokumen, 'rIdHyperlinkDoc');
                    $fillDocLink($docLinkP2, $deal->dokumen_kontrak, 'rIdHyperlinkKontrak');

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

                        $lines = explode("\n", $deal->deskripsi ?? '');
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

                    // Fill Tasks Table
                    $tblNode = $xpath->query('//w:tbl[w:tr[1]/w:tc[1]/w:p/w:r/w:t[text()="No"] and w:tr[1]/w:tc[2]/w:p/w:r/w:t[contains(text(), "Nama")]]')->item(0);
                    if ($tblNode) {
                        $rowTemplate = $xpath->query('w:tr[2]', $tblNode)->item(0);
                        if ($rowTemplate) {
                            $newRowTemplate = $rowTemplate->cloneNode(true);
                            
                            // Delete all data rows in table
                            $rows = $xpath->query('w:tr', $tblNode);
                            for ($i = 1; $i < $rows->length; $i++) {
                                $tblNode->removeChild($rows->item($i));
                            }
                            
                            $index = 1;
                            foreach ($deal->tasks as $task) {
                                $clonedRow = $newRowTemplate->cloneNode(true);
                                
                                $popCell = function($colIdx, $value) use ($xpath, $clonedRow, $dom) {
                                    $tc = $xpath->query("w:tc[$colIdx]", $clonedRow)->item(0);
                                    if ($tc) {
                                        $pNodes = $xpath->query('w:p', $tc);
                                        $firstP = $pNodes->item(0);
                                        if ($firstP) {
                                            $runs = $xpath->query('w:r', $firstP);
                                            foreach ($runs as $run) {
                                                $firstP->removeChild($run);
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
                                            
                                            $lines = explode("\n", $value);
                                            foreach ($lines as $li => $line) {
                                                if ($li > 0) {
                                                    $run->appendChild($dom->createElement('w:br'));
                                                }
                                                $t = $dom->createElement('w:t');
                                                $t->nodeValue = htmlspecialchars($line);
                                                $run->appendChild($t);
                                            }
                                            $firstP->appendChild($run);
                                        }
                                        for ($p = 1; $p < $pNodes->length; $p++) {
                                            $tc->removeChild($pNodes->item($p));
                                        }
                                    }
                                };
                                
                                 $popCell(1, $index . '.');
                                 $popCell(2, $task->nama_tugas);
                                 $popCell(3, !empty($task->anggota) ? implode(', ', $task->anggota) : '-');
                                 $popCell(4, $task->tanggal_tugas ? $formatIndonesianDate($task->tanggal_tugas) : '-');
                                $popCell(5, $task->status ?: '-');
                                
                                $tblNode->appendChild($clonedRow);
                                $index++;
                            }
                        }
                    }

                    // Update progress percentage
                    $progressNode = $xpath->query('//w:p[w:r/w:t[contains(text(), "Persentase")]]/w:r[w:t[text()=".."]]/w:t')->item(0);
                    if ($progressNode) {
                        $progressNode->nodeValue = (string) $progress;
                    }

                    // Update Catatan Proyek
                    $lateTasks = [];
                    $notesIdx = 1;
                    foreach ($deal->tasks as $task) {
                        $daysLeft = $task->days_left;
                        if ($task->status !== 'Done' && $daysLeft !== null && $daysLeft < 0) {
                            $lateTasks[] = $notesIdx . '. Tugas "' . $task->nama_tugas . '" mengalami keterlambatan selama ' . abs($daysLeft) . ' hari.';
                            $notesIdx++;
                        }
                    }
                    $notesText = empty($lateTasks) ? 'Semua tugas berjalan tepat waktu.' : implode("\n", $lateTasks);
                    
                    $notesP = $xpath->query('//w:p[w:r/w:t[contains(text(), "Cth: ")]]')->item(0);
                    if ($notesP) {
                        $runs = $xpath->query('w:r', $notesP);
                        foreach ($runs as $run) {
                            $notesP->removeChild($run);
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

                        $lines = explode("\n", $notesText);
                        foreach ($lines as $i => $line) {
                            if ($i > 0) {
                                $run->appendChild($dom->createElement('w:br'));
                            }
                            $t = $dom->createElement('w:t');
                            $t->nodeValue = htmlspecialchars($line);
                            $run->appendChild($t);
                        }
                        $notesP->appendChild($run);
                    }

                    // Update Pekanbaru Date
                    $dateP = $xpath->query('//w:p[w:r/w:t[contains(text(), "Pekanbaru")]]')->item(0);
                    if ($dateP) {
                        $runs = $xpath->query('w:r', $dateP);
                        foreach ($runs as $run) {
                            $dateP->removeChild($run);
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
                        $t->nodeValue = 'Pekanbaru, ' . $formatIndonesianDate(now());
                        $run->appendChild($t);
                        $dateP->appendChild($run);
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

        $filename = 'Laporan Proyek Deal - ' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $deal->nama_proyek) . '.docx';
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function getDeadlineNotifications()
    {
        $thresholdDays = 3;
        $start = now()->startOfDay();
        $end = now()->endOfDay()->addDays($thresholdDays);

        $tasks = DealTask::with('deal')
            ->where('status', '!=', 'Done')
            ->whereDate('tanggal_tugas', '>=', $start->toDateString())
            ->whereDate('tanggal_tugas', '<=', $end->toDateString())
            ->orderBy('tanggal_tugas', 'asc')
            ->get()
            ->map(function ($task) {
                $daysLeft = $task->days_left;

                if ($daysLeft === null || $daysLeft < 0) {
                    return null;
                }

                $urgency = $daysLeft === 0 ? 'today' : ($daysLeft === 1 ? 'tomorrow' : 'soon');

                return [
                    'type' => 'task',
                    'id' => $task->id,
                    'nama_tugas' => $task->nama_tugas,
                    'nama_proyek' => $task->deal ? $task->deal->nama_proyek : 'N/A',
                    'tanggal_tugas' => $task->tanggal_tugas ? $task->tanggal_tugas->format('d/m/Y') : null,
                    'deal_id' => $task->penawaran_id,
                    'deal_nama' => $task->deal ? $task->deal->nama_proyek : 'N/A',
                    'days_left' => $daysLeft,
                    'deadline_label' => $task->deadline_label,
                    'urgency' => $urgency,
                ];
            })
            ->filter()
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'count' => count($tasks),
            'tasks' => $tasks,
        ]);
    }
}
