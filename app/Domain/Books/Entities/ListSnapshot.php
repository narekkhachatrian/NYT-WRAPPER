<?php

declare(strict_types=1);

namespace App\Domain\Books\Entities;

use App\Domain\Books\ValueObjects\ListIdentifier;
use App\Domain\Books\ValueObjects\Offset;
use Carbon\CarbonImmutable;

/**
 * Aggregate root describing one published list page
 * (20‑book slice determined by `offset`).
 */
final readonly class ListSnapshot
{
    /**
 * @param Book[] $books
*/
    public function __construct(
        private ListIdentifier  $listId,
        private string          $displayName,
        private CarbonImmutable $bestsellersDate,
        private CarbonImmutable $publishedDate,
        private array           $books,
        private int             $totalResults,
        private Offset          $offset,
    )
    {
    }

    public function listId(): ListIdentifier
    {
        return $this->listId;
    }
    public function displayName(): string
    {
        return $this->displayName;
    }
    public function bestsellersDate(): CarbonImmutable
    {
        return $this->bestsellersDate;
    }
    public function publishedDate(): CarbonImmutable
    {
        return $this->publishedDate;
    }

    /**
 * @return Book[]
*/
    public function books(): array
    {
        return $this->books;
    }

    public function totalResults(): int
    {
        return $this->totalResults;
    }

    public function offset(): Offset
    {
        return $this->offset;
    }

    public function hasMore(): bool
    {
        return $this->offset->value() + \count($this->books) < $this->totalResults;
    }
}
