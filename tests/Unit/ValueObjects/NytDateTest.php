<?php

namespace Tests\Unit\ValueObjects;

use App\Domain\Books\ValueObjects\NytDate;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;

class NytDateTest extends TestCase
{
    public function test_from_string_accepts_standard_date()
    {
        $dto = NytDate::fromString('2025-04-18');
        $this->assertInstanceOf(CarbonImmutable::class, $dto->value());
        $this->assertSame('2025-04-18', $dto->value()->toDateString());
        $this->assertSame('2025-04-18', $dto->asPathPart());
    }

    public function test_from_string_accepts_current()
    {
        $dto = NytDate::fromString('current');
        $this->assertSame('current', $dto->asPathPart());
    }

    public function test_from_string_rejects_bad_date()
    {
        $this->expectException(\InvalidArgumentException::class);
        NytDate::fromString('2025-13-01');
    }
}
