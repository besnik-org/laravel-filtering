<?php
declare(strict_types=1);

use Besnik\LaravelFiltering\controllers\FilterController;
use Besnik\LaravelFiltering\controllers\FilteringFieldController;
use Illuminate\Support\Facades\Route;

Route::get('filtering', function () {
    return 'hello';
});

Route::get('besnik-filtering/{filter}', [FilterController::class, 'index']);

Route::get('besnik-filtering-fields/{filter}', [FilteringFieldController::class, 'index']);
Route::get('besnik-filtering-options', [FilteringFieldController::class, 'modelOptions']);
