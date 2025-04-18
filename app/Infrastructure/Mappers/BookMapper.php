<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Books\Entities\Book;
use App\Domain\Books\ValueObjects\ISBN;
use App\Domain\Books\ValueObjects\Title;
use App\Domain\Books\ValueObjects\AuthorName;

/**
 * Transforms a book object from NYT JSON
 */
final class BookMapper
{
    public static function fromListItem(array $raw): Book
    {
        $primary = self::pickPrimaryIsbn($raw);

        if ($primary === null) {
            throw new \RuntimeException('Book row missing ISBN');
        }

        $primaryIsbn  = $raw['primary_isbn13'] ?? $raw['primary_isbn10'] ?? $raw['isbns'][0]['isbn13'] ?? $raw['isbns'][0]['isbn10'];

        return new Book(
            new ISBN((string) $primaryIsbn),
            new Title($raw['title'] ?? $raw['book_details'][0]['title'] ?? ''),
            new AuthorName($raw['author'] ?? $raw['book_details'][0]['author'] ?? ''),
            $raw['description'] ?? $raw['book_details'][0]['description'] ?? null,
            $raw['rank'] ?? null,
            $raw['weeks_on_list'] ?? null,
            $raw['publisher'] ?? $raw['book_details'][0]['publisher'] ?? null,
            $raw['amazon_product_url'] ?? null,
            self::collectIsbns($raw)
        );
    }

    /** @param array $raw @return ISBN[] */
    private static function collectIsbns(array $raw): array
    {
        $set = [];

        foreach ($raw['isbns'] ?? [] as $pair) {
            foreach (['isbn13', 'isbn10'] as $k) {
                if (empty($pair[$k])) {
                    continue;
                }
                try {
                    $set[] = new ISBN($pair[$k]);
                } catch (\InvalidArgumentException) {
                    // skip malformed ISBNs
                }
            }
        }

        foreach ($raw['ranks_history'] ?? [] as $hist) {
            foreach (['primary_isbn13', 'primary_isbn10'] as $k) {
                if (empty($hist[$k])) {
                    continue;
                }
                try {
                    $set[] = new ISBN($hist[$k]);
                } catch (\InvalidArgumentException) {
                    continue;
                }
            }
        }

        $unique = [];
        foreach ($set as $isbn) {
            $unique[$isbn->value()] = $isbn;
        }

        return array_values($unique);
    }

    private static function pickPrimaryIsbn(array $raw): ?string
    {
        foreach (['primary_isbn13','primary_isbn10'] as $k) {
            if (!empty($raw[$k])) return $raw[$k];
        }

        if (!empty($raw['isbns'][0]['isbn13'])) return $raw['isbns'][0]['isbn13'];
        if (!empty($raw['isbns'][0]['isbn10'])) return $raw['isbns'][0]['isbn10'];

        if (!empty($raw['isbn13'][0])) return $raw['isbn13'][0];

        return null;
    }
}
