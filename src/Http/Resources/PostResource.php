<?php

namespace Webid\Druid\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /** @var Post $resource */
    public $resource;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'lang' => $this->resource->lang,
            'content' => $this->resource->content,
            'html_content' => $this->resource->html_content,
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
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }

}
