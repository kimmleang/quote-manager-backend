<?php

namespace App\Http\Controllers;

use App\Services\QuoteService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function fetchRandomQuote()
    {
        try {
            return response()->json($this->quoteService->fetchRandomQuote());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            return response()->json($this->quoteService->saveQuote($request), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function list()
    {
        try {
            return response()->json($this->quoteService->getSavedQuotes());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($id, Request $request)
    {
        try {
            return response()->json($this->quoteService->updateQuote($id, $request));
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function delete($id)
    {
        try {
            return response()->json($this->quoteService->deleteQuote($id));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}