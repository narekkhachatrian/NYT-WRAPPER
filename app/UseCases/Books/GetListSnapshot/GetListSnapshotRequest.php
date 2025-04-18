<?php

declare(strict_types=1);

namespace App\UseCases\Books\GetListSnapshot;

final class GetListSnapshotRequest
{
    public function __construct(
        public string $list,            // "hardcover-fiction"
        public string $publishedDate,   // "current" or Y-m-d
        public int    $offset           // 0,20,40…
    )
    {
    }
}
