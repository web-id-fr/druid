<?php

namespace Webid\Druid\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Druid\App\Models\Page;
use Webid\Druid\App\Services\ComponentDisplayContentExtractor;

class PageResource extends JsonResource
{
    /** @var Page */
    public $resource;

    /**
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return $this->toArrayWithoutRequestContext();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayWithoutRequestContext(): array
    {
        /** @var ComponentDisplayContentExtractor $componentDisplayContentExtractor */
        $componentDisplayContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'lang' => $this->resource->lang,
            'content' => $componentDisplayContentExtractor->getContentFromBlocks($this->resource->content),
            'searchable_content' => $this->resource->searchable_content,
            'status' => $this->resource->status->value,
            'parent_page_id' => $this->resource->parent_page_id,
            'indexation' => $this->resource->indexation,
            'meta_title' => $this->resource->meta_title,
            'meta_description' => $this->resource->meta_description,
            'meta_keywords' => $this->resource->meta_keywords,
            'opengraph_title' => $this->resource->opengraph_title,
            'opengraph_description' => $this->resource->opengraph_description,
            'opengraph_picture' => $this->resource->opengraph_picture,
            'opengraph_picture_alt' => $this->resource->opengraph_picture_alt,
            'deleted_at' => $this->resource->deleted_at,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }

    public function toObject(): object
    {
        return (object) $this->toArrayWithoutRequestContext();
    }
}
