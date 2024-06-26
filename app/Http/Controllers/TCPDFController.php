<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDI;

class TCPDFController extends Controller
{
    public function downloadPdf(Request $request)
    {
        $pdf = new TCPDI();
        $pdf->SetAutoPageBreak(true, 0);
        // Import existing PDF
        $pdf->setSourceFile(public_path('cv.pdf'));
        $tplIdx = $pdf->importPage(1);
        // Get the size and orientation of the imported PDF page
        $importedPageSize = $pdf->getTemplateSize($tplIdx);
        $importedPageOrientation = ($importedPageSize['w'] > $importedPageSize['h']) ? 'L' : 'P';

        // Add a page with the same orientation as the imported PDF
        $pdf->AddPage($importedPageOrientation);

        // Adjust position and size of the imported PDF to match the page size
        $pdf->useTemplate($tplIdx, 0, 0, $importedPageSize['w'], $importedPageSize['h'], true);
        $turun = $importedPageSize['h'] - 35;

        // Set signature
        $certificate = 'file://' . base_path() . '/storage/app/certificate/esertifikat.crt';
        $info = array(
            'Name' => 'Danang Ugroseno',
            'Location' => 'Blitar',
            'Reason' => 'E-Sertifikat',
            'ContactInfo' => 'danang.ugroseno@gmail.com',
        );
        $pdf->setSignature($certificate, $certificate, 'password', '', 2, $info);

        // Set font and metadata
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetCreator('Danang Ugroseno');
        $pdf->SetTitle('E-Sertificate');
        $pdf->SetAuthor('Danang Ugroseno');
        $pdf->SetSubject('E-Sertificate');

        // Add QR code image
        $pdf->Image(public_path('qr.png'), 5, $turun, 30, 30, 'PNG');

        // Set appearance of signature
        $pdf->setSignatureAppearance(5, $turun, 30, 30);

        // Output the PDF
        $pdf->Output('example.pdf', 'I');
        echo "PDF Generated Successfully";
    }
}
