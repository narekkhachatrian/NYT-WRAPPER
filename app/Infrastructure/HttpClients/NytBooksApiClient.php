<?php
declare(strict_types=1);

namespace App\Infrastructure\HttpClients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Psr\Log\LoggerInterface;

final readonly class NytBooksApiClient
{
    public function __construct(
        private string          $apiKey,
        private LoggerInterface $logger,
        private string          $baseUrl,
        private string          $version,
        private int             $timeoutSeconds,
        private int             $retries,
        private int             $retryDelayMs,
    )
    {
    }

    /**
     * @param  array<string,string|int>  $query
     * @return array<string,mixed>
     * @throws RequestException|ConnectionException
     */
    public function get(string $path, array $query = []): array
    {
        $start = microtime(true);

        try {
            /**
 * @var Response $response
*/
            $response = Http::retry(
                $this->retries,
                $this->retryDelayMs,
                fn($exception, $request) =>
                    $exception instanceof ConnectionException
                    || (
                        $exception instanceof RequestException
                        && $exception->response?->serverError()
                    )
            )
                ->timeout($this->timeoutSeconds)
                ->get(
                    rtrim($this->baseUrl, '/') . '/' . trim($this->version, '/') . '/' . ltrim($path, '/'),
                    array_merge($query, ['api-key' => $this->apiKey])
                )
                ->throw();

            $ms = (int) ((microtime(true) - $start) * 1000);
            $this->logger->info('NYT API call', [
                'path' => $path,
                'ms'   => $ms,
            ]);

            return $response->json() ?? [];
        } catch (ConnectionException | RequestException $e) {
            $this->logger->error('NYT API call failed after retries', [
                'path'      => $path,
                'query'     => $query,
                'message'   => $e->getMessage(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
