<?php

declare(strict_types=1);

namespace App\Domain\Books\Entities;

use App\Domain\Books\ValueObjects\Title;
use App\Domain\Books\ValueObjects\AuthorName;
use App\Domain\Books\ValueObjects\ISBN;
use Carbon\CarbonImmutable;

/**
 * Editorial review row returned by /reviews.json
 */
readonly class Review
{
    /**
 * @param ISBN[] $isbn13
*/
    public function __construct(
        private string          $url,
        private CarbonImmutable $publicationDate,
        private string          $byline,
        private Title           $bookTitle,
        private AuthorName      $bookAuthor,
        private string          $summary,
        private array           $isbn13,
    )
    {
    }

    public function url(): string
    {
        return $this->url;
    }
    public function publicationDate(): CarbonImmutable
    {
        return $this->publicationDate;
    }
    public function byline(): string
    {
        return $this->byline;
    }
    public function bookTitle(): Title
    {
        return $this->bookTitle;
    }
    public function bookAuthor(): AuthorName
    {
        return $this->bookAuthor;
    }
    public function summary(): string
    {
        return $this->summary;
    }

    /**
 * @return ISBN[]
*/
    public function isbn13(): array
    {
        return $this->isbn13;
    }
}
