<?php

declare(strict_types=1);

namespace App\Domain\Books\Repositories;

use App\Domain\Books\Entities\ListName;
use App\Domain\Books\Entities\ListSnapshot;
use App\Domain\Books\ValueObjects\ListIdentifier;
use App\Domain\Books\ValueObjects\NytDate;
use App\Domain\Books\ValueObjects\Offset;

interface ListRepositoryInterface
{
    /**
 * @return ListName[]
*/
    public function allNames(): array;

    public function snapshot(
        NytDate $date,
        ListIdentifier $list,
        Offset $offset
    ): ListSnapshot;
}
