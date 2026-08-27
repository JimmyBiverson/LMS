<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    public function preview(Certificate $certificate): Response
    {
        if ($certificate->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        return $this->renderPdf($certificate, 'inline');
    }

    public function download(Certificate $certificate): Response
    {
        if ($certificate->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        return $this->renderPdf($certificate, 'download');
    }

    protected function renderPdf(Certificate $certificate, string $mode): Response
    {
        $certificate->load('user', 'course');

        $pdf = Pdf::loadView('pdfs.certificate', [
            'certificate' => $certificate,
            'student' => $certificate->user,
            'course' => $certificate->course,
        ]);

        $filename = "certificate-{$certificate->course->slug}.pdf";

        return $mode === 'inline'
            ? $pdf->stream($filename)
            : $pdf->download($filename);
    }
}
