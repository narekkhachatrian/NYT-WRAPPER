<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Api;

use App\Domain\Books\Repositories\ListRepositoryInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use App\Domain\Books\ValueObjects\{NytDate,ListIdentifier,Offset};
use App\Domain\Books\Entities\{ListName,ListSnapshot};
use App\Infrastructure\HttpClients\NytBooksApiClient;
use App\Infrastructure\Mappers\{ListNameMapper,ListSnapshotMapper};

final readonly class NytListRepository implements ListRepositoryInterface
{
    public function __construct(private NytBooksApiClient $client) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function allNames(): array
    {
        $json = $this->client->get('/lists/names.json');
        return array_map(ListNameMapper::class.'::fromApi', $json['results'] ?? []);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function snapshot(NytDate $date, ListIdentifier $list, Offset $offset): ListSnapshot
    {
        $json = $this->client->get('/lists.json', [
            'list'           => $list->value(),
            'published-date' => $date->asPathPart(),
            'offset'         => $offset->value(),
        ]);

        // Pass the slug into the mapper:
        return ListSnapshotMapper::fromApi(
            $json,
            $offset,
            $list
        );
    }
}
