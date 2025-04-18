<?php
// Response
namespace App\UseCases\Books\GetListNames;
use App\Domain\Books\Entities\ListName;
final class GetListNamesResponse
{
    /** @param ListName[] $names */
    public function __construct(public array $names) {}
}
