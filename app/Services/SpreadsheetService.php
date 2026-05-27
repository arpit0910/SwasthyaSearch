<?php

namespace App\Services;

class SpreadsheetService
{
    private static function splitTwoPhones(string $value): array
    {
        // Split values like "value1, value2" into 2 columns.
        $parts = array_values(array_filter(array_map(
            static fn ($part) => trim($part),
            preg_split('/\s*,\s*/', $value) ?: []
        ), static fn ($part) => $part !== ''));

        return [
            $parts[0] ?? null,
            $parts[1] ?? null,
        ];
    }

    private static function hydratePhoneVariants(array $row): array
    {
        if (array_key_exists('country_code', $row)) {
            [$first, $second] = self::splitTwoPhones((string) ($row['country_code'] ?? ''));
            $row['country_code_1'] = $first;
            $row['country_code_2'] = $second;
        }

        if (array_key_exists('emergency_country_code', $row)) {
            [$first, $second] = self::splitTwoPhones((string) ($row['emergency_country_code'] ?? ''));
            $row['country_code_1'] = $row['country_code_1'] ?? $first;
            $row['country_code_2'] = $row['country_code_2'] ?? $second;
        }

        if (array_key_exists('emergency_phone', $row)) {
            [$first, $second] = self::splitTwoPhones((string) ($row['emergency_phone'] ?? ''));
            $row['phone_1'] = $row['phone_1'] ?? $first;
            $row['phone_2'] = $row['phone_2'] ?? $second;
        }

        if (array_key_exists('phone', $row)) {
            [$first, $second] = self::splitTwoPhones((string) ($row['phone'] ?? ''));
            $row['phone_1'] = $first;
            $row['phone_2'] = $second;
        }

        return $row;
    }

    /**
     * Parses an XLSX or CSV file and returns an array of associative arrays.
     */
    public static function parseSpreadsheet(string $filePath): array
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($ext === 'xlsx') {
            $zip = new \ZipArchive();
            if ($zip->open($filePath) === true) {
                // 1. Parse sharedStrings.xml
                $sharedStrings = [];
                if (($ssXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
                    $ss = simplexml_load_string($ssXml);
                    if ($ss && isset($ss->si)) {
                        foreach ($ss->si as $val) {
                            if (isset($val->t)) {
                                $sharedStrings[] = (string) $val->t;
                            } elseif (isset($val->r)) {
                                $text = '';
                                foreach ($val->r as $r) {
                                    if (isset($r->t)) {
                                        $text .= (string) $r->t;
                                    }
                                }
                                $sharedStrings[] = $text;
                            } else {
                                $sharedStrings[] = '';
                            }
                        }
                    }
                }

                // 2. Parse sheet1.xml
                $rows = [];
                if (($sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml')) !== false) {
                    $sheet = simplexml_load_string($sheetXml);
                    if ($sheet && isset($sheet->sheetData->row)) {
                        foreach ($sheet->sheetData->row as $rowObj) {
                            $rowVals = [];
                            $colIndex = 0;
                            foreach ($rowObj->c as $c) {
                                // Calculate column index from r attribute (e.g., A1, B1, C1)
                                $cellRef = (string) $c['r'];
                                $colLetters = preg_replace('/[0-9]/', '', $cellRef);
                                // Convert column letter to 0-based index
                                $currCol = 0;
                                $len = strlen($colLetters);
                                for ($i = 0; $i < $len; $i++) {
                                    $currCol = $currCol * 26 + (ord(strtoupper($colLetters[$i])) - 64);
                                }
                                $currCol -= 1; // 0-indexed

                                // Pad empty cells if any were skipped
                                while ($colIndex < $currCol) {
                                    $rowVals[] = '';
                                    $colIndex++;
                                }

                                $val = isset($c->v) ? (string) $c->v : '';
                                if (isset($c['t']) && (string) $c['t'] === 's') {
                                    $val = $sharedStrings[(int) $val] ?? $val;
                                }
                                $rowVals[] = $val;
                                $colIndex++;
                            }
                            if (!empty(array_filter($rowVals))) {
                                $rows[] = $rowVals;
                            }
                        }
                    }
                }
                $zip->close();

                if (!empty($rows)) {
                    $headers = array_shift($rows);
                    $headers = array_map(fn($h) => trim(strtolower($h)), $headers);
                    $data = [];
                    foreach ($rows as $r) {
                        // Pad row to match headers length
                        $r = array_pad($r, count($headers), '');
                        // Truncate if longer
                        $r = array_slice($r, 0, count($headers));
                        $row = array_combine($headers, $r);
                        $data[] = self::hydratePhoneVariants($row);
                    }
                    return $data;
                }
            }
        }

        // Fallback to CSV parsing
        $data = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = fgetcsv($handle);
            if ($headers) {
                $headers = array_map(fn($h) => trim(strtolower($h)), $headers);
                while (($row = fgetcsv($handle)) !== false) {
                    if (!empty(array_filter($row))) {
                        $row = array_pad($row, count($headers), '');
                        $row = array_slice($row, 0, count($headers));
                        $parsedRow = array_combine($headers, $row);
                        $data[] = self::hydratePhoneVariants($parsedRow);
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
