<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Books\Entities\Review;
use App\Domain\Books\ValueObjects\AuthorName;
use App\Domain\Books\ValueObjects\ISBN;
use App\Domain\Books\ValueObjects\Title;
use Carbon\CarbonImmutable;

final class ReviewMapper
{
    public static function fromApi(array $row): Review
    {
        $isbnObjects = array_map(
            fn (string $i) => new ISBN($i),
            $row['isbn13'] ?? []
        );

        return new Review(
            $row['url'],
            CarbonImmutable::parse($row['publication_dt']),
            $row['byline'],
            new Title($row['book_title']),
            new AuthorName($row['book_author']),
            $row['summary'],
            $isbnObjects
        );
    }
}
