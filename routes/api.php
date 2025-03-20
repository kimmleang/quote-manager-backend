<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);  
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile', [AuthController::class, 'profile']); 
    
    //Quote routes
    Route::prefix('quotes')->group(function () {
        Route::post('/', [QuoteController::class, 'save']);
        Route::get('/', [QuoteController::class, 'list']);
        Route::put('/{id}', [QuoteController::class, 'update']);
        Route::delete('/{id}', [QuoteController::class, 'delete']);
    });
});

Route::get('/quotes/random', [QuoteController::class, 'fetchRandomQuote']);