<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        // $this->resource is your use‑case response DTO
        return [
            'status'      => $this->resource->status,
            'copyright'   => $this->resource->copyright,
            'num_results' => $this->resource->numResults,
            'offset'      => $this->resource->offset,
            'results'     => BookResource::collection($this->resource->results),
        ];
    }
}
