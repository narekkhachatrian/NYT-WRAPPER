<?php

declare(strict_types=1);

namespace App\Domain\Books\ValueObjects;

use BadMethodCallException;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

final class NytDate
{
    private ?CarbonImmutable $date;
    private bool $isCurrent;

    private function __construct(?CarbonImmutable $date, bool $isCurrent)
    {
        $this->date      = $date;
        $this->isCurrent = $isCurrent;
    }

    /**
     * @param string $input "current" or "YYYY-MM-DD"
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromString(string $input): self
    {
        if (strtolower($input) === 'current') {
            return new self(null, true);
        }

        $dt = CarbonImmutable::createFromFormat('Y-m-d', $input);

        if (!($dt instanceof CarbonImmutable) || $dt->format('Y-m-d') !== $input) {
            throw new InvalidArgumentException("Invalid date string: {$input}");
        }

        return new self($dt, false);
    }

    /**
     * @return string "current" or the date in YYYY-MM-DD
     */
    public function asPathPart(): string
    {
        return $this->isCurrent ? 'current' : $this->date->toDateString();
    }

    /**
     * @return CarbonImmutable
     * @throws BadMethodCallException
     */
    public function value(): CarbonImmutable
    {
        if ($this->isCurrent || $this->date === null) {
            throw new BadMethodCallException("Cannot call value() on 'current'");
        }
        return $this->date;
    }
}
