<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        try {
            $update = $request->all();
            Log::info("Received Telegram Update: " . json_encode($update));

            if (isset($update['message'])) {
                $chatId = $update['message']['chat']['id'];
                $text = $update['message']['text'];

                // Extract phone number from /start <phone>
                if (preg_match('/\/start\s+(\d+)/', $text, $matches)) {
                    $phoneNumber = $matches[1];

                    // Find the contact using the extracted phone number
                    $contact = Contact::where('phone', $phoneNumber)->first();

                    if ($contact) {
                        // Update chat_id in the database
                        $contact->update(['chat_id' => $chatId]);

                        Log::info("Updated contact: {$contact->name} with chat_id: {$chatId}");

                        // Send a greeting message to the user
                        $this->sendGreeting($chatId, $contact->name);
                    } else {
                        Log::warning("No contact found for phone: {$phoneNumber}");
                        $this->sendMessage($chatId, "Sorry, we couldn't find your contact information.");
                    }
                }
            }

            return response()->json(['message' => 'Webhook handled successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Webhook handling error: " . $e->getMessage());
            return response()->json(['error' => 'Failed to handle webhook', 'details' => $e->getMessage()], 500);
        }
    }

    // Send greeting to user
    private function sendGreeting($chatId, $name)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $message = "Hello *{$name}* 👋!\n\n"
                 . "Thank you for reaching out! Here are my services:\n"
                 . "💻 Web Development (Laravel, React, etc.)\n"
                 . "📱 Mobile App Development\n"
                 . "🚀 Custom Software Solutions\n\n"
                 . "If you have any questions, feel free to ask!";

        $this->sendMessage($chatId, $message, 'Markdown');
    }

    // Generic message sender
    private function sendMessage($chatId, $message, $parseMode = null)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        $data = [
            'chat_id' => $chatId,
            'text' => $message
        ];

        if ($parseMode) {
            $data['parse_mode'] = $parseMode;
        }

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", $data);
    }
}
