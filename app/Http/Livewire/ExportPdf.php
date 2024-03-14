<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade as PDF;

class ExportPdf extends Component
{
    public function exportPdf()
    {
        // (Optional) Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Generate and download the PDF file
        $pdfContent = $dompdf->output();
        return Response::make($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="exported_file.pdf"'
        ]);

        // Generate PDF using DomPDF
        $pdf = PDF::loadView('pdf.export', compact('data'));

        // Download PDF file
        return $pdf->download('exported_data.pdf');
    }

    public function render()
    {
        return view('livewire.export-pdf');
    }
}
