<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{       
    protected $authService;

    public function __construct(
        AuthService $authService
    )
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try{
            $user = $this->authService->register($request);
            return response()->json($user, 201);
        }catch (ValidationException $e){
            return response()->json(['errors' => $e -> errors()], 400);
        }catch (Exception $e){
            return response()->json(['error' => $e -> getMessage()], 500);
        }
    }


    public function login(Request $request)
    {
        try {
            $user = $this->authService->login($request);
            return response()->json($user);
        }catch (ValidationException $e){
            return response()->json([ "errors" => $e -> errors()],422); 
        }catch (Exception $e){
            return response()->json(['error' => $e -> getMessage()], 500);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json([
                'status' => 'success',
                'authorisation' => [
                    'type' => 'Bearer',
                    'token' => $token,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }


}