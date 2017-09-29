<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/vagas/empresa/{id}', 'VagaController@empresa')->name('vagas.empresa');
Route::resource('vagas', 'VagaController');

Route::get('/empresas/vagas/{id}', 'EmpresaController@vagas')->name('empresas.vagas');
Route::resource('empresas', 'EmpresaController');

