<?php

declare(strict_types=1);

namespace Devralint\Cuit;

final class Validator
{
    private const array WEIGHTS = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];

    public static function normalize(string $value): string
    {
        return preg_replace('/\D/', '', $value) ?? '';
    }

    public static function hasValidFormat(string $value): bool
    {
        $digits = self::normalize($value);

        return strlen($digits) === 11;
    }

    public static function checkDigit(string $normalized): int
    {
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $normalized[$i] * self::WEIGHTS[$i];
        }

        $remainder = $sum % 11;
        $dv = 11 - $remainder;

        if ($dv === 11) {
            return 0;
        }

        if ($dv === 10) {
            return 9;
        }

        return $dv;
    }

    public static function isValid(string $value): bool
    {
        if (!self::hasValidFormat($value)) {
            return false;
        }

        $normalized = self::normalize($value);

        return self::checkDigit($normalized) === (int) $normalized[10];
    }
}
