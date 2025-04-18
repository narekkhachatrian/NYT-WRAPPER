<?php

declare(strict_types=1);

namespace App\Domain\Books\Entities;

use App\Domain\Books\ValueObjects\ListIdentifier;
use Carbon\CarbonImmutable;

/**
 * Metadata row returned by /lists/names.json
 */
final readonly class ListName
{
    public function __construct(
        private ListIdentifier  $id,
        private string          $displayName,
        private CarbonImmutable $oldestDate,
        private CarbonImmutable $newestDate,
        private string          $updateFrequency
    )
    {
    }

    public function id(): ListIdentifier
    {
        return $this->id;
    }
    public function displayName(): string
    {
        return $this->displayName;
    }
    public function oldestDate(): CarbonImmutable
    {
        return $this->oldestDate;
    }
    public function newestDate(): CarbonImmutable
    {
        return $this->newestDate;
    }
    public function updateFrequency(): string
    {
        return $this->updateFrequency;
    }
}
