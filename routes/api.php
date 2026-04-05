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

Route::post('/v1/login', [UserController::class, 'login']);
Route::post('/v1/register', [UserController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    //Login
    Route::post('/v1/logout', [UserController::class, 'logout']);

    //Users
    Route::prefix('v1/users')->group(function () {
        Route::get('', [UserController::class, 'index'])
            ->middleware('role:admin,junta'); //cojo todos los usuarios, solo para admin y junta
        Route::get('/{id}', [UserController::class, 'detail'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //cojo uno usuario en concreto, solo para admin y junta
        Route::post('', [UserController::class, 'store'])
            ->middleware('role:admin'); //creo un usuario, solo para admin
        Route::put('/{id}', [UserController::class, 'update'])
            ->where('id', '[0-9]+'); //actualizo un usuario en concreto. Admin puede editar cualquiera, otros solo a si mismos (lo hago en el controlador)
        Route::delete('/{id}', [UserController::class, 'destroy'])
            ->middleware('role:admin')    
            ->where('id', '[0-9]+'); //borro (sin destruir) un usuario en concreto, solo para admin
    });
    //Boardgames
    Route::prefix('v1/boardgames')->group(function () {
        Route::get('', [BoardgameController::class, 'index']); //cojo todos los juegos
        Route::get('/{id}', [BoardgameController::class, 'detail'])
            ->where('id', '[0-9]+'); //cojo un juego en concreto
        Route::post('', [BoardgameController::class, 'store'])
            ->middleware('role:admin,junta'); //creo un juego. Solo para admin y junta
        Route::put('/{id}', [BoardgameController::class, 'update'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //actualizo un juego en concreto. Solo para admin y junta
        Route::delete('/{id}', [BoardgameController::class, 'destroy'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //borro un juego en concreto. Solo para admin y junta
    });
    //Types
    Route::prefix('v1/types')->group(function () {
        Route::get('', [TypeController::class, 'index'])
            ->middleware('role:admin,junta,partner'); //cojo todos los tipos, para admin, junta y partner
        Route::get('/{id}', [TypeController::class, 'detail'])
            ->middleware('role:admin,junta,partner')
            ->where('id', '[0-9]+'); //cojo un tipo en concreto, para admin, junta y partner
        Route::post('', [TypeController::class, 'store'])
            ->middleware('role:admin,junta'); //creo un tipo, solo para admin y junta
        Route::put('/{id}', [TypeController::class, 'update'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //actualizo un tipo en concreto, solo para admin y junta
        Route::delete('/{id}', [TypeController::class, 'destroy'])
            ->middleware('role:admin')
            ->where('id', '[0-9]+'); //borro un tipo en concreto, solo para admin
    });
    //Zassessions
    Route::prefix('v1/zassessions')->group(function () {
        Route::get('', [ZassessionController::class, 'index']); //cojo todas las sesiones. Todos pueden verlas, incluso los invitados que se logeen

        Route::get('/{id}', [ZassessionController::class, 'detail'])->where('id', '[0-9]+'); //cojo una sesion en concreto. Todos pueden verla, incluso los invitados que se logeen
        Route::post('', [ZassessionController::class, 'store'])
            ->middleware('role:admin,junta');  //creo una sesion, solo para admin y junta
        Route::put('/{id}', [ZassessionController::class, 'update'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //actualizo una sesion en concreto, solo para admin y junta
        Route::delete('/{id}', [ZassessionController::class, 'destroy'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //borro una sesion en concreto, solo para admin y junta
        Route::post('/{id}/join', [ZassessionController::class, 'join'])->where('id', '[0-9]+'); //un usuario se une a una session. Todos pueden, incluso los invitados que se logeen
        Route::delete('/{id}/leave', [ZassessionController::class, 'leave'])->where('id', '[0-9]+'); //un usuario se va de una session. Todos pueden, incluso los invitados que se logeen
        Route::get('/{id}/users', [ZassessionController::class, 'getUsers'])->where('id', '[0-9]+'); //cojo los usuarios de una session. Todos pueden, incluso los invitados que se logeen
        Route::get('/stats', [ZassessionController::class, 'allstats'])
            ->middleware('role:admin,junta,partner'); //estadísticas de las zassessions, solo para admin, junta y partner
        Route::get('/{id}/stats', [ZassessionController::class, 'sessionStats'])
            ->middleware('role:admin,junta,partner')
            ->where('id', '[0-9]+'); //estadísticas de una zassession concretamente, solo para admin, junta y partner

        //necesita games
        Route::get('/{id}/games', [GameController::class, 'indexSession'])
            ->middleware('role:admin,junta,partner,guest')
            ->where('id', '[0-9]+'); //cojo todas las partidas de una sesión concreta. Todos pueden, incluso los invitados que se logeen
    });

    //Games    
    Route::prefix('v1/games')->group(function () {
        Route::get('', [GameController::class, 'indexAll'])
            ->middleware('role:admin,junta,partner'); //cojo todas las partidas, solo para admin, junta y partner
        Route::get('/{id}', [GameController::class, 'detail'])
            ->where('id', '[0-9]+'); //cojo una partida en concreto. Todos pueden, incluso los invitados que se logeen
        Route::post('', [GameController::class, 'store'])
            ->middleware('role:admin,junta,partner'); //creo una partida, solo para admin, junta y partner
        Route::put('/{id}', [GameController::class, 'update'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //actualizo una partida en concreto, solo para admin y junta
        Route::delete('/{id}', [GameController::class, 'destroy'])
            ->middleware('role:admin,junta')
            ->where('id', '[0-9]+'); //borro una partida en concreto, solo para admin y junta
        Route::post('/{id}/join', [GameController::class, 'join'])->where('id', '[0-9]+'); //un usuario se une a una partida. Todos pueden, incluso los invitados que se logeen
        Route::delete('/{id}/leave', [GameController::class, 'leave'])->where('id', '[0-9]+'); //un usuario se va de una partida. Todos pueden, incluso los invitados que se logeen
        Route::get('/{id}/users', [GameController::class, 'getUsers'])->where('id', '[0-9]+'); //cojo los usuarios de una partida. Todos pueden, incluso los invitados que se logeen
        Route::get('/{id}/stats', [GameController::class, 'gameStats'])
            ->middleware('role:admin,junta,partner')
            ->where('id', '[0-9]+'); //estadísticas de una partida, solo para admin, junta y partner
    });
    
});