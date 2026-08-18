<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        if (!in_array($locale, ['en', 'ar', 'es', 'bn'])) {
            $locale = 'en';
        }

        session()->put('locale', $locale);
        app()->setLocale($locale);

        return back();
    }
}
