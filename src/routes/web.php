<?php

use Besnik\LaravelFiltering\controllers\FilteringFieldController;
use Illuminate\Support\Facades\Route;

Route::get('filtering', function (){
    return "hello";
});

Route::get('besnik-filtering/{filter}', [\Besnik\LaravelFiltering\controllers\FilterController::class, 'index']);

Route::get('besnik-filtering-fields/{filter}', [FilteringFieldController::class, 'index']);