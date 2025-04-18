<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\Books\ValueObjects\ListIdentifier;
use PHPUnit\Framework\TestCase;

class ListIdentifierTest extends TestCase
{
    /** @dataProvider validProvider */
    public function test_accepts_valid_slug(string $value)
    {
        $vo = new ListIdentifier($value);
        $this->assertSame($value, $vo->value());
    }

    public static function validProvider(): array
    {
        return [
            ['hardcover-fiction'],
            ['ebook-nonfiction'],
            ['combined-print-and-e-book-fiction'],
        ];
    }

    /** @dataProvider invalidProvider */
    public function test_rejects_invalid_slug(string $value)
    {
        $this->expectException(\InvalidArgumentException::class);
        new ListIdentifier($value);
    }

    public static function invalidProvider(): array
    {
        return [
            ['UpperCase'],
            ['with_space'],
            ['no spaces allowed'],
            [''],
        ];
    }
}
