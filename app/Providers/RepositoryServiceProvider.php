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
        string $port,
        string $impl,
        string $decorator,
        int    $ttl
    ): void {
        $this->app->bind($port, function ($app) use ($impl, $decorator, $ttl) {
            $repo = $app->make($impl);
            return new $decorator($repo, Cache::store(), $ttl);
        });
    }

    public function register(): void
    {
        $this->app->singleton(
            NytBooksApiClient::class,
            function ($app) {
                return new NytBooksApiClient(
                    config('services.nyt.key'),
                    $app->make(LoggerInterface::class)
                );
            }
        );

        $this->bindWithCache(ListRepositoryInterface::class, NytListRepository::class, CachedListRepository::class, 3600);
        $this->bindWithCache(HistoryRepositoryInterface::class, NytHistoryRepository::class, CachedHistoryRepository::class, 3600);
        $this->bindWithCache(ReviewRepositoryInterface::class, NytReviewRepository::class, CachedReviewRepository::class, 900);
    }
}
