<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_account()
    {
        $user = [
            "name" => "Test1",
            "email" => "mail@mail.com",
            "password" => "password",
        ];

        $this->postJson(route('user-store'), $user)
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name',
                    'email',
                    'role'
                ],
            ]);

        $userDB = User::where('email', data_get($user, 'email'))->firstOrFail();

        $this->assertDatabaseHas('users', [
            'id' => $userDB->id,
            'name' => $userDB->name,
            'email' => $userDB->email,
            'role' => $userDB->role,

        ]);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);
        $this->actingAs($user, 'sanctum');

        $this->deleteJson(route('user-destroy'))
            ->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);

    }
}