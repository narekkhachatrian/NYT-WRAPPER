<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Api;

use App\Domain\Books\Repositories\HistoryRepositoryInterface;
use App\Domain\Books\ValueObjects\Offset;
use App\Infrastructure\HttpClients\NytBooksApiClient;
use App\Infrastructure\Mappers\BookMapper;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

final readonly class NytHistoryRepository implements HistoryRepositoryInterface
{
    public function __construct(private NytBooksApiClient $client)
    {
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function search(array $filters, Offset $offset): array
    {
        $query = ['offset' => $offset->value()] + $filters;

        $json   = $this->client->get('/lists/best-sellers/history.json', $query);
        $books = [];
        foreach ($json['results'] ?? [] as $row) {
            try {
                $books[] = BookMapper::fromListItem($row);
            } catch (\RuntimeException) {
                continue;
            }
        }
        return [
            'total'  => $json['num_results'] ?? \count($books),
            'offset' => $offset,
            'books'  => $books,
        ];
    }
}
