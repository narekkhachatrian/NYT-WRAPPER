<?php

declare(strict_types=1);

namespace App\Domain\Books\Repositories;

use App\Domain\Books\Entities\Book;
use App\Domain\Books\ValueObjects\Offset;

interface HistoryRepositoryInterface
{
    /**
     * @param array{author?:string,title?:string,isbn?:string[]} $filters
     * @return array{total:int,offset:Offset,books:Book[]}
     */
    public function search(array $filters, Offset $offset): array;
}
