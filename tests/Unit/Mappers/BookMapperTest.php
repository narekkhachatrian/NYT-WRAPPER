<?php

namespace Tests\Unit\Mappers;

use App\Infrastructure\Mappers\BookMapper;
use PHPUnit\Framework\TestCase;

class BookMapperTest extends TestCase
{
    public function test_maps_minimal_list_row()
    {
        $row = [
            'title'       => 'TEST',
            'author'      => 'Jane Doe',
            'description' => '',
            'isbns'       => [['isbn13'=>'9780307476463']],
        ];

        $book = BookMapper::fromListItem($row);

        $this->assertSame('TEST', $book->title()->value());
        $this->assertSame('Jane Doe', $book->author()->full());
        $this->assertSame('9780307476463', $book->primaryIsbn()->value());
    }
}
