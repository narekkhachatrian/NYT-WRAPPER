<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Psr\Log\LoggerInterface;

final class NytBooksApiClient
{
    private const BASE_URL = 'https://api.nytimes.com/svc/books/v3';

    public function __construct(
        private readonly string          $apiKey,
        private readonly LoggerInterface $logger,
        private readonly int             $timeoutSeconds = 5,
        private readonly int             $retries = 3,
        private readonly int             $retryDelayMs = 200
    )
    {
    }

    /**
 * @param array<string,string|int> $query
     * @throws RequestException|ConnectionException
     */
    public function get(string $path, array $query = []): array
    {
        $start = microtime(true);

        /**
 * @var Response $response
*/
        $response = Http::retry($this->retries, $this->retryDelayMs)
            ->timeout($this->timeoutSeconds)
            ->get(self::BASE_URL . '/' . ltrim($path, '/'), array_merge(
                $query,
                ['api-key' => $this->apiKey]
            ))
            ->throw();

        $ms = (int) ((microtime(true) - $start) * 1000);
        $this->logger->info('NYT API call', ['path' => $path, 'ms' => $ms]);

        return $response->json();
    }
}
