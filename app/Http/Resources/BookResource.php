<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Books\Entities\Book;

class BookResource extends JsonResource
{
    /**
 * @var Book
*/
    public $resource;

    public function toArray($req): array
    {
        return [
            'rank'        => $this->resource->rank(),
            'weeks_on_list' => $this->resource->weeksOnList(),
            'description' => $this->resource->description(),
            'title'       => $this->resource->title()->value(),
            'author'      => $this->resource->author()->full(),
            'publisher'   => $this->resource->publisher(),
            'primary_isbn13' => $this->resource->primaryIsbn()->value(),
            'isbns'       => array_map(fn($i)=>['isbn13' => $i->value()], $this->resource->allIsbns()),
            'amazon_product_url' => $this->resource->amazonUrl(),
        ];
    }
}
