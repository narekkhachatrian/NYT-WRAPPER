<?php

namespace Tests\Unit\Mappers;

use App\Infrastructure\Mappers\ListSnapshotMapper;
use App\Domain\Books\ValueObjects\{ListIdentifier, Offset};
use App\Domain\Books\Entities\ListSnapshot;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class ListSnapshotMapperTest extends TestCase
{
    public function test_from_api_flat_shape_with_no_books()
    {
        $root = [
            'results'     => [],
            'num_results' => 0,
        ];
        $listId = new ListIdentifier('hardcover-fiction');
        $offset = new Offset(0);

        $snap = ListSnapshotMapper::fromApi($root, $offset, $listId);

        $this->assertInstanceOf(ListSnapshot::class, $snap);
        $this->assertSame('hardcover-fiction', $snap->listId()->value());
        $this->assertSame(0, $snap->totalResults());
        $this->assertCount(0, $snap->books());
    }

    public function test_from_api_flat_shape_with_metadata_only()
    {
        $root = [
            'results'     => [
                [
                    'display_name'      => 'My List',
                    'bestsellers_date'  => '2025-01-01',
                    'published_date'    => '2025-01-02',
                ],
            ],
            'num_results' => 1,
        ];
        $listId = new ListIdentifier('hardcover-fiction');
        $offset = new Offset(0);

        $snap = ListSnapshotMapper::fromApi($root, $offset, $listId);

        $this->assertSame('My List', $snap->displayName());
        $this->assertSame('2025-01-01', $snap->bestsellersDate()->toDateString());
        $this->assertSame('2025-01-02', $snap->publishedDate()->toDateString());
        $this->assertSame(1, $snap->totalResults());
    }

    public function test_from_api_path_variant()
    {
        $root = [
            'results'     => [
                'display_name'      => 'Path List',
                'bestsellers_date'  => '2025-02-01',
                'published_date'    => '2025-02-02',
                'books'             => [],
            ],
            'num_results' => 0,
        ];
        $listId = new ListIdentifier('ebook-fiction');
        $offset = new Offset(20);

        $snap = ListSnapshotMapper::fromApi($root, $offset, $listId);

        $this->assertSame('ebook-fiction', $snap->listId()->value());
        $this->assertSame('Path List', $snap->displayName());
        $this->assertSame(0, $snap->totalResults());
        $this->assertSame(20, $snap->offset()->value());
    }
}
