<?php

namespace App\Support;

final class BetterplaceHelper
{
    public static function formatCurrency(float|int $amount): string
    {
        return number_format($amount, 2, ',', '.');
    }

    public static function pickFirstString(mixed ...$values): string
    {
        foreach ($values as $value) {
            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return '';
    }
}
