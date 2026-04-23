<?php

class CsvExporter {

    public static function export($filename, $headers, $data, $columnsToExport = null) {
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, $headers);

        foreach ($data as $row) {
            $rowdata = [];

            if ($columnsToExport) {
                foreach ($columnsToExport as $colKey) {
                    $rowdata[] = $row[$colKey] ?? '';
                }
            } else {
                $rowdata = array_values($row);
            }

            fputcsv($output, $rowdata);
        }

        fclose($output);
        exit();
    }
}

?>