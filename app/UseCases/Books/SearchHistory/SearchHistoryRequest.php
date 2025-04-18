<?php

declare(strict_types=1);

namespace App\UseCases\Books\SearchHistory;

final class SearchHistoryRequest
{
    /** @param string[]|null $isbn13 */
    public function __construct(
        public ?string $author,
        public ?string $title,
        public ?array  $isbn13,
        public int     $offset = 0
    ) {}
}
