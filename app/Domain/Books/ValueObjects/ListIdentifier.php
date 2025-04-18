<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

final class ListIdentifier
{
    public function __construct(private string $encoded)
    {
        if (!\preg_match('/^[a-z0-9\-]+$/', $encoded)) {
            throw new \InvalidArgumentException("Bad list identifier: {$encoded}");
        }
    }

    public function value(): string
    {
        return $this->encoded;
    }

    public function __toString(): string
    {
        return $this->encoded;
    }
}
