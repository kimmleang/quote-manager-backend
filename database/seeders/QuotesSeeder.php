<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;
use App\Models\User;

class QuotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'johndoe@example.com')->first();

        if ($user) {
            // Create 5 quotes
            $quotes = [
                [
                    'content' => 'The only limit to our realization of tomorrow is our doubts of today.',
                    'author' => 'Franklin D. Roosevelt',
                ],
                [
                    'content' => 'In the middle of every difficulty lies opportunity.',
                    'author' => 'Albert Einstein',
                ],
                [
                    'content' => 'Success is not final, failure is not fatal: It is the courage to continue that counts.',
                    'author' => 'Winston Churchill',
                ],
                [
                    'content' => 'Do not wait to strike till the iron is hot; but make it hot by striking.',
                    'author' => 'William Butler Yeats',
                ],
                [
                    'content' => 'What lies behind us and what lies before us are tiny matters compared to what lies within us.',
                    'author' => 'Ralph Waldo Emerson',
                ],
            ];

            foreach ($quotes as $quote) {
                Quote::create([
                    'user_id' => $user->id,
                    'content' => $quote['content'],
                    'author' => $quote['author'],
                ]);
            }
        }
    }
}