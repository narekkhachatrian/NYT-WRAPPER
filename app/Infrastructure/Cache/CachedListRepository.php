<?php

namespace App\Infrastructure\Cache;

use App\Domain\Books\Repositories\ListRepositoryInterface;
use App\Domain\Books\ValueObjects\{NytDate,ListIdentifier,Offset};
use App\Domain\Books\Entities\{ListName,ListSnapshot};

class CachedListRepository extends CachedRepositoryDecorator implements ListRepositoryInterface
{
    public function allNames(): array
    {
        return parent::__call(__FUNCTION__, func_get_args());
    }

    public function snapshot(NytDate $date, ListIdentifier $list, Offset $offset): ListSnapshot
    {
        return parent::__call(__FUNCTION__, func_get_args());
    }
}
