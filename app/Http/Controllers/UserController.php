<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('api-token')->accessToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }
    
    public function detail($id): JsonResponse
    {
        $user = User::find($id);
        if ($user ===null) { 
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }
        return response()->json($user);
    }
    
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'num_partner' => 'required|integer',
            'nickname' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string|min:6',
            'type' => 'required|in:admin,junta,partner,guest',
            'registration_date' => 'required|date',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'age' => 'required|integer',
            'language' => 'required|in:en,es,ca',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return response()->json($user, 201);
    }
    
    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $user->update([
            'withdrawal_date' => now(),
        ]);

        return response()->json([
            'message' => 'User deleted',
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validate([
            'num_partner' => 'sometimes|integer',
            'nickname' => 'sometimes|string',
            'name' => 'sometimes|string',
            'password' => 'sometimes|string|min:6',
            'type' => 'sometimes|in:admin,partner,guest',
            'registration_date' => 'sometimes|date',
            'withdrawal_date' => 'nullable|date',
            'email' => 'nullable|email',
            'telephone' => 'nullable|string',
            'age' => 'sometimes|integer',
            'language' => 'sometimes||in:en,es,ca',
        ]);

        // Si viene password → hashearla
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json($user, 200);
    }
}
