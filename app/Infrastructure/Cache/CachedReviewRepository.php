<?php

namespace App\Infrastructure\Cache;

use App\Domain\Books\Repositories\ReviewRepositoryInterface;
use App\Domain\Books\Entities\Review;

class CachedReviewRepository extends CachedRepositoryDecorator implements ReviewRepositoryInterface
{
    public function search(array $filters): array
    {
        /** @var Review[] */
        return parent::__call(__FUNCTION__, func_get_args());
    }
}
