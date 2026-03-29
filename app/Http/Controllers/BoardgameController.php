<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Boardgame;
use Illuminate\Support\Facades\Auth;

class BoardgameController extends Controller
{
    public function index(): JsonResponse
    {
        $boardgames = Boardgame::all();
        return response()->json($boardgames);
    }

    public function detail($id): JsonResponse
    {
        $boardgames = Boardgame::find($id);
        if ($boardgames ===null) { 
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }
        return response()->json($boardgames);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string', 
            'slug' => 'required|string', 
            'min_players' => 'required|integer', 
            'max_players' => 'required|integer', 
            'min_age' => 'required|integer', 
            'duration' => 'required|integer', 
            'description' => 'required|string',
            'owner_user_id' => 'integer'
        ]);
        $boardgames = Boardgame::create($data);

        return response()->json($boardgames, 201);
    }
    public function destroy($id): JsonResponse
    {
        $boardgames = Boardgame::find($id);

        if (!$boardgames) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $boardgames->destroy();

        return response()->json([
            'message' => 'Boardgame deleted',
        ]);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $boardgames = Boardgame::find($id);

        if (!$boardgames) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|string', 
            'slug' => 'sometimes|string', 
            'min_players' => 'sometimes|integer', 
            'max_players' => 'sometimes|integer', 
            'min_age' => 'sometimes|integer', 
            'duration' => 'sometimes|integer', 
            'description' => 'sometimes|string',
            'owner_user_id' => 'sometimes|integer'
        ]);
        $boardgames->update($data);
        return response()->json($boardgames, 200);
    }
}
