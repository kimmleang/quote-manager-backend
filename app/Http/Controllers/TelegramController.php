<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
   
    public function handleWebhook(Request $request)
    {
        try {
            $update = $request->all();
    
            if (isset($update['message'])) {
                $chatId = $update['message']['chat']['id'];
                $text = $update['message']['text'];
    
                // Find the contact by phone number
                $contact = Contact::where('phone', $text)->first();
                if ($contact) {
                    // Update the chat_id in the database
                    $contact->update(['chat_id' => $chatId]);
    
                    // Send a greeting message
                    $this->sendGreeting($chatId, $contact->name);
                } else {
                    $botToken = env('TELEGRAM_BOT_TOKEN');
                    $message = "Sorry, we couldn't find your contact information. Please make sure you provided the correct phone number.";
    
                    Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                    ]);
                }
            }
    
            return response()->json(['message' => 'Webhook handled successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to handle webhook',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    private function sendGreeting($chatId, $name)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $message = "Hello *{$name}* 👋!\n\n"
                 . "Thank you for reaching out! Here are my services:\n"
                 . "💻 Web Development (Laravel, React, etc.)\n"
                 . "📱 Mobile App Development\n"
                 . "🚀 Custom Software Solutions\n\n"
                 . "If you have any questions, feel free to ask!";

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }
}
