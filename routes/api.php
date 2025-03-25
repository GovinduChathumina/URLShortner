<?php

use App\Http\Controllers\UrlShortenerController;

Route::post('/encode', [UrlShortenerController::class, 'encode']);
Route::post('/decode', [UrlShortenerController::class, 'decode']);