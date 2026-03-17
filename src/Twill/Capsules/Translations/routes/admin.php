<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'translations'], function () {
    Route::module('translations');
    Route::module('translationGroups');
});
