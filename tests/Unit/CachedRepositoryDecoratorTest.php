<?php

namespace Tests\Unit;

use App\Infrastructure\Cache\CachedRepositoryDecorator;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use PHPUnit\Framework\TestCase;

class CachedRepositoryDecoratorTest extends TestCase
{
    public function test_calls_inner_once_then_cache()
    {
        $calls = 0;
        $inner = new class($calls) {
            public function __construct(private int &$c) {}
            public function hello(): string
            { $this->c++; return 'world'; }
        };

        $cache = new Repository(new ArrayStore);
        $decor = new CachedRepositoryDecorator($inner, $cache, 3600);

        $this->assertSame('world', $decor->hello());
        $this->assertSame('world', $decor->hello());
        $this->assertSame(1, $calls);
    }
}
