<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Boardgame;
use Illuminate\Support\Facades\Auth;

class BoardgameController extends Controller
{
    /**
     * Listar juegos de mesa
     *
     * Devuelve todos los juegos de mesa disponibles.
     *
     * @group Juegos de mesa
     * 
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Juego A",
     *     "slug": "juego-a",
     *     "min_players": 2,
     *     "max_players": 10,
     *     "min_age": 8,
     *     "duration": 60,
     *     "description": "Descripción del juego A"
     *   }
     * ]
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     * @response 403 {
     *   "message": "Forbidden"
     * }
     */
    public function index(): JsonResponse
    {
        $boardgames = Boardgame::all();
        return response()->json($boardgames);
    }
    /**
     * Obtener detalle de un juego de mesa
     *
     * Devuelve la información completa de un juego de mesa específico.
     *
     * @group Juegos de mesa
     *
     * @urlParam id integer required El ID del juego de mesa. Ejemplo: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Juego A",
     *   "slug": "juego-a",
     *   "min_players": 2,
     *   "max_players": 10,
     *   "min_age": 8,
     *   "duration": 60,
     *   "description": "Descripción del juego A"
     * }
     * @response 404 {
     *   "message": "Not Found"
     * }
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     */
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
    /**
     * Crear juego de mesa
     *
     * Crea un nuevo juego de mesa con los datos proporcionados.
     *
     * @group Juegos de mesa
     * 
     * @response 201 {
     *   "id": 1,
     *   "name": "Juego A",
     *   "slug": "juego-a",
     *   "min_players": 2,
     *   "max_players": 10,
     *   "min_age": 8,
     *   "duration": 60,
     *   "description": "Descripción del juego A",
     *   "owner_user_id": null
     * }
     * @response 400 {
     *   "message": "The given data was invalid."
     * }
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     * @response 403 {
     *   "message": "Forbidden"
     * }
     */
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
            'owner_user_id' => 'nullable|integer'
        ]);
        $boardgames = Boardgame::create($data);

        return response()->json($boardgames, 201);
    }
    /**
     * Eliminar juego de mesa
     *
     * Elimina un juego de mesa del sistema.
     *
     * @group Juegos de mesa
     * 
     * @urlParam id integer required El ID del juego de mesa. Ejemplo: 1
     * 
     * @response 200 {
     *   "message": "Boardgame deleted"
     * }
     * @response 404 {
     *   "message": "Not Found"
     * }
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     * @response 403 {
     *   "message": "Forbidden"
     * }
     */
    public function destroy($id): JsonResponse
    {
        $boardgames = Boardgame::find($id);

        if (!$boardgames) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        $boardgames->delete();
        return response()->json([
            'message' => 'Boardgame deleted',
        ]);
    }
    /**
     * Actualizar juego de mesa
     *
     * Actualiza la información de un juego de mesa específico.
     *
     * @group Juegos de mesa
     * 
     * @urlParam id integer required El ID del juego de mesa. Ejemplo: 1
     * 
     * @response 200 {
     *   "id": 1,
     *   "name": "Juego A",
     *   "slug": "juego-a",
     *   "min_players": 2,
     *   "max_players": 10,
     *   "min_age": 8,
     *   "duration": 60,
     *   "description": "Descripción del juego A"
     * }
     * @response 400 {
     *   "message": "The given data was invalid."
     * }
     * @response 404 {
     *   "message": "Not Found"
     * }
     * @response 401 {
     *   "message": "Unauthorized"
     * }
     * @response 403 {
     *   "message": "Forbidden"
     * }
     */
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