<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardgameController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:api')->group(function () {
    //Users
    Route::get('/v1/users', [UserController::class, 'index']); //cojo todos
    Route::get('/v1/users/{id}', [UserController::class, 'detail']); //cojo uno en concreto
    Route::post('/v1/users', [UserController::class, 'store']); //creo uno
    Route::put('/v1/users/{id}', [UserController::class, 'update']); //actualizo uno en concreto
    Route::delete('/v1/users/{id}', [UserController::class, 'destroy']); //borro (sin destruir) uno en concreto
    //Boardgames
    Route::get('/v1/boardgames', [BoardgameController::class, 'index']); //cojo todos
    Route::get('/v1/boardgames/{id}', [BoardgameController::class, 'detail']); //cojo uno en concreto
    Route::post('/v1/boardgames', [BoardgameController::class, 'store']); //creo uno
    Route::put('/v1/boardgames/{id}', [BoardgameController::class, 'update']); //actualizo uno en concreto
    Route::delete('/v1/boardgames/{id}', [BoardgameController::class, 'destroy']); //borro uno en concreto
});