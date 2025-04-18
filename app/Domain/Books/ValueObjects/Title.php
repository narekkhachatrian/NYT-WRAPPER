<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

final class Title
{
    public function __construct(private string $value)
    {
        $trimmed = \trim($value);
        if ($trimmed === '' || \strlen($trimmed) > 255) {
            throw new \InvalidArgumentException('Invalid Title length');
        }
        $this->value = $trimmed;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
