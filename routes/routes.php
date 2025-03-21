<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Http\Controllers\BlogController;
use Webid\Druid\Http\Controllers\FallbackController;
use Webid\Druid\Http\Controllers\LanguageSwitcherController;
use Webid\Druid\Http\Middleware\RedirectionParentChild;

if (Druid::isMultilingualEnabled()) {
    Route::get('switch-lang/{locale}', LanguageSwitcherController::class)->name('switch_lang');
}

if (Druid::isBlogModuleEnabled()) {
    if (Druid::isBlogDefaultRoutesEnabled()) {
        if (Druid::isMultilingualEnabled()) {
            Route::prefix('{lang}/'.Config::string('cms.blog.prefix'))
                ->name('posts.multilingual.')
                ->middleware(['multilingual-required', 'language-is-valid', 'web'])
                ->group(function () {
                    Route::get('/', [BlogController::class, 'indexMultilingual'])
                        ->name('index');

                    Route::get('/{category:slug}', [BlogController::class, 'indexByCategoryMultilingual'])
                        ->name('indexByCategory');

                    Route::get('/{category:slug}/{post:slug}', [BlogController::class, 'showMultilingual'])
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
                Route::get('/{category:slug}', [BlogController::class, 'indexByCategory'])
                    ->name('indexByCategory');
                Route::get('/{category:slug}/{post:slug}', [BlogController::class, 'show'])
                    ->name('show')
                    ->missing(function (Request $request) {
                        abort(404);
                    });
            });
    }
}

Route::middleware(['web', RedirectionParentChild::class])->group(function () {
    Route::fallback([FallbackController::class, 'show']);
});
