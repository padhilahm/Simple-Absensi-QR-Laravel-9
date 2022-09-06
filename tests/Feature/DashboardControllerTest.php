<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function testIndexDashboardSuccess()
    {
        // login
        $this->post('/login', [
            'email' => 'padhilahm@gmail.com',
            'password' => 'password'
        ]);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function testIndexDashboardFailed()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function testDashboardAttendanceSuccess()
    {
        // login
        $this->post('/login', [
            'email' => 'padhilahm@gmail.com',
            'password' => 'password'
        ]);

        $response = $this->get('/dashboard/attendance/1/2022-10-10');
        $response->assertStatus(200);
    }

    public function testDashboardAttendanceFailed()
    {
        $response = $this->get('/dashboard/attendance/1/1');
        $response->assertRedirect('/login');
    }
}
