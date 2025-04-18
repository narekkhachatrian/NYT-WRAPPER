<?php

declare(strict_types=1);

namespace App\UseCases\Books\GetReviews;

use App\Domain\Books\Entities\Review;

/**
 * @property Review[] $reviews
*/
final class GetReviewsResponse
{
    public function __construct(public array $reviews)
    {
    }
}
