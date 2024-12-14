<?php

use Illuminate\Support\Facades\Route;

// Regular web routes go here

// This should be the last route
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$')->name('spa');
