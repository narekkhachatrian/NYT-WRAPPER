<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Books\Entities\Book;
use App\Domain\Books\ValueObjects\ISBN;
use App\Domain\Books\ValueObjects\Title;
use App\Domain\Books\ValueObjects\AuthorName;

final class BookMapper
{
    public static function fromListItem(array $raw): Book
    {
        // 1) pick primary ISBN or fall back
        try {
            $primary = new ISBN(
                $raw['primary_isbn13']
                ?? $raw['primary_isbn10']
                ?? ''
            );
        } catch (\InvalidArgumentException) {
            $all = self::collectIsbns($raw);
            if (empty($all)) {
                throw new \RuntimeException('No valid ISBNs for book row');
            }
            $primary = $all[0];
        }

        // 2) collect all other valid ISBNs
        $allIsbns = self::collectIsbns($raw);

        // 3) construct the Book using Title & AuthorName VOs
        return new Book(
            $primary,
            new Title($raw['title'] ?? ''),
            new AuthorName($raw['author'] ?? ''),
            $raw['description'] ?? null,
            $raw['rank'] ?? null,
            $raw['weeks_on_list'] ?? null,
            $raw['publisher'] ?? null,
            $raw['amazon_product_url'] ?? null,
            $allIsbns
        );
    }

    private static function collectIsbns(array $raw): array
    {
        $set = [];

        foreach ($raw['isbns'] ?? [] as $pair) {
            foreach (['isbn13', 'isbn10'] as $f) {
                if (!empty($pair[$f])) {
                    try {
                        $set[] = new ISBN($pair[$f]);
                    } catch (\InvalidArgumentException) {
                        // skip invalid
                    }
                }
            }
        }

        foreach ($raw['ranks_history'] ?? [] as $hist) {
            foreach (['primary_isbn13', 'primary_isbn10'] as $f) {
                if (!empty($hist[$f])) {
                    try {
                        $set[] = new ISBN($hist[$f]);
                    } catch (\InvalidArgumentException) {
                        // skip invalid
                    }
                }
            }
        }

        // de‑duplicate
        $unique = [];
        foreach ($set as $isbn) {
            $unique[$isbn->value()] = $isbn;
        }

        return array_values($unique);
    }
}
