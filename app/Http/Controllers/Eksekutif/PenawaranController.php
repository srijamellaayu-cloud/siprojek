<?php

namespace App\Http\Controllers\Eksekutif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrasi\Penawaran;

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

        return view('eksekutif.penawaran.index', compact('penawarans'));
    }

    public function show($id)
    {
        $penawaran = Penawaran::findOrFail($id);
        return view('eksekutif.penawaran.show', compact('penawaran'));
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
