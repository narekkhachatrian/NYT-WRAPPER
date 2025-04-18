<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

final class ISBN
{
    public function __construct(private string $value)
    {
        // Allow 13 digits  ‑‑ OR ‑‑ 9 digits + digit/X
        if (!preg_match('/^(?:\d{13}|\d{9}[0-9Xx])$/', $this->value)) {
            throw new \InvalidArgumentException("Invalid ISBN: {$this->value}");
        }

        $this->value = strtoupper($this->value);
    }


    public function value(): string
    {
        return $this->value;
    }

    public function isIsbn13(): bool
    {
        return \strlen($this->value) === 13;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
