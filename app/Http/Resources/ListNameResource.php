<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Books\Entities\ListName;

class ListNameResource extends JsonResource
{
    /**
 * @var ListName
*/
    public $resource;

    public function toArray($req): array
    {
        return [
            'list_name'            => $this->resource->id()->value(),
            'display_name'         => $this->resource->displayName(),
            'list_name_encoded'    => $this->resource->id()->value(),
            'oldest_published_date' => $this->resource->oldestDate()->toDateString(),
            'newest_published_date' => $this->resource->newestDate()->toDateString(),
            'updated'              => $this->resource->updateFrequency(),
        ];
    }
}
