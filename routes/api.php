<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-open-games',[GameController::class, 'getOpen']);
Route::get('/get-over-games',[GameController::class, 'getOver']);
Route::post('/new-game',[GameController::class, 'newGame']);
Route::post('/connect-to-game',[GameController::class, 'connectToGame']);
Route::post('/choose-gesture',[GameController::class, 'chooseGesture']);
