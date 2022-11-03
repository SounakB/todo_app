<?php

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

Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'index']);
Route::get('task/{id}', [\App\Http\Controllers\TaskController::class, 'show']);
Route::post('task', [\App\Http\Controllers\TaskController::class, 'store']);
Route::get('task/complete/{id}', [\App\Http\Controllers\TaskController::class, 'complete']);
Route::delete('task/{id}', [\App\Http\Controllers\TaskController::class, 'destroy']);
