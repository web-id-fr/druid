<?php

use Illuminate\Support\Facades\Route;

Route::fallback([\Webid\Druid\Http\Controllers\FallbackController::class, 'show']);
