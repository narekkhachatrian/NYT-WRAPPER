<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Books\Entities\Review;

class ReviewResource extends JsonResource
{
    /** @var Review */
    public $resource;

    public function toArray($req): array
    {
        return [
            'url'            => $this->resource->url(),
            'publication_dt' => $this->resource->publicationDate()->toDateString(),
            'byline'         => $this->resource->byline(),
            'book_title'     => $this->resource->bookTitle()->value(),
            'book_author'    => $this->resource->bookAuthor()->full(),
            'summary'        => $this->resource->summary(),
            'isbn13'         => array_map(fn($i)=>$i->value(), $this->resource->isbn13()),
        ];
    }
}
