<?php

namespace App\Services;

use ZipArchive;

class XlsxExporter
{
    /**
     * Generate a genuine .xlsx file from headers and rows and return the temp file path.
     *
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, string|int|float|null>>  $rows
     * @param  string  $sheetName
     * @return string
     */
    public static function generate(array $headers, array $rows, string $sheetName = 'Tender Report'): string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();
        if ($zip->open($tmpFile, ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create XLSX archive.');
        }

        $safeSheetName = mb_substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '', $sheetName) ?: 'Report', 0, 31);

        $zip->addFromString('[Content_Types].xml', self::contentTypesXml());
        $zip->addFromString('_rels/.rels', self::relsXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRelsXml());
        $zip->addFromString('xl/workbook.xml', self::workbookXml($safeSheetName));
        $zip->addFromString('xl/styles.xml', self::stylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheetXml($headers, $rows));

        $zip->close();

        return $tmpFile;
    }

    private static function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private static function colLetter(int $index): string
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26) - 1;
        }
        return $letter;
    }

    private static function sheetXml(array $headers, array $rows): string
    {
        $colCount = count($headers);
        $widths = array_fill(0, $colCount, 14);

        foreach ($headers as $c => $h) {
            $widths[$c] = max($widths[$c], mb_strlen((string) $h) + 5);
        }
        foreach ($rows as $row) {
            foreach ($row as $c => $val) {
                $len = mb_strlen((string) ($val ?? ''));
                $widths[$c] = min(48, max($widths[$c] ?? 14, $len + 4));
            }
        }

        $colsXml = '<cols>';
        foreach ($widths as $c => $w) {
            $idx = $c + 1;
            $colsXml .= '<col min="' . $idx . '" max="' . $idx . '" width="' . $w . '" customWidth="1"/>';
        }
        $colsXml .= '</cols>';

        $sheetData = '<sheetData>';

        // Header row (style 1 = bold white on dark navy/slate)
        $sheetData .= '<row r="1" ht="24" customHeight="1">';
        foreach ($headers as $c => $header) {
            $ref = self::colLetter($c) . '1';
            $sheetData .= '<c r="' . $ref . '" t="inlineStr" s="1"><is><t>' . self::xmlEscape((string) $header) . '</t></is></c>';
        }
        $sheetData .= '</row>';

        // Data rows
        $rowNum = 2;
        foreach ($rows as $row) {
            $sheetData .= '<row r="' . $rowNum . '">';
            foreach ($row as $c => $val) {
                $ref = self::colLetter($c) . $rowNum;
                if (is_int($val) || is_float($val)) {
                    $sheetData .= '<c r="' . $ref . '" s="2"><v>' . $val . '</v></c>';
                } else {
                    $text = (string) ($val ?? '-');
                    $sheetData .= '<c r="' . $ref . '" t="inlineStr" s="3"><is><t>' . self::xmlEscape($text) . '</t></is></c>';
                }
            }
            $sheetData .= '</row>';
            $rowNum++;
        }

        $sheetData .= '</sheetData>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView tabSelected="1" workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . $colsXml
            . $sheetData
            . '</worksheet>';
    }

    private static function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF1E3A8A"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="2">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left style="thin"><color rgb="FFD6D3D1"/></left><right style="thin"><color rgb="FFD6D3D1"/></right><top style="thin"><color rgb="FFD6D3D1"/></top><bottom style="thin"><color rgb="FFD6D3D1"/></bottom><diagonal/></border>'
            . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="4">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '<xf numFmtId="4" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            . '</cellXfs>'
            . '</styleSheet>';
    }

    private static function workbookXml(string $sheetName): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . self::xmlEscape($sheetName) . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private static function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private static function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private static function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }
}
