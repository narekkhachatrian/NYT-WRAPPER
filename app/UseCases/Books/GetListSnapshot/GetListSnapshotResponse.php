<?php

declare(strict_types=1);

namespace App\UseCases\Books\GetListSnapshot;

use App\Domain\Books\Entities\ListSnapshot;

final class GetListSnapshotResponse
{
    public function __construct(public ListSnapshot $snapshot) {}
}
