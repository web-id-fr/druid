<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => config('cms.blog.prefix'),
    'as' => config('cms.blog.prefix') . '.',
], function () {
    Route::get('/', [\Webid\Druid\Http\Controllers\BlogController::class, 'index'])
        ->name('index');
    Route::get('/{category:slug}/{post:slug}', [\Webid\Druid\Http\Controllers\BlogController::class, 'show'])
        ->name('show')
        ->missing(function (Request $request) {
            abort(404);
    });
});

Route::fallback([\Webid\Druid\Http\Controllers\FallbackController::class, 'show'])->middleware('redirection-parent-child');
