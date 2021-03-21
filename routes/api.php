<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BatalhaController;
use App\Http\Controllers\JogadorController;
use App\Http\Controllers\JogoController;

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

Route::get('/', [JogoController::class, 'index']);

Route::post('/registrar', [JogoController::class, 'registrar']);

