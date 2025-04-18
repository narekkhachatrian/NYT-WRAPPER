<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

use InvalidArgumentException;

final class ISBN
{
    private string $value;

    /**
     * @param string $raw The raw ISBN input (may include hyphens, spaces, lower‑case x, etc.)
     * @throws InvalidArgumentException if the ISBN is not a valid ISBN‑10 or ISBN‑13.
     */
    public function __construct(string $raw)
    {
        $normalized = $this->normalize($raw);

        if ($this->isIsbn10($normalized)) {
            if (!self::isValidIsbn10($normalized)) {
                throw new InvalidArgumentException("Invalid ISBN‑10: {$raw}");
            }
        } elseif ($this->isIsbn13($normalized)) {
            if (!self::isValidIsbn13($normalized)) {
                throw new InvalidArgumentException("Invalid ISBN‑13: {$raw}");
            }
        } else {
            throw new InvalidArgumentException("ISBN must be exactly 10 or 13 characters (digits or X): {$raw}");
        }

        $this->value = $normalized;
    }

    /**
     * Returns the normalized ISBN string (digits and possibly final 'X').
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Strip spaces, hyphens, dashes, etc., and uppercase any 'x'.
     */
    private function normalize(string $input): string
    {
        $stripped = str_replace(
            [' ', '-', '‐', '–', '—'],
            '',
            $input
        );

        return strtoupper($stripped);
    }

    private function isIsbn10(string $s): bool
    {
        return (bool) preg_match('/^\d{9}[0-9X]$/', $s);
    }

    private function isIsbn13(string $s): bool
    {
        return (bool) preg_match('/^\d{13}$/', $s);
    }

    /**
     * Validate ISBN‑10 via mod‑11 weighted sum:
     *  sum = 1*d1 + 2*d2 + … + 9*d9,  remainder = sum % 11,
     *  check digit = (remainder == 10 ? 'X' : (string)remainder).
     */
    private static function isValidIsbn10(string $s): bool
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ($i + 1) * (int) $s[$i];
        }
        $remainder = $sum % 11;
        $expected = $remainder === 10 ? 'X' : (string) $remainder;
        return $s[9] === $expected;
    }

    /**
     * Validate ISBN‑13 via mod‑10 weighted sum:
     * weights alternate 1, 3 for the first 12 digits; check = (10 - sum%10) % 10.
     */
    private static function isValidIsbn13(string $s): bool
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $weight = ($i % 2 === 0) ? 1 : 3;
            $sum    += (int) $s[$i] * $weight;
        }
        $mod   = $sum % 10;
        $check = (10 - $mod) % 10;
        return (int) $s[12] === $check;
    }
}
