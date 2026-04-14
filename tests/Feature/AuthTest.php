<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_seller(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Artisan',
            'email' => 'artisan@example.com',
            'password' => 'password',
            'role' => 'seller',
            'phone' => '+22990000000',
            'location' => 'Cotonou',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'artisan@example.com',
            'role' => 'seller',
            'seller_status' => 'pending',
        ]);
    }

    public function test_user_can_login(): void
    {
        User::factory()->create([
            'email' => 'client@example.com',
            'password' => 'secret1234',
        ]);

        $response = $this->postJson('/login', [
            'email' => 'client@example.com',
            'password' => 'secret1234',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('user.email', 'client@example.com');
    }

    public function test_only_admin_can_approve_seller(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
            'seller_status' => 'pending',
        ]);

        $buyer = User::factory()->create(['role' => 'buyer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($buyer)->patchJson("/sellers/{$seller->id}/approve");
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->patchJson("/sellers/{$seller->id}/approve");
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'seller_status' => 'approved',
        ]);
    }
}
