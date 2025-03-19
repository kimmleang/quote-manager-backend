<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService {

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return [
            'message' => 'User registered successful',
            'user' => $user,
            'authorization' => [
                'type' => 'Bearer',
                'token' => $token,
            ],
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            throw new Exception('Invalid credentials');
        }

        $user = auth()->user();

        return [
            'message' => 'Login successful',
            'user' => $user,
            'authorization' => [
                'type' => 'Bearer',
                'token' => $token,
            ],
        ];
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return [
                'message' => 'Successfully logged out',
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to log out, please try again.');
        }
    }
}