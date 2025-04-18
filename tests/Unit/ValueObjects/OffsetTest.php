<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\Books\ValueObjects\Offset;
use PHPUnit\Framework\TestCase;

class OffsetTest extends TestCase
{
    /** @dataProvider validProvider */
    public function test_accepts_valid_offset(int $value)
    {
        $vo = new Offset($value);
        $this->assertSame($value, $vo->value());
    }

    public static function validProvider(): array
    {
        return [
            [0],
            [20],
            [40],
            [100],
        ];
    }

    /** @dataProvider invalidProvider */
    public function test_rejects_invalid_offset(int $value)
    {
        $this->expectException(\InvalidArgumentException::class);
        new Offset($value);
    }

    public static function invalidProvider(): array
    {
        return [
            [-1],
            [1],
            [19],
            [21],
        ];
    }
}
