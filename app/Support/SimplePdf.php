<?php

namespace App\Support;

class SimplePdf
{
    private $title;
    private $pages = [];
    private $currentPageContent = [];
    private $y = 750;
    private $lineHeight = 16;
    private $margins = ['left' => 50, 'right' => 545];
    private $bottomLimit = 60;

    public function __construct(string $title)
    {
        $this->title = self::normalize($title);
        $this->startNewPage();
    }

    private function startNewPage()
    {
        if (!empty($this->currentPageContent)) {
            $this->pages[] = $this->currentPageContent;
            $this->currentPageContent = [];
        }
        
        $this->y = 780;
        
        // Draw a clean top border line
        $this->currentPageContent[] = "0.5 w 50 790 m 545 790 l S";
    }

    public function addTitle(string $text)
    {
        $text = self::normalize($text);
        // Draw title
        $this->currentPageContent[] = 'BT /F2 16 Tf 50 ' . $this->y . ' Td (' . self::escape($text) . ') Tj ET';
        $this->y -= 25;
        // Underline title
        $this->currentPageContent[] = "1 w 50 " . ($this->y + 15) . " m 545 " . ($this->y + 15) . " l S";
        $this->y -= 10;
    }

    public function addSection(string $text)
    {
        if ($this->y < $this->bottomLimit + 40) {
            $this->startNewPage();
        }
        $text = self::normalize($text);
        $this->y -= 5;
        $this->currentPageContent[] = 'BT /F2 12 Tf 50 ' . $this->y . ' Td (' . self::escape($text) . ') Tj ET';
        $this->y -= 18;
    }

    public function addLine(string $text, bool $bold = false, int $size = 10)
    {
        if ($this->y < $this->bottomLimit) {
            $this->startNewPage();
        }
        $text = self::normalize($text);
        $font = $bold ? '/F2' : '/F1';
        $this->currentPageContent[] = 'BT ' . $font . ' ' . $size . ' Tf 50 ' . $this->y . ' Td (' . self::escape($text) . ') Tj ET';
        $this->y -= $this->lineHeight;
    }

    public function addKeyValuePair(string $key, string $value, int $keyWidth = 150)
    {
        if ($this->y < $this->bottomLimit) {
            $this->startNewPage();
        }
        $key = self::normalize($key);
        $value = self::normalize($value);

        // Key
        $this->currentPageContent[] = 'BT /F2 10 Tf 50 ' . $this->y . ' Td (' . self::escape($key) . ') Tj ET';
        // Value (with wrapping if too long)
        $valueX = 50 + $keyWidth;
        
        $wrappedLines = $this->wrapText($value, 545 - $valueX, 10, false);
        if (empty($wrappedLines)) {
            $wrappedLines = ['-'];
        }
        foreach ($wrappedLines as $index => $wLine) {
            if ($index > 0 && $this->y < $this->bottomLimit) {
                $this->startNewPage();
            }
            $this->currentPageContent[] = 'BT /F1 10 Tf ' . $valueX . ' ' . $this->y . ' Td (' . self::escape($wLine) . ') Tj ET';
            $this->y -= $this->lineHeight;
        }
    }

    public function addDivider(float $width = 0.5)
    {
        $this->currentPageContent[] = $width . " w 50 " . $this->y . " m 545 " . $this->y . " l S";
        $this->y -= 12;
    }

    public function addTable(array $headers, array $rows, array $widths)
    {
        if ($this->y < $this->bottomLimit + 40) {
            $this->startNewPage();
        }

        // Draw header
        $x = 50;
        $this->addDivider(1.0);
        
        // Save y for table header text
        $headerY = $this->y + 4;
        foreach ($headers as $i => $header) {
            $this->currentPageContent[] = 'BT /F2 9 Tf ' . $x . ' ' . $headerY . ' Td (' . self::escape(self::normalize($header)) . ') Tj ET';
            $x += $widths[$i];
        }
        
        $this->y -= 4;
        $this->addDivider(0.5);

        // Draw rows
        foreach ($rows as $row) {
            if ($this->y < $this->bottomLimit) {
                $this->startNewPage();
                // Redraw table headers on new page
                $x = 50;
                $this->addDivider(1.0);
                $headerY = $this->y + 4;
                foreach ($headers as $i => $header) {
                    $this->currentPageContent[] = 'BT /F2 9 Tf ' . $x . ' ' . $headerY . ' Td (' . self::escape(self::normalize($header)) . ') Tj ET';
                    $x += $widths[$i];
                }
                $this->y -= 4;
                $this->addDivider(0.5);
            }

            $x = 50;
            // Find max lines in any cell of this row to offset y properly
            $cellLines = [];
            $maxLines = 1;
            foreach ($row as $i => $cell) {
                $cellVal = self::normalize($cell);
                $lines = $this->wrapText($cellVal, $widths[$i] - 10, 9, false);
                $cellLines[$i] = $lines;
                $maxLines = max($maxLines, count($lines));
            }

            // Draw cells
            $rowY = $this->y;
            for ($lineIdx = 0; $lineIdx < $maxLines; $lineIdx++) {
                if ($rowY < $this->bottomLimit) {
                    // Draw a divider at the page boundary
                    $this->addDivider(0.5);
                    $this->startNewPage();
                    $rowY = $this->y;
                }
                $x = 50;
                foreach ($row as $i => $cell) {
                    $textLine = $cellLines[$i][$lineIdx] ?? '';
                    if ($textLine !== '') {
                        $this->currentPageContent[] = 'BT /F1 9 Tf ' . $x . ' ' . $rowY . ' Td (' . self::escape($textLine) . ') Tj ET';
                    }
                    $x += $widths[$i];
                }
                $rowY -= 12;
            }
            $this->y = $rowY - 4;
        }
        $this->addDivider(1.0);
    }

    private function wrapText(string $text, float $maxWidth, float $fontSize, bool $isBold): array
    {
        // Average char width estimate (approx 0.5 of font size)
        $charWidth = $fontSize * 0.55;
        $maxChars = max(1, (int)($maxWidth / $charWidth));

        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            // handle newlines inside text
            if (strpos($word, "\n") !== false) {
                $parts = explode("\n", $word);
                foreach ($parts as $idx => $part) {
                    if ($idx > 0) {
                        $lines[] = trim($currentLine);
                        $currentLine = $part;
                    } else {
                        if ($currentLine === '') {
                            $currentLine = $part;
                        } else {
                            $currentLine .= ' ' . $part;
                        }
                    }
                }
                continue;
            }

            if ($currentLine === '') {
                $currentLine = $word;
            } elseif (strlen($currentLine . ' ' . $word) <= $maxChars) {
                $currentLine .= ' ' . $word;
            } else {
                $lines[] = trim($currentLine);
                $currentLine = $word;
            }
        }
        if ($currentLine !== '') {
            $lines[] = trim($currentLine);
        }

        return array_filter($lines);
    }

    public function render(): string
    {
        if (!empty($this->currentPageContent)) {
            $this->pages[] = $this->currentPageContent;
            $this->currentPageContent = [];
        }

        $pdf = "%PDF-1.4\n";
        $objects = [];
        $objCounter = 1;

        $addObject = function (string $body) use (&$pdf, &$objects, &$objCounter): int {
            $num = $objCounter++;
            $objects[$num] = strlen($pdf);
            $pdf .= $num . " 0 obj\n" . $body . "\nendobj\n";
            return $num;
        };

        // Pre-reserve Catalog and Pages obj numbers
        $catalogObjNum = 1;
        $pagesObjNum = 2;
        $objCounter = 3;

        // Fonts
        $font1ObjNum = $addObject('<</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>');
        $font2ObjNum = $addObject('<</Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold>>');

        $pageObjNums = [];
        foreach ($this->pages as $pageIndex => $pageCmds) {
            $contentStr = implode("\n", $pageCmds) . "\n";
            $contentObjNum = $addObject('<</Length ' . strlen($contentStr) . ">>\nstream\n" . $contentStr . "endstream");
            
            // Page object
            $pageObjNum = $objCounter++;
            $pageObjNums[] = $pageObjNum;
        }

        // Write Pages and Catalog
        $kidsStr = '[' . implode(' 0 R ', $pageObjNums) . ' 0 R]';
        
        // Catalog
        $objects[$catalogObjNum] = strlen($pdf);
        $pdf .= $catalogObjNum . " 0 obj\n<</Type /Catalog /Pages " . $pagesObjNum . " 0 R>>\nendobj\n";

        // Pages
        $objects[$pagesObjNum] = strlen($pdf);
        $pdf .= $pagesObjNum . " 0 obj\n<</Type /Pages /Kids " . $kidsStr . " /Count " . count($pageObjNums) . ">>\nendobj\n";

        // Now write each Page object content
        foreach ($this->pages as $pageIndex => $pageCmds) {
            $pageObjNum = $pageObjNums[$pageIndex];
            // Since object ID is already allocated, calculate content number which is pageObjNum - 1
            $contentObjNum = $pageObjNum - 1;
            
            $objects[$pageObjNum] = strlen($pdf);
            $pdf .= $pageObjNum . " 0 obj\n<</Type /Page /Parent " . $pagesObjNum . " 0 R /MediaBox [0 0 595 842] /Resources <</Font <</F1 " . $font1ObjNum . " 0 R /F2 " . $font2ObjNum . " 0 R>>>> /Contents " . $contentObjNum . " 0 R>>\nendobj\n";
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . $objCounter . "\n0000000000 65535 f \n";

        for ($i = 1; $i < $objCounter; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $objects[$i] ?? 0);
        }

        $pdf .= "trailer\n<</Size " . $objCounter . " /Root " . $catalogObjNum . " 0 R>>\nstartxref\n" . $xrefPosition . "\n%%EOF";

        return $pdf;
    }

    private static function normalize(string $text): string
    {
        $text = (string) $text;

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
            if ($converted !== false) {
                return $converted;
            }
        }

        return preg_replace('/[^\x20-\x7E\n]/', '?', $text) ?? $text;
    }

    private static function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
