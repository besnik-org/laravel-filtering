<?php
declare(strict_types=1);

use Besnik\LaravelInertiaCrud\CrudGenerateController;
use Illuminate\Support\Facades\Route;


Route::get('inertia-crud-generator', [CrudGenerateController::class, 'index'])->name('inertia-crud-root');

Route::post('inertia-crud-generator', [CrudGenerateController::class, 'generate'])->name('besnik-crud');

