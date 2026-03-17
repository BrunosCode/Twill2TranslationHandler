<?php

use Illuminate\Support\Facades\Route;

Route::prefix('translations')->group(function () {
  Route::module('translationGroups');
});
Route::module('translationGroups');