<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewSearchHttpRequest;
use App\Http\Resources\ReviewResource;
use App\UseCases\Books\GetReviews\{GetReviewsRequest,GetReviewsInteractor};

class ReviewController extends Controller
{
    public function __construct(private readonly GetReviewsInteractor $uc) {}

    public function search(ReviewSearchHttpRequest $req)
    {
        $dto = new GetReviewsRequest(
            $req->input('author'),
            $req->input('title'),
            $req->input('isbn')
        );

        $resp = $this->uc->execute($dto);
        return ReviewResource::collection($resp->reviews);
    }
}
