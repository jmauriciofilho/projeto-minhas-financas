<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_home(): void
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }

    public function test_returns_a_successful_response_for_authenticated_user(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('home'));

        $response->assertOk();
    }
}
