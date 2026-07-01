<?php

namespace App\Helpers;

use App\Models\Currency;
use App\Models\SchoolSetting;

class CurrencyHelper
{
    public static function format(float $amount, ?string $currencyCode = null): string
    {
        $school = SchoolSetting::getInstance();
        $code = $currencyCode ?? $school->currency_code ?? 'USD';
        $symbol = $school->currency_symbol ?? '$';
        $position = $school->currency_position ?? 'left';

        $currency = Currency::where('code', $code)->where('status', 'active')->first();

        if ($currency && $currency->rate > 0 && !$currency->is_default) {
            $defaultCurrency = Currency::where('is_default', true)->where('status', 'active')->first();
            if ($defaultCurrency && $defaultCurrency->rate > 0) {
                $amount = ($amount / $defaultCurrency->rate) * $currency->rate;
            }
        }

        $formatted = number_format($amount, 2);

        if ($position === 'left') {
            return $symbol . $formatted;
        }
        return $formatted . $symbol;
    }

    public static function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $from = Currency::where('code', $fromCurrency)->where('status', 'active')->first();
        $to = Currency::where('code', $toCurrency)->where('status', 'active')->first();

        if (!$from || !$to || $from->rate <= 0) {
            return $amount;
        }

        return ($amount / $from->rate) * $to->rate;
    }
}
