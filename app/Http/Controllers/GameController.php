<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function indexAll(): JsonResponse
    {
        $games = Game::all();
        return response()->json($games);
    }

    public function detail($id): JsonResponse
    {
        $games = Game::find($id);
        if ($games ===null) { 
            return response()->json([
                'message' => 'Game Not Found'
            ], 404);
        }
        return response()->json($games);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'zassession_id' => 'required|integer',
            'boardgame_id' => 'required|integer',
            'host_user_id' => 'required|integer',
            'max_players' => 'required|integer',
            'start_time' => 'required|date_format:H:i:s',
            'status' => 'required|string',
            'necesary_know_how' => 'required|boolean',
        ]);
        $games = Game::create($data);

        return response()->json($games, 201);
    }
    public function destroy($id): JsonResponse
    {
        $games = Game::find($id);

        if (!$games) {
            return response()->json(['message' => 'Game Not Found'], 404);
        }

        $games->delete();

        return response()->json([
            'message' => 'Game deleted',
        ]);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $games = Game::find($id);

        if (!$games) {
            return response()->json(['message' => 'Game Not Found'], 404);
        }

        $data = $request->validate([
            'zassession_id' => 'sometimes|integer',
            'boardgame_id' => 'sometimes|integer',
            'host_user_id' => 'sometimes|integer',
            'max_players' => 'sometimes|integer',
            'start_time' => 'sometimes|string',
            'status' => 'sometimes|string',
            'necesary_know_how' => 'sometimes|boolean',
        ]);
        $games->update($data);
        return response()->json($games, 200);
    }
    public function join($game_id): JsonResponse
    {
        /** @var \App\Models\User $user */
        
        $user = Auth::guard('api')->user();
//dd($game_id);        
        if (!$user) { 
            return response()->json([
                'message' => 'User not autenticated'
            ], 404);
        } 
//dd($game_id);        
        $game = Game::find($game_id);
        if (!$game) { 
            return response()->json([
                'message' => 'Game not found'
            ], 404);
        }
//dd($game_id);        
        if ($game->players()->count() >= $game->max_players) {
            return response()->json([
                'message' => 'Game is full'
            ], 401);
        }
//dd($game_id);        
        if ($game->players()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'User already joined this game'
            ], 402);
        }
//dd($game_id);        
        $game->players()->attach($user->id);
        return response()->json([
            'message' => 'User joined the game'
        ], 200);
    }
    public function leave($game_id): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user ===null) { 
            return response()->json([
                'message' => 'User not autenticated'
            ], 404);
        } 
        $game = Game::find($game_id);
        if (!$game) { 
            return response()->json([
                'message' => 'Game not found'
            ], 404);
        }
        
        if (!$game->players()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'User is not joined to this game'
            ], 400);
        }
        $game->players()->detach($user->id);
        return response()->json([
            'message' => 'User left the game'
        ], 200);
    }
    public function getUsers($game_id): JsonResponse
    {
        $game = Game::find($game_id);
        if ($game ===null) { 
            return response()->json([
                'message' => 'Game not found'
            ], 404);
        }
         if ($game->players()->count() == 0) {
            return response()->json([
                'message' => 'No players joined to this game'
            ], 400);
        }
        return response()->json($game->players, 200);
    }
}
