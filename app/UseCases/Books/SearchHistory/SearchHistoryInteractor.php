<?php

declare(strict_types=1);

namespace App\UseCases\Books\SearchHistory;

use App\Domain\Books\Repositories\HistoryRepositoryInterface;
use App\Domain\Books\ValueObjects\Offset;

final readonly class SearchHistoryInteractor
{
    public function __construct(private HistoryRepositoryInterface $repo)
    {
    }

    public function execute(SearchHistoryRequest $r): SearchHistoryResponse
    {
        $filters = array_filter([
            'author' => $r->author,
            'title'  => $r->title,
            'isbn'   => $r->isbn13 ? implode(';', $r->isbn13) : null,
        ]);

        $result = $this->repo->search($filters, new Offset($r->offset));

        return new SearchHistoryResponse(
            $result['total'],
            $result['offset'],
            $result['books'],
        );
    }
}
