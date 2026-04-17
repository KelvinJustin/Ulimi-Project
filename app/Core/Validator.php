<?php
declare(strict_types=1);

namespace App\Core;

final class Validator
{
    public static function email(?string $value): bool
    {
        if (!is_string($value) || $value === '') {
            return false;
        }
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function str(?string $value, int $min = 1, int $max = 255): bool
    {
        if (!is_string($value)) {
            return false;
        }
        $len = mb_strlen(trim($value));
        return $len >= $min && $len <= $max;
    }

    public static function in(?string $value, array $allowed): bool
    {
        if (!is_string($value)) {
            return false;
        }
        return in_array($value, $allowed, true);
    }

    public static function numeric($value, float $min = 0, float $max = INF): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        $num = (float)$value;
        return $num >= $min && $num <= $max;
    }
}
