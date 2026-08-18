<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function preview(Certificate $certificate): View
    {
        $isOwner = $certificate->user_id === auth()->id();
        $isCourseOwner = auth()->check() && auth()->user()->id === ($certificate->course?->user_id ?? null);
        $isAdmin = auth()->user()?->isAdmin() === true;

        if (! $isOwner && ! $isCourseOwner && ! $isAdmin) {
            abort(403, 'You do not have access to this certificate.');
        }

        $certificate->load('user', 'course');

        return view('dashboard.certificate-preview', [
            'certificate' => $certificate,
            'student' => $certificate->user,
            'course' => $certificate->course,
        ]);
    }

    public function download(Certificate $certificate): Response
    {
        $isOwner = $certificate->user_id === auth()->id();
        $isCourseOwner = auth()->check() && auth()->user()->id === ($certificate->course?->user_id ?? null);
        $isAdmin = auth()->user()?->isAdmin() === true;

        if (! $isOwner && ! $isCourseOwner && ! $isAdmin) {
            abort(403);
        }

        $certificate->load('user', 'course');

        $pdf = Pdf::loadView('pdfs.certificate', [
            'certificate' => $certificate,
            'student' => $certificate->user,
            'course' => $certificate->course,
        ]);

        return $pdf->download("certificate-{$certificate->course->slug}.pdf");
    }
}
