<?php

namespace App\Http\Controllers\Interfaces;

use Illuminate\Http\Request;

interface AuthControllerInterface
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe12345@example.com"),
     *                 @OA\Property(property="password", type="string", example="SecurePass123!")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="authorisation", type="object",
     *                 @OA\Property(property="type", type="string", example="Bearer"),
     *                 @OA\Property(property="token", type="string", example="your-jwt-token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function register(Request $request);

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", example="johndoe12345@example.com"),
     *             @OA\Property(property="password", type="string", example="SecurePass123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="authorisation", type="object",
     *                 @OA\Property(property="type", type="string", example="Bearer"),
     *                 @OA\Property(property="token", type="string", example="your-jwt-token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function login(Request $request);

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout a user",
     *     tags={"Auth"},
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function logout();
}