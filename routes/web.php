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

Route::get('/', 'SitesController@index')
    ->name('site.index');
Route::get('/site/create', 'SitesController@create')
    ->name('site.create');
Route::get('/site/{site}', 'SitesController@show')
    ->name('site.show');
Route::get('/site/{site}/edit', 'SitesController@edit')
    ->name('site.edit');
Route::post('/site/', 'SitesController@store')
    ->name('site.store');
Route::put('/site/{site}', 'SitesController@update')
    ->name('site.update');
Route::delete('/site/{site}', 'SitesController@destroy')
    ->name('site.destroy');

Route::get('/sites-down', 'SitesDownController@index')
    ->name('sites-down.index');
