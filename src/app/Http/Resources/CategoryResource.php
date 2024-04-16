<?php

namespace Webid\Druid\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Druid\App\Models\Category;

class CategoryResource extends JsonResource
{
    /** @var Category $resource */
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
