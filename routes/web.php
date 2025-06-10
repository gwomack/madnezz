<?php

declare(strict_types = 1);

use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('produtos', ProdutoController::class);
});

require __DIR__ . '/settings.php';

require __DIR__ . '/auth.php';
