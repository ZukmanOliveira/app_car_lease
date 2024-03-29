<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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



Route::get('/', function () {
    return view('welcome');
});
Route::prefix('v1')->middleware('jwt.auth')->group( function(){
    Route::apiResource('/cliente','App\Http\Controllers\ClienteController');
    Route::apiResource('/marca','App\Http\Controllers\MarcaController');
    Route::apiResource('/modelo','App\Http\Controllers\ModeloController');
    Route::apiResource('/carro','App\Http\Controllers\CarroController');
    Route::apiResource('/locacao','App\Http\Controllers\LocacoesController');      
    Route::post('/refresh', 'App\Http\Controllers\AuthController@refresh'); 
    Route::post('/me', 'App\Http\Controllers\AuthController@me');
    Route::post('/logout','App\Http\Controllers\AuthController@logout');
});

Route::post('/login','App\Http\Controllers\AuthController@login');

