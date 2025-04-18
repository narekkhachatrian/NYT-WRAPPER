<?php

namespace Tests\Unit\Interactors;

use App\UseCases\Books\GetListNames\{GetListNamesInteractor, GetListNamesRequest};
use App\Domain\Books\Repositories\ListRepositoryInterface;
use App\Domain\Books\Entities\ListName;
use App\Domain\Books\ValueObjects\{ListIdentifier};
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class GetListNamesInteractorTest extends TestCase
{
    public function test_execute_returns_names_array()
    {
        $dummy = new ListName(
            new ListIdentifier('foo'),
            'Foo List',
            CarbonImmutable::parse('2020-01-01'),
            CarbonImmutable::parse('2021-01-01'),
            'WEEKLY'
        );

        $repo = new class($dummy) implements ListRepositoryInterface {
            public function __construct(private readonly ListName $dummy) {}
            public function allNames(): array { return [$this->dummy]; }
            public function snapshot(...$args): \App\Domain\Books\Entities\ListSnapshot
            {}
        };

        $uc   = new GetListNamesInteractor($repo);
        $resp = $uc->execute(new GetListNamesRequest);

        $this->assertSame([$dummy], $resp->names);
    }
}
