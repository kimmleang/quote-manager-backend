<?php

namespace App\Http\Controllers\Interfaces;

use Illuminate\Http\Request;

interface QuoteControllerInterface
{
    /**
     * @OA\Get(
     *     path="/api/quotes/random",
     *     summary="Fetch a random quote from the external API",
     *     tags={"Quotes"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="content", type="string", example="This is a random quote."),
     *             @OA\Property(property="author", type="string", example="Author Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function fetchRandomQuote();

    /**
     * @OA\Post(
     *     path="/api/quotes",
     *     summary="Save a favorite quote for the authenticated user",
     *     tags={"Quotes"}
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="content", type="string", example="This is a random quote."),
     *             @OA\Property(property="author", type="string", example="Author Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Quote saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Quote saved successfully"),
     *             @OA\Property(property="quote", type="object")
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
    public function saveQuote(Request $request);

    /**
     * @OA\Get(
     *     path="/api/quotes",
     *     summary="Retrieve all saved favorite quotes for the authenticated user",
     *     tags={"Quotes"},
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="content", type="string", example="This is a random quote."),
     *                 @OA\Property(property="author", type="string", example="Author Name"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function getSavedQuotes();

    /**
     * @OA\Delete(
     *     path="/api/quotes/{id}",
     *     summary="Delete a saved favorite quote by ID",
     *     tags={"Quotes"},
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the quote to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Quote deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Quote not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function deleteQuote($id);
}