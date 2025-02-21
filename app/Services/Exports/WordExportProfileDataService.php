<?php

namespace App\Services\Exports;

use App\Modules\Infrastructure\Contracts\ExportInterface;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class WordExportProfileDataService implements ExportInterface
{
    public function export(array $data): ResponseInterface
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('Профиль пользователя', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addTextBreak(1);

        $table = $section->addTable([
            'borderSize'  => 6,
            'borderColor' => '999999',
            'cellMargin'  => 50,
        ]);

        foreach ($data as $key => $value) {
            $table->addRow();
            $table->addCell()->addText(strtoupper(str_replace('_', ' ', $key)), ['bold' => true]);
            $table->addCell()->addText((string)$value);
        }

        $fileName = 'Export_' . $data['id'] . '_' . time() . '.docx';

        ob_start();
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        $wordOutput = ob_get_clean();

        return response()
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($wordOutput);
    }
}