<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/v1/users', [UserController::class, 'index']); //cojo todos
Route::get('/v1/users/{id}', [UserController::class, 'detail']); //cojo uno en concreto
Route::post('/v1/users', [UserController::class, 'store']); //creo uno
Route::put('/v1/users/{id}', [UserController::class, 'update']); //actualizo uno en concreto
Route::delete('/v1/users/{id}', [UserController::class, 'destroy']); //borro (sin destruir) uno en concreto
