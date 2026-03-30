<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(): JsonResponse
    {
        $games = Game::all();
        return response()->json($games);
    }

    public function detail($id): JsonResponse
    {
        $games = Game::find($id);
        if ($games ===null) { 
            return response()->json([
                'message' => 'Not Found'
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
            'start_time' => 'required|string',
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
            return response()->json(['message' => 'Not Found'], 404);
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
            return response()->json(['message' => 'Not Found'], 404);
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
}
