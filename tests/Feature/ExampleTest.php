<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::first();

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Tanikuy');
    }
}
