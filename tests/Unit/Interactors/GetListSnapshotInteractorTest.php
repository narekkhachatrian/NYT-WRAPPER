<?php

namespace Tests\Unit\Interactors;

use App\UseCases\Books\GetListSnapshot\{GetListSnapshotInteractor, GetListSnapshotRequest};
use App\Domain\Books\Repositories\ListRepositoryInterface;
use App\Domain\Books\Entities\ListSnapshot;
use App\Domain\Books\ValueObjects\{ListIdentifier, Offset, NytDate};
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class GetListSnapshotInteractorTest extends TestCase
{
    public function test_execute_returns_snapshot()
    {
        $dummySnap = new ListSnapshot(
            new ListIdentifier('foo'),
            'Foo List',
            CarbonImmutable::parse('2025-01-01'),
            CarbonImmutable::parse('2025-01-02'),
            [],
            0,
            new Offset(0)
        );

        $repo = new class($dummySnap) implements ListRepositoryInterface {
            public function __construct(private readonly ListSnapshot $snap) {}
            public function allNames(): array {}
            public function snapshot(NytDate $d, ListIdentifier $l, Offset $o): ListSnapshot
            {
                return $this->snap;
            }
        };

        $uc = new GetListSnapshotInteractor($repo);
        $req = new GetListSnapshotRequest('foo', '2025-01-01', 0);
        $resp = $uc->execute($req);

        $this->assertSame($dummySnap, $resp->snapshot);
    }
}
