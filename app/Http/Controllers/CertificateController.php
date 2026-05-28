<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    public function download(Certificate $certificate): Response
    {
        if ($certificate->user_id !== auth()->id() && !auth()->user()?->isAdmin()) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdfs.certificate', [
            'certificate' => $certificate,
            'student' => $certificate->user,
            'course' => $certificate->course,
        ]);

        return $pdf->download("certificate-{$certificate->course->slug}.pdf");
    }
}
