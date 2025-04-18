<?php

declare(strict_types=1);

namespace App\UseCases\Books\GetReviews;

use App\Domain\Books\Repositories\ReviewRepositoryInterface;

final readonly class GetReviewsInteractor
{
    public function __construct(private ReviewRepositoryInterface $repo)
    {
    }

    public function execute(GetReviewsRequest $r): GetReviewsResponse
    {
        $filters = array_filter([
            'author' => $r->author,
            'title'  => $r->title,
            'isbn'   => $r->isbn,
        ]);

        $reviews = $this->repo->search($filters);

        return new GetReviewsResponse($reviews);
    }
}
