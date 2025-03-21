<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function test_user_can_login_with_valid_credentials()
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Kimleang',
            'email' => 'kimleang12@gmail.com',
            'password' => bcrypt('1234567890'), 
        ]);
    
        // POST request to login endpoint
        $response = $this->postJson('/api/login', [
            'email' => 'kimleang12@gmail.com',
            'password' => '1234567890',
        ]);
    
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'authorization' => [
                         'type',
                         'token',
                     ],
                     'user',
                 ]);
    }
}