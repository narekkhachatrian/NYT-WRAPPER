<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Api;

use App\Domain\Books\Repositories\ReviewRepositoryInterface;
use App\Domain\Books\Entities\Review;
use App\Infrastructure\HttpClients\NytBooksApiClient;
use App\Infrastructure\Mappers\ReviewMapper;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

final readonly class NytReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(private NytBooksApiClient $client)
    {
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function search(array $filters): array
    {
        if ($filters === []) {
            throw new \InvalidArgumentException('At least one filter is required');
        }

        $json = $this->client->get('/reviews.json', $filters);
        return array_map(ReviewMapper::class.'::fromApi', $json['results'] ?? []);
    }
}
