<?php

declare(strict_types=1);

namespace App\UseCases\Books\GetReviews;

final class GetReviewsRequest
{
    public function __construct(
        public ?string $author,
        public ?string $title,
        public ?string $isbn
    ) {}
}
