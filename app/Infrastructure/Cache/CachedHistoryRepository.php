<?php

namespace App\Infrastructure\Cache;

use App\Domain\Books\Repositories\HistoryRepositoryInterface;
use App\Domain\Books\ValueObjects\Offset;
use App\Domain\Books\Entities\Book;

class CachedHistoryRepository extends CachedRepositoryDecorator implements HistoryRepositoryInterface
{
    public function search(array $filters, Offset $offset): array
    {
        /** @var array{total:int,offset:Offset,books:Book[]} */
        return parent::__call(__FUNCTION__, func_get_args());
    }
}
