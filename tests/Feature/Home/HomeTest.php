<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_can_access_home()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_redirect_to_adminhome()
    {
        $response = $this->get('/');
    }
}
