<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    /**
     * Test admin dashboard loads without database errors.
     */
    public function test_admin_dashboard_loads()
    {
        // Skip authentication for this test
        $this->withoutMiddleware();

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }
}
