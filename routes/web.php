<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\BasecampAuthController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\SitesDownController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('', [SitesController::class, 'index'])
        ->name('site.index');
    Route::get('/site/create', [SitesController::class, 'create'])
        ->name('site.create');
    Route::get('/site/{site}', [SitesController::class, 'show'])
        ->name('site.show');
    Route::get('/site/{site}/edit', [SitesController::class, 'edit'])
        ->name('site.edit');
    Route::post('/site/', [SitesController::class, 'store'])
        ->name('site.store');
    Route::put('/site/{site}', [SitesController::class, 'update'])
        ->name('site.update');
    Route::delete('/site/{site}', [SitesController::class, 'destroy'])
        ->name('site.destroy');

    Route::get('/sites-down', [SitesDownController::class, 'index'])
        ->name('sites-down.index');
});

Route::get('basecamp-redirect', [BasecampAuthController::class, 'store'])
    ->name('basecamp-redirect.store');

Auth::routes([
    'register' => false
]);
