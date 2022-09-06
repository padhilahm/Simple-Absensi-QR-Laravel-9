<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testLoginIndex()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testLoginSuccess()
    {
        $response = $this->post('/login', [
            'email' => 'padhilahm@gmail.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function testLoginFailed()
    {
        $response = $this->post('/login', [
            'email' => 'padhilahm@gmail.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/login');
    }

    public function testLogout()
    {
        $response = $this->get('/logout');

        $response->assertRedirect('/login');
    }
}
