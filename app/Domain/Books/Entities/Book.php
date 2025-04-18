<?php

declare(strict_types=1);

namespace App\Domain\Books\Entities;

use App\Domain\Books\ValueObjects\ISBN;
use App\Domain\Books\ValueObjects\Title;
use App\Domain\Books\ValueObjects\AuthorName;

/**
 * A single entry on a Best‑Seller list or in history search.
 */
final readonly class Book
{
    /** @param ISBN[] $allIsbns */
    public function __construct(
        private ISBN       $primaryIsbn,
        private Title      $title,
        private AuthorName $author,
        private ?string    $description = null,
        private ?int       $rank = null,
        private ?int       $weeksOnList = null,
        private ?string    $publisher = null,
        private ?string    $amazonUrl = null,
        private array      $allIsbns = [],
    ) {}

    public function primaryIsbn(): ISBN { return $this->primaryIsbn; }
    public function title(): Title { return $this->title; }
    public function author(): AuthorName { return $this->author; }
    public function description(): ?string { return $this->description; }
    public function rank(): ?int { return $this->rank; }
    public function weeksOnList(): ?int { return $this->weeksOnList; }
    public function publisher(): ?string { return $this->publisher; }
    public function amazonUrl(): ?string { return $this->amazonUrl; }
    /** @return ISBN[] */
    public function allIsbns(): array { return $this->allIsbns; }
}
