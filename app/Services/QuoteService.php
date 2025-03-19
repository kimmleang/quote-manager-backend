<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class QuoteService
{
    public function fetchRandomQuote()
    {
        try {
            $response = Http::withOptions(['verify' => false])->withHeaders([
                'Accept' => 'application/json',
            ])->get('https://favqs.com/api/qotd');
    
            // dd($response->body());
            if ($response->successful()) {
                $quoteData = $response->json('quote');
                // dd($quoteData);
                
                if ($quoteData && isset($quoteData['body'], $quoteData['author'])) {
                    return [
                        'content' => $quoteData['body'],
                        'author' => $quoteData['author'],
                    ];
                }
                throw new Exception('Invalid quote data from FavQs API');
            }
            throw new Exception('Failed to fetch quote from FavQs API');
        } catch (Exception $e) {
            // dd($e->getMessage());
            return [
                'content' => 'This is a fallback quote.',
                'author' => 'Fallback Author',
            ];
        }
    }
}