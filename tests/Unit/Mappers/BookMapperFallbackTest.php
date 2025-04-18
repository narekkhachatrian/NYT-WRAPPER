<?php
namespace Tests\Unit\Mappers;

use App\Infrastructure\Mappers\BookMapper;
use PHPUnit\Framework\TestCase;
use App\Domain\Books\Entities\Book;

class BookMapperFallbackTest extends TestCase
{
    public function test_primary_isbn_fallback_to_isbns_array()
    {
        $row = [
            'title'=>'X','author'=>'Y','description'=>'','publisher'=>'P',
            'primary_isbn13'=>'','primary_isbn10'=>'',
            'isbns'=>[['isbn10'=>'0307476464','isbn13'=>'']],
        ];

        $book = BookMapper::fromListItem($row);

        $this->assertInstanceOf(Book::class, $book);
        $this->assertSame('0307476464', $book->primaryIsbn()->value());
    }

    public function test_skips_bad_isbn_entries()
    {
        $row = [
            'title'=>'A','author'=>'B','description'=>'','publisher'=>'P',
            'isbns'=>[['isbn13'=>'INVALID'],['isbn10'=>'123456789X']],
        ];

        $book = BookMapper::fromListItem($row);
        // Only valid ISBN is '123456789X'
        $this->assertSame('123456789X', $book->primaryIsbn()->value());
    }

    public function test_collects_unique_isbns_only()
    {
        $row = [
            'title'=>'T','author'=>'A','description'=>'','publisher'=>'P',
            'isbns'=>[
                ['isbn13'=>'9780307476463'],
                ['isbn13'=>'9780307476463']
            ]
        ];

        $book = BookMapper::fromListItem($row);
        $all = array_map(fn($i)=>$i->value(), $book->allIsbns());
        $this->assertCount(1, $all);
        $this->assertSame(['9780307476463'], $all);
    }
}
