<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardgameController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ZassessionController;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/login', function() {
    return response()->json(['message' => 'User not authenticated'], 404);
});*/
Route::post('/v1/login', [UserController::class, 'login']);
Route::post('/v1/register', [UserController::class, 'register']);


Route::middleware('auth:api')->group(function () {
    //Login
    //Route::post('/v1/register', [UserController::class, 'register']);
    //Route::post('/v1/login', [UserController::class, 'login']);
    Route::post('/v1/logout', [UserController::class, 'logout']);
    //Users
    Route::prefix('v1/users')->group(function () {
        Route::get('', [UserController::class, 'index']); //cojo todos
        Route::get('/{id}', [UserController::class, 'detail'])->where('id', '[0-9]+'); //cojo uno en concreto
        Route::post('', [UserController::class, 'store']); //creo uno
        Route::put('/{id}', [UserController::class, 'update'])->where('id', '[0-9]+'); //actualizo uno en concreto
        Route::delete('/{id}', [UserController::class, 'destroy'])->where('id', '[0-9]+'); //borro (sin destruir) uno en concreto
    });
    //Boardgames
    Route::prefix('v1/boardgames')->group(function () {
        Route::get('', [BoardgameController::class, 'index']); //cojo todos
        Route::get('/{id}', [BoardgameController::class, 'detail'])->where('id', '[0-9]+'); //cojo uno en concreto
        Route::post('', [BoardgameController::class, 'store']); //creo uno
        Route::put('/{id}', [BoardgameController::class, 'update'])->where('id', '[0-9]+'); //actualizo uno en concreto
        Route::delete('/{id}', [BoardgameController::class, 'destroy'])->where('id', '[0-9]+'); //borro uno en concreto
    });
    //Types
    Route::prefix('v1/types')->group(function () {
        Route::get('', [TypeController::class, 'index']); //cojo todos
        Route::get('/{id}', [TypeController::class, 'detail'])->where('id', '[0-9]+'); //cojo uno en concreto
        Route::post('', [TypeController::class, 'store']); //creo uno
        Route::put('/{id}', [TypeController::class, 'update'])->where('id', '[0-9]+'); //actualizo uno en concreto
        Route::delete('/{id}', [TypeController::class, 'destroy'])->where('id', '[0-9]+'); //borro uno en concreto
    });
    //Zassessions
    Route::prefix('v1/zassessions')->group(function () {
        Route::get('', [ZassessionController::class, 'index']); //cojo todas las sesiones
        Route::get('/{id}', [ZassessionController::class, 'detail'])->where('id', '[0-9]+'); //cojo una sesion en concreto
        Route::post('', [ZassessionController::class, 'store']); //creo una sesion
        Route::put('/{id}', [ZassessionController::class, 'update'])->where('id', '[0-9]+'); //actualizo una sesion en concreto
        Route::delete('/{id}', [ZassessionController::class, 'destroy'])->where('id', '[0-9]+'); //borro una sesion en concreto
        Route::post('/{id}/join', [ZassessionController::class, 'join'])->where('id', '[0-9]+'); //un usuario se une a una session
        Route::delete('/{id}/leave', [ZassessionController::class, 'leave'])->where('id', '[0-9]+'); //un usuario se va de una session
        Route::get('/{id}/users', [ZassessionController::class, 'getUsers'])->where('id', '[0-9]+'); //cojo los usuarios de una session
        Route::get('/stats', [ZassessionController::class, 'allstats']); //estadísticas de las zassessions
        Route::get('/{id}/stats', [ZassessionController::class, 'sessionStats'])->where('id', '[0-9]+'); //estadísticas de una zassession concretamente    

        //necesita games
        Route::get('/{id}/games', [GameController::class, 'indexSession'])->where('id', '[0-9]+'); //cojo todas las partidas de una sesión concreta
    });

    //Games
    
    Route::prefix('v1/games')->group(function () {
        Route::get('', [GameController::class, 'indexAll']); //cojo todas las partidas        
        Route::get('/{id}', [GameController::class, 'detail'])->where('id', '[0-9]+'); //cojo una partida en concreto
        Route::post('', [GameController::class, 'store']); //creo una partida
        Route::put('/{id}', [GameController::class, 'update'])->where('id', '[0-9]+'); //actualizo una partida en concreto
        Route::delete('/{id}', [GameController::class, 'destroy'])->where('id', '[0-9]+'); //borro una partida en concreto

        Route::post('/{id}/join', [GameController::class, 'join'])->where('id', '[0-9]+'); //un usuario se une a una partida
        Route::delete('/{id}/leave', [GameController::class, 'leave'])->where('id', '[0-9]+'); //un usuario se va de una partida
        Route::get('/{id}/users', [GameController::class, 'getUsers'])->where('id', '[0-9]+'); //cojo los usuarios de una partida
        Route::get('/{id}/stats', [GameController::class, 'gameStats'])->where('id', '[0-9]+'); //estadísticas de una partida
    });
    
});