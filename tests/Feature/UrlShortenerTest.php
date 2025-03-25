<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ShortUrl;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;
    
    // Encoding
    public function test_it_encodes_a_url()
    {
        $response = $this->postJson('/api/encode', [
            'url' => 'https://laravel.com'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['short_url']);
    }

    // Duplicate Encode
    public function test_it_returns_existing_short_url_for_same_original_url()
    {
        $short = ShortUrl::create([
            'original_url' => 'https://laravel.com',
            'short_code' => 'Lara01',
        ]);

        $response = $this->postJson('/api/encode', [
            'url' => 'https://laravel.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['short_url' => url('Lara01')]);
    }
    // Decoding
    public function test_it_decodes_a_short_url()
    {
        $short = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'Ex1234',
        ]);

        $response = $this->postJson('/api/decode', [
            'short_url' => url('Ex1234')
        ]);

        $response->assertStatus(200)
                 ->assertJson(['original_url' => 'https://example.com']);
    }

    // Invalid short code
    public function test_it_returns_404_for_invalid_short_url()
    {
        $response = $this->postJson('/api/decode', [
            'short_url' => url('invalidcode')
        ]);

        $response->assertStatus(404)
                 ->assertJson(['error' => 'URL not found']);
    }

    // Validation faliure
    public function test_validation_fails_for_missing_url()
    {
        $response = $this->postJson('/api/encode', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('url');
    }

}
