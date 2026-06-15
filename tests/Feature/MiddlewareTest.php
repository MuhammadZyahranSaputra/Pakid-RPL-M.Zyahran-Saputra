<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_can_post_without_middleware(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
    }
}
