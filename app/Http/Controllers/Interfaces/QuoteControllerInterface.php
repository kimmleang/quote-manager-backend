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
     *     security={{"bearerAuth":{}}},
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
     *     tags={"Quotes"},
     *     security={{"bearerAuth":{}}},
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
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="filter[content]",
     *         in="query",
     *         required=false,
     *         description="Filter quotes by content",
     *         @OA\Schema(type="string", example="inspiration")
     *     ),
     *     @OA\Parameter(
     *         name="filter[author]",
     *         in="query",
     *         required=false,
     *         description="Filter quotes by author",
     *         @OA\Schema(type="string", example="John Doe")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         required=false,
     *         description="Sort quotes by created_at or updated_at",
     *         @OA\Schema(type="string", example="-created_at")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of quotes per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
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
    public function getSavedQuotes(Request $request);

    /**
     * @OA\Delete(
     *     path="/api/quotes/{id}",
     *     summary="Delete a saved favorite quote by ID",
     *     tags={"Quotes"},
     *     security={{"bearerAuth":{}}},
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

    /**
     * @OA\Put(
     *     path="/api/quotes/{id}",
     *     summary="Update a saved favorite quote by ID",
     *     tags={"Quotes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the quote to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="content", type="string", example="Updated quote content."),
     *             @OA\Property(property="author", type="string", example="Updated Author")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quote updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Quote updated successfully"),
     *             @OA\Property(property="quote", type="object")
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
    public function updateQuote($id, Request $request);
}