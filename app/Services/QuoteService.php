<?php

namespace App\Services;

use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Exception;

class QuoteService
{
    //generate or fetch random quote from https://favqs.com/api/qotd
    public function fetchRandomQuote()
    {
        try {
            $response = Http::withOptions(['verify' => false])->withHeaders([
                'Accept' => 'application/json',
            ])->get('https://favqs.com/api/qotd');

            if ($response->successful()) {
                $quoteData = $response->json('quote');

                if ($quoteData && isset($quoteData['body'], $quoteData['author'])) {
                    return [
                        'message' => 'Quote generate successfully',
                        'author' => $quoteData['author'],
                        'content' => $quoteData['body'],
                    ];
                }
                throw new Exception('Invalid quote data from FavQs API');
            }
            throw new Exception('Failed to fetch quote from FavQs API');
        } catch (Exception $e) {
            return [
                'message' => 'Failed to fetch quote from external API',
                'author' => 'Fallback Author',
                'content' => 'This is a fallback quote.',

            ];
        }
    }

    //save quote 
    public function saveQuote($request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'author' => 'required|string',
        ]);

        $quote = Quote::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'author' => $validated['author'],
        ]);

        return [
            'message' => 'Quote saved successfully',
            'quote' => $quote,
        ];
    }

    //list saved quotes
    public function getSavedQuotes($request = null)
    {
        $query = QueryBuilder::for(Quote::class)
            ->where('user_id', Auth::id())
            ->allowedFilters([
                AllowedFilter::partial('content'),
                AllowedFilter::partial('author'),
            ])
            ->allowedSorts('id','created_at', 'updated_at')
            ->defaultSort('id');
    
        // Handle pagination if $request is provided
        if ($request) {
            $quotes = $query->paginate($request->input('per_page', 10));
        } else {
            $quotes = $query->get();
        }
    
        return [
            'message' => 'Saved quotes retrieved successfully',
            'data' => $quotes,
        ];
    }

    //update quote
    public function updateQuote($id, $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'author' => 'required|string',
        ]);

        $quote = Quote::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$quote) {
            throw new Exception('Quote not found');
        }

        $quote->update([
            'content' => $validated['content'],
            'author' => $validated['author'],
        ]);

        return [
            'message' => 'Quote updated successfully',
            'data' => $quote,
        ];
    }

    //delete quote
    public function deleteQuote($id)
    {
        $quote = Quote::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$quote) {
            throw new Exception('Quote not found');
        }

        $quote->delete();

        return ['message' => 'Quote deleted successfully'];
    }
}