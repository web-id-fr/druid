<?php

namespace Webid\Druid\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Facades\Druid;

class CheckIfPostNeedsToBePublished extends Command
{
    protected $signature = 'druid:publish-scheduled-posts';

    protected $description = 'Check if post needs to be published';

    public function handle(): void
    {
        $this->info('Checking if post needs to be published');

        $postModel = Druid::Post();

        $posts = $postModel::query()
            ->where('status', PostStatus::SCHEDULED_PUBLISH)
            ->where('published_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->get();

        foreach ($posts as $post) {
            $post->status = PostStatus::PUBLISHED;
            $post->save();
        }

        $this->info('Posts have been published');
    }
}
