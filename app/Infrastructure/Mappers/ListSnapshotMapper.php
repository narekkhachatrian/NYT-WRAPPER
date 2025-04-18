<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Books\Entities\ListSnapshot;
use App\Domain\Books\ValueObjects\ListIdentifier;
use App\Domain\Books\ValueObjects\Offset;
use Carbon\CarbonImmutable;

final class ListSnapshotMapper
{
    /**
     * @param  array           $root    Raw NYT JSON
     * @param  Offset          $offset  The offset VO
     * @param  ListIdentifier  $listId  The validated slug from the client
     */
    public static function fromApi(
        array          $root,
        Offset         $offset,
        ListIdentifier $listId
    ): ListSnapshot
    {
        $results = $root['results'] ?? [];

        if (isset($results['books'])) {
            $meta = $results;
            $rows = $results['books'];
        } else {
            $meta = $results[0] ?? [];
            $rows = $results;
        }

        $books = [];
        foreach ($rows as $row) {
            try {
                $books[] = BookMapper::fromListItem($row);
            } catch (\Throwable) {
                // skip bad rows
                continue;
            }
        }

        return new ListSnapshot(
            $listId,
            $meta['display_name'] ?? '',
            CarbonImmutable::parse($meta['bestsellers_date'] ?? 'now'),
            CarbonImmutable::parse($meta['published_date'] ?? 'now'),
            $books,
            $root['num_results'] ?? count($books),
            $offset
        );
    }
}
