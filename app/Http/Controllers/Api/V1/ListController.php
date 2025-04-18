<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\{ListNamesHttpRequest,ListSnapshotHttpRequest};
use App\Http\Resources\{ListNameResource,ListSnapshotResource};
use App\UseCases\Books\GetListNames\{GetListNamesRequest,GetListNamesInteractor};
use App\UseCases\Books\GetListSnapshot\{GetListSnapshotRequest,GetListSnapshotInteractor};

class ListController extends Controller
{
    public function __construct(
        private readonly GetListNamesInteractor $namesUC,
        private readonly GetListSnapshotInteractor $snapUC
    ) {}

    public function names(ListNamesHttpRequest $req)
    {
        $resp = $this->namesUC->execute(new GetListNamesRequest);
        return ListNameResource::collection($resp->names);
    }

    public function snapshot(ListSnapshotHttpRequest $req): ListSnapshotResource
    {
        $dto  = new GetListSnapshotRequest(
            $req->string('list'),
            $req->validatedPublishedDate(),
            $req->validatedOffset()
        );

        $resp = $this->snapUC->execute($dto);
        return new ListSnapshotResource($resp->snapshot);
    }
}
