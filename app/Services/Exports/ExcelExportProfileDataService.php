<?php

namespace App\Services\Exports;

use App\Modules\Infrastructure\Contracts\ExportInterface;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportProfileDataService implements ExportInterface
{
    /**
     * @param array $data
     * @return ResponseInterface
     */
    public function export(array $data): ResponseInterface
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columnIndex = 0;

        foreach ($data as $key => $value) {
            $columnLetter = chr(65 + $columnIndex);

            $sheet->setCellValue("{$columnLetter}1", $key);
            $sheet->setCellValue("{$columnLetter}2", $value);

            $columnIndex++;
        }

        $fileName = 'Export_' . $data['id'] . '_' . time() . '.xlsx';

        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return response()
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($excelOutput);
    }
}