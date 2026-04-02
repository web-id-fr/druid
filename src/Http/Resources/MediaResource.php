<?php

namespace Webid\Druid\Http\Resources;

use Webid\Druid\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /** @var Media */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            // @phpstan-ignore-next-line
            'url' => $this->resource->url,
            // @phpstan-ignore-next-line
            'alt' => $this->resource->alt,
        ];
    }
}
