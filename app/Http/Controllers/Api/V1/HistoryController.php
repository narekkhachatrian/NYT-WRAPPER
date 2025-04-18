<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\HistorySearchHttpRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\JsonResponse;
use App\UseCases\Books\SearchHistory\{SearchHistoryRequest,SearchHistoryInteractor};

class HistoryController extends Controller
{
    public function __construct(private readonly SearchHistoryInteractor $uc) {}

    public function search(HistorySearchHttpRequest $req): JsonResponse
    {
        $dto = new SearchHistoryRequest(
            $req->input('author'),
            $req->input('title'),
            $req->input('isbn'),
            $req->validatedOffset()
        );

        $resp = $this->uc->execute($dto);

        return response()->json([
            'status'      => 'OK',
            'num_results' => $resp->total,
            'results'     => BookResource::collection($resp->books),
            'offset'      => $resp->offset->value(),
        ]);
    }
}
