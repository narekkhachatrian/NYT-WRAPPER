<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

final readonly class Offset
{
    public function __construct(private int $value = 0)
    {
        if ($value < 0 || $value % 20 !== 0) {
            throw new \InvalidArgumentException('Offset must be 0 or multiple of 20');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function next(): self
    {
        return new self($this->value + 20);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
