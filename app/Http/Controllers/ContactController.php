<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'message' => 'nullable|string',
        ]);
    
        // Store contact info in the database
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);
    
        // Fetch chat_id from Telegram if user already interacted with bot
        $chatId = $this->getChatIdByPhone($contact->phone);
    
        // STOP EXECUTION & SHOW CHAT ID
        dd(Contact::whereNotNull('chat_id')->get());
    
        if ($chatId) {
            $contact->update(['chat_id' => $chatId]);
            $this->sendUserGreeting($contact);
        } else {
            Log::warning("Chat ID not found for {$contact->phone}. Asking them to start the bot.");
        }
    
        // Notify admin
        $this->sendAdminNotification($contact);
    
        return response()->json(['message' => 'Contact saved successfully!'], 201);
    }
    

    private function getChatIdByPhone($phone)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $response = Http::get("https://api.telegram.org/bot{$botToken}/getUpdates");

        if ($response->successful()) {
            $updates = $response->json();

            if (isset($updates['result'])) {
                foreach ($updates['result'] as $update) {
                    if (isset($update['message']['text']) && strpos($update['message']['text'], "/start") !== false) {
                        $receivedPhone = trim(str_replace("/start", "", $update['message']['text']));

                        if ($receivedPhone === $phone) {
                            return $update['message']['chat']['id'];
                        }
                    }
                }
            }
        }

        return null; // Chat ID not found
    }

    private function sendAdminNotification($contact)
    {
        $chatId = env('TELEGRAM_CHAT_ID'); 
        $botToken = env('TELEGRAM_BOT_TOKEN'); 
        $message = "📩 New Contact Submission!\n\n"
                 . "👤 Name: {$contact->name}\n"
                 . "📞 Phone: {$contact->phone}\n"
                 . "💬 Message: {$contact->message}";

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

    private function sendUserGreeting($contact)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');

        if ($contact->chat_id) {
            // Send greeting if chat_id exists
            $message = "Hello *{$contact->name}* 👋!\n\n"
                     . "Thank you for reaching out! Here are my services:\n"
                     . "💻 Web Development (Laravel, React, etc.)\n"
                     . "📱 Mobile App Development\n"
                     . "🚀 Custom Software Solutions\n\n"
                     . "If you have any questions, feel free to ask!";

            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $contact->chat_id,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
        } else {
            // Ask user to start the bot manually
            $message = "Hello *{$contact->name}* 👋!\n\n"
                     . "To chat with me, please click the link and press Start: [Click Here](https://t.me/awsome_dynamic_bot?start={$contact->phone})";

            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => "User *{$contact->name}* has not started the bot yet. Ask them to click: https://t.me/awsome_dynamic_bot?start={$contact->phone}",
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
