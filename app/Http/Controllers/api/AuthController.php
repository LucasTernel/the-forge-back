<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Extract first name for the avatar
        $firstName = explode(' ', $request->name)[0];
        $avatarUrl = "https://api.dicebear.com/7.x/avataaars/svg?seed={$firstName}";

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar_url' => $avatarUrl,
        ]);

        // Automatically create a default collection for the new user
        $user->collections()->create([
            'name' => 'Ma Collection',
            'image_cover' => '/storage/default-collection.jpg',
        ]);

        // Default expiration: 30 days
        $token = $user->createToken('auth_token', ['*'], now()->addMonth())->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'boolean' // Optionnel: true pour 1 mois, false pour 24h par exemple
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Le mail ou le mot de passe est incorrect.'],
            ]);
        }

        // Si remember est coché: 1 mois, sinon 24 heures
        $expiration = $request->remember ? now()->addMonth() : now()->addDay();
        $token = $user->createToken('auth_token', ['*'], $expiration)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expiration->toDateTimeString(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'sometimes|string',
            'new_password' => 'sometimes|string|min:6|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Le mot de passe actuel est incorrect.'],
                ]);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->filled('name')) $user->name = $request->name;
        if ($request->filled('email')) $user->email = $request->email;

        $user->save();

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }
}