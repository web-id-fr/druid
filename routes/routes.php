<?php

declare(strict_types=1);

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Http\Controllers\BlogController;
use Webid\Druid\Http\Controllers\FallbackController;

Route::prefix('{lang}/' . config('cms.blog.prefix'))
    ->name('posts.multilingual.')
    ->middleware(['multilingual-required', 'web'])
    ->group(function () {
        Route::get('/', [BlogController::class, 'index'])
            ->name('index');

        // TODO: Refacto model binding. Should work automatically with implicit binding but does not
        Route::get('/{post:slug}', function (Langs $lang, string $postSlug) {
            /** @var BlogController $blogController */
            $blogController = app()->make(BlogController::class);

            return $blogController
                ->showMultilingual($lang, Post::query()
                    ->where(['lang' => $lang, 'slug' => $postSlug])
                    ->firstOrFail());
        })
            ->name('show')
            ->missing(function (Request $request) {
                abort(404);
            });
    });

// @phpstan-ignore-next-line
Route::prefix(config('cms.blog.prefix'))
    ->name('posts.')
    ->middleware(['multilingual-forbidden', 'web'])
    ->group(function () {
        Route::get('/', [BlogController::class, 'index'])
            ->name('index');
        Route::get('/{post:slug}', [BlogController::class, 'show'])
            ->name('show')
            ->missing(function (Request $request) {
                abort(404);
            });
    });

Route::fallback([FallbackController::class, 'show'])->middleware('web');
