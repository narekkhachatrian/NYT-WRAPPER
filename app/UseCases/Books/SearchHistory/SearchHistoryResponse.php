<?php

declare(strict_types=1);

namespace App\UseCases\Books\SearchHistory;

use App\Domain\Books\Entities\Book;
use App\Domain\Books\ValueObjects\Offset;

/**
 * @property Book[] $books
 */
final class SearchHistoryResponse
{
    public function __construct(
        public int    $total,
        public Offset $offset,
        public array  $books
    )
    {
    }
}
