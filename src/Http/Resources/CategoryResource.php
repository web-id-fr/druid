<?php

namespace Webid\Druid\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Druid\Models\Category;

class CategoryResource extends JsonResource
{
    /** @var Category */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'lang' => $this->resource->lang,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
