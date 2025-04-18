<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

final class AuthorName
{
    public function __construct(private string $full)
    {
        $trim = \trim($full);
        if ($trim === '' || \strlen($trim) > 120) {
            throw new \InvalidArgumentException('Invalid author name');
        }
        $this->full = $trim;
    }

    public function full(): string
    {
        return $this->full;
    }

    public function __toString(): string
    {
        return $this->full;
    }
}
