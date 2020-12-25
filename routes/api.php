<?php

use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\VoteController;
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

Route::get('/rooms/{code}/join', [RoomController::class, 'join'])->name('room.join');
Route::get('/rooms/{code}/data', [RoomController::class, 'voteData'])->name('room.vote-data');
Route::get('/rooms/{id}/candidates', [RoomController::class, 'candidates'])->name('room.candidates');

Route::post('/votes/{code}', [VoteController::class, 'vote'])->name('vote');