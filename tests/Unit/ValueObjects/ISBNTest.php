<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\Books\ValueObjects\ISBN;
use PHPUnit\Framework\TestCase;

class ISBNTest extends TestCase
{
    /** @dataProvider okProvider */
    public function test_accepts_valid_isbn(string $raw)
    {
        $this->assertSame(strtoupper($raw), (new ISBN($raw))->value());
    }

    public static function okProvider(): array
    {
        return [
            ['9780307476463'],
            ['0307476464'],
            ['042519745X'],
        ];
    }

    /** @dataProvider badProvider */
    public function test_rejects_bad_isbn(string $raw)
    {
        $this->expectException(\InvalidArgumentException::class);
        new ISBN($raw);
    }

    public static function badProvider(): array
    {
        return [
            ['123'], ['9780abc'], ['030747646Y'],
        ];
    }
}
