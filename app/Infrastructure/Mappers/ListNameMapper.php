<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Books\Entities\ListName;
use App\Domain\Books\ValueObjects\ListIdentifier;
use Carbon\CarbonImmutable;

final class ListNameMapper
{
    public static function fromApi(array $row): ListName
    {
        return new ListName(
            new ListIdentifier($row['list_name_encoded']),
            $row['display_name'],
            CarbonImmutable::parse($row['oldest_published_date']),
            CarbonImmutable::parse($row['newest_published_date']),
            $row['updated']
        );
    }
}
