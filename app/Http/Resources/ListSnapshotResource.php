<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Books\Entities\ListSnapshot;

class ListSnapshotResource extends JsonResource
{
    /**
 * @var ListSnapshot
*/
    public $resource;

    public function toArray($req): array
    {
        return [
            'status'       => 'OK',
            'num_results'  => $this->resource->totalResults(),
            'results'      => [
                'list_name'       => $this->resource->listId()->value(),
                'display_name'    => $this->resource->displayName(),
                'bestsellers_date' => $this->resource->bestsellersDate()->toDateString(),
                'published_date'  => $this->resource->publishedDate()->toDateString(),
                'books'           => BookResource::collection($this->resource->books()),
            ],
        ];
    }
}
