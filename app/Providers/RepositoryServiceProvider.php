<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;
use App\Infrastructure\HttpClients\NytBooksApiClient;
use App\Infrastructure\Repositories\Api\{
    NytListRepository,
    NytHistoryRepository,
    NytReviewRepository
};
use App\Domain\Books\Repositories\{
    ListRepositoryInterface,
    HistoryRepositoryInterface,
    ReviewRepositoryInterface
};
use App\Infrastructure\Cache\{
    CachedListRepository,
    CachedHistoryRepository,
    CachedReviewRepository
};

final class RepositoryServiceProvider extends ServiceProvider
{
    private function bindWithCache(
        string $interface,
        string $implementation,
        string $decorator,
        int    $ttl
    ): void {
        $this->app->bind($interface, function ($app) use ($implementation, $decorator, $ttl) {
            $repo = $app->make($implementation);
            return new $decorator($repo, Cache::store(), $ttl);
        });
    }

    public function register(): void
    {
        // NyT HTTP client singleton
        $this->app->singleton(NytBooksApiClient::class, function ($app) {
            $cfg = config('services.nyt');

            return new NytBooksApiClient(
                $cfg['key'],
                $app->make(LoggerInterface::class),
                $cfg['base_url'],
                $cfg['version'],

                (int) $cfg['timeout_seconds'],
                (int) $cfg['retries'],
                (int) $cfg['retry_delay_ms'],
            );
        });

        $useCache = config('services.nyt.use_cache', true);

        if ($useCache) {
            // Decorate each one with the cache layer
            $this->bindWithCache(
                ListRepositoryInterface::class,
                NytListRepository::class,
                CachedListRepository::class,
                3600
            );
            $this->bindWithCache(
                HistoryRepositoryInterface::class,
                NytHistoryRepository::class,
                CachedHistoryRepository::class,
                3600
            );
            $this->bindWithCache(
                ReviewRepositoryInterface::class,
                NytReviewRepository::class,
                CachedReviewRepository::class,
                900
            );
        } else {
            // Direct bindings, no cache
            $this->app->bind(ListRepositoryInterface::class, NytListRepository::class);
            $this->app->bind(HistoryRepositoryInterface::class, NytHistoryRepository::class);
            $this->app->bind(ReviewRepositoryInterface::class, NytReviewRepository::class);
        }
    }
}
