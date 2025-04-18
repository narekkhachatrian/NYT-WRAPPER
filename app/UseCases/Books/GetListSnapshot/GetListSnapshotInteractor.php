<?php

declare(strict_types=1);

namespace App\UseCases\Books\GetListSnapshot;

use App\Domain\Books\Repositories\ListRepositoryInterface;
use App\Domain\Books\ValueObjects\{ListIdentifier,NytDate,Offset};

final class GetListSnapshotInteractor
{
    public function __construct(private ListRepositoryInterface $repo) {}

    public function execute(GetListSnapshotRequest $r): GetListSnapshotResponse
    {
        $snapshot = $this->repo->snapshot(
            NytDate::fromString($r->publishedDate),
            new ListIdentifier($r->list),
            new Offset($r->offset)
        );

        return new GetListSnapshotResponse($snapshot);
    }
}
