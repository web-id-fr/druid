<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Http\Controllers\BlogController;
use Webid\Druid\App\Http\Controllers\FallbackController;
use Webid\Druid\App\Models\Post;

if (isBlogDefaultRoutesEnable()) {
    if (isMultilingualEnabled()) {
        Route::prefix('{lang}/'.config('cms.blog.prefix'))
            ->name('posts.multilingual.')
            ->middleware(['multilingual-required', 'web'])
            ->group(function () {
                Route::get('/', [BlogController::class, 'indexMultilingual'])
                    ->name('index');

                Route::get('/categories/{category:slug}', [BlogController::class, 'indexByCategoryMultilingual'])
                    ->name('indexByCategory');

                Route::get('/{post:slug}', [BlogController::class, 'showMultilingual'])
                    ->name('show')
                    ->missing(function (Request $request) {
                        abort(404);
                    });
            });
    }

    // @phpstan-ignore-next-line
    Route::prefix(config('cms.blog.prefix'))
        ->name('posts.')
        ->middleware(['multilingual-forbidden', 'web'])
        ->group(function () {
            Route::get('/', [BlogController::class, 'index'])
                ->name('index');
            Route::get('/categories/{category:slug}', [BlogController::class, 'indexByCategory'])
                ->name('indexByCategory');
            Route::get('/{post:slug}', [BlogController::class, 'show'])
                ->name('show')
                ->missing(function (Request $request) {
                    abort(404);
                });
        });
}

Route::fallback([FallbackController::class, 'show'])->middleware('web');
