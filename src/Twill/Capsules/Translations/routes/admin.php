<?php

use Illuminate\Support\Facades\Route;

Route::prefix('translations')->group(function () {
    Route::module('translations');
});
Route::module('translations');
