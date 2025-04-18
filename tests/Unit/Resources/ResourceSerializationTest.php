<?php

namespace Tests\Unit\Resources;

use PHPUnit\Framework\TestCase;
use Carbon\CarbonImmutable;
use App\Domain\Books\Entities\{Book, Review, ListName, ListSnapshot};
use App\Domain\Books\ValueObjects\{ISBN, Title, AuthorName, ListIdentifier, Offset, NytDate};
use App\Http\Resources\{BookResource, ReviewResource, ListNameResource, ListSnapshotResource};

class ResourceSerializationTest extends TestCase
{
    public function test_list_name_resource()
    {
        $entity = new ListName(
            new ListIdentifier('foo'),
            'Foo',
            CarbonImmutable::parse('2020-01-01'),
            CarbonImmutable::parse('2021-01-01'),
            'WEEKLY'
        );
        $arr = (new ListNameResource($entity))->toArray(null);
        $this->assertSame('foo', $arr['list_name']);
        $this->assertSame('Foo', $arr['display_name']);
    }

    public function test_book_resource()
    {
        $book = new Book(
            new ISBN('9780307476463'),
            new Title('My Title'),
            new AuthorName('John Doe'),
            'Desc',
            1,
            2,
            'Publisher',
            'http://amazon.url',
            [new ISBN('9780307476463')]
        );
        $arr = (new BookResource($book))->toArray(null);

        $this->assertSame('My Title',           $arr['title']);
        $this->assertSame('John Doe',           $arr['author']);
        $this->assertSame('9780307476463',      $arr['primary_isbn13']);
        $this->assertSame('http://amazon.url',  $arr['amazon_product_url']);
    }

    public function test_list_snapshot_resource()
    {
        $snap = new ListSnapshot(
            new ListIdentifier('foo'),
            'FooList',
            CarbonImmutable::parse('2025-01-01'),
            CarbonImmutable::parse('2025-01-02'),
            [],
            0,
            new Offset(0)
        );
        $arr = (new ListSnapshotResource($snap))->toArray(null);

        $this->assertSame('foo', $arr['results']['list_name']);
        $this->assertSame('FooList', $arr['results']['display_name']);
    }

    public function test_review_resource()
    {
        $rev = new Review(
            'http://url',
            CarbonImmutable::parse('2025-01-01'),
            'BYLINE',
            new Title('BookTitle'),
            new AuthorName('AuthorName'),
            'Summary text',
            [new ISBN('9780307476463')]
        );
        $arr = (new ReviewResource($rev))->toArray(null);

        $this->assertSame('http://url', $arr['book_review_link'] ?? $arr['url'] ?? $arr['book_review_link'] ?? $arr['url']);
        $this->assertSame('BookTitle',$arr['book_title']   ?? $arr['bookTitle']);
        $this->assertSame(['9780307476463'], $arr['isbn13'] ?? $arr['isbn13']);
    }
}
