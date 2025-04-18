<?php

declare(strict_types=1);

namespace App\Domain\Books\Repositories;

use App\Domain\Books\Entities\Review;

interface ReviewRepositoryInterface
{
    /**
     * At least one of author|title|isbn must be provided.
     *
     * @param array{author?:string,title?:string,isbn?:string} $filters
     * @return Review[]
     */
    public function search(array $filters): array;
}
