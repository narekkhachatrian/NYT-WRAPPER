<?php

namespace Tests\Unit\Interactors;

use App\UseCases\Books\GetReviews\{GetReviewsInteractor, GetReviewsRequest};
use App\Domain\Books\Repositories\ReviewRepositoryInterface;
use App\Domain\Books\Entities\Review;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class GetReviewsInteractorTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_execute_returns_reviews_list()
    {
        $fakeReview = $this->createMock(Review::class);

        $repo = new class($fakeReview) implements ReviewRepositoryInterface {
            public function __construct(private readonly Review $r) {}
            public function search(array $filters): array
            {
                return [$this->r];
            }
        };

        $uc   = new GetReviewsInteractor($repo);
        $req  = new GetReviewsRequest('author', 'title', '123');
        $resp = $uc->execute($req);

        $this->assertSame([$fakeReview], $resp->reviews);
    }
}
