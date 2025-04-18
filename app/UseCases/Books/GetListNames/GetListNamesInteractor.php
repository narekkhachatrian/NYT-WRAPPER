<?php

namespace App\UseCases\Books\GetListNames;
use App\Domain\Books\Repositories\ListRepositoryInterface;
final readonly class GetListNamesInteractor
{
    public function __construct(private ListRepositoryInterface $repo) {}
    public function execute(GetListNamesRequest $req): GetListNamesResponse
    {
        return new GetListNamesResponse($this->repo->allNames());
    }
}
