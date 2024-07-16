<?php

// use App\Http\Controllers\UserController;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Post
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/batch', [UserController::class, 'storeBatch']);

// Get
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);

// PUT
Route::put('/users/{id}', [UserController::class, 'update']);

// DELETE
Route::delete('/users/{id}', [UserController::class, 'destroy']);
