<?php

// File: app/Services/ReportGeneratorService.php

namespace App\Services;

use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ReportGeneratorService
{
    public function generatePDF(Activity $activity)
    {
        // Load view HTML dan passing data kegiatan
        $pdf = Pdf::loadView('reports.activity_pdf', compact('activity'));
        
        // Return file PDF untuk di-download
        return $pdf->download('Laporan_Kegiatan_' . $activity->title . '.pdf');
    }

    public function generateWord(Activity $activity)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Contoh sederhana penambahan teks ke Word
        $section->addText('LAPORAN KEGIATAN PENYULUH KEHUTANAN', ['bold' => true, 'size' => 16]);
        $section->addText('Judul: ' . $activity->title);
        $section->addText('Tanggal: ' . $activity->activity_date->format('d M Y'));
        $section->addText('Hasil: ' . $activity->result);

        // Nanti di Tahap View/Logic lanjutan, kita tambahkan loop untuk foto dan format tabel di sini

        $fileName = 'Laporan_Kegiatan_' . $activity->title . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}