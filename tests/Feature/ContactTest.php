<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_submission()
    {
        $response = $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'This is a test message',
            'g-recaptcha-response' => 'test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_contact_api_submission()
    {
        $response = $this->postJson('/api/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'API test message',
        ], [
            'Origin' => 'https://yourwebsite.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', [
            'email' => 'jane@example.com',
            'source_website' => 'https://yourwebsite.com',
        ]);
    }
}
