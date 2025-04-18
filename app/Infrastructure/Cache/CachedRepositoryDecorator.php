<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use Illuminate\Contracts\Cache\Repository as CacheStore;
use Closure;

/**
 * Decorates any repository method with Cache::remember.
 */
class CachedRepositoryDecorator
{
    public function __construct(
        private readonly object     $inner,
        private readonly CacheStore $cache,
        private readonly int        $seconds = 3600
    ) {}

    public function __call(string $method, array $args)
    {
        $key = $this->makeKey($method, $args);

        return $this->cache->remember($key, $this->seconds, function () use ($method, $args) {
            return $this->inner->$method(...$args);
        });
    }

    private function makeKey(string $method, array $args): string
    {
        return 'nyt:' . \get_class($this->inner) . ':' . $method . ':' . md5(serialize($args));
    }
}
