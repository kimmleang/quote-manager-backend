<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email'=> 'required|string',
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

        // Send notification to you (admin)
        $this->sendAdminNotification($contact);

        // Ask the user to start the bot and get their chat ID
        $this->sendUserGreeting($contact);

        return response()->json(['message' => 'Contact saved successfully!'], 201);
    }

    private function sendAdminNotification($contact)
    {
        $chatId = env('TELEGRAM_CHAT_ID'); 
        $botToken = env('TELEGRAM_BOT_TOKEN'); 
        $message = "📩 New Contact Submission!\n\n" .
                   "👤 Name: {$contact->name}\n" .
                   "📞 Phone: {$contact->phone}\n" .
                   "💬 Message: {$contact->message}";

        Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

    
    // filepath: /Users/johnkimleang/Documents/quote-manager-backend/app/Http/Controllers/ContactController.php
    private function sendUserGreeting($contact)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
    
        if ($contact->chat_id) {
            // If chat_id is available, send a greeting message
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
            // If chat_id is not available, ask the user to provide it
            $message = "Hello *{$contact->name}* 👋!\n\n"
                    . "Thank you for reaching out! To chat with me, please start the bot and send your phone number: [Click Here](https://t.me/awsome_dynamic_bot)";
    
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $contact->phone, // Placeholder, as we don't have their chat_id yet
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}
