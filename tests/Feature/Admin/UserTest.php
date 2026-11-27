<?php
namespace Tests\Feature\Admin;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_see_all_users()
    {
        $user = User::factory()->create();

        User::factory()->createMany(10);

        $this->actingAs($user, 'sanctum');
        $this->getJson(route('admin-user-index'))
            ->assertForbidden()
            ->assertJsonMissing([
                "data"
            ]);
    }

    public function test_user_cannot_see_an_user()
    {
        $user = User::factory()->create();

        $anotherUser = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('admin-user-show'), [
            'id' => $anotherUser->id,
        ])->assertForbidden()
            ->assertJsonMissing([
                'data'
            ]);
    }
    public function test_user_cannot_update_an_user()
    {
        $user = User::factory()->create();

        $anotherUser = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('admin-user-update'), [
            'id' => $anotherUser->id,
            'name' => 'Test1',
            'email' => 'mail@mail.com',
            'role' => RoleEnum::Client,
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'name' => 'Test1',
            'email' => 'mail@mail.com',
            'role' => RoleEnum::Client,
        ]);
    }

    public function test_user_cannot_delete_an_user()
    {
        $admin = User::factory()->create(['role' => RoleEnum::Admin]);

        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->deleteJson(route('admin-user-destroy'), [
            'id' => $admin->id,
        ])->assertForbidden()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'email' => $admin->email
        ]);

    }

    public function test_admin_user_can_delete_an_user()
    {
        $admin = User::factory()->create(['role' => RoleEnum::Admin]);

        $user = User::factory()->create();

        $this->actingAs($admin, 'sanctum');

        $this->deleteJson(route('admin-user-destroy'), [
            'id' => $user->id,
        ])->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_admin_user_can_update_an_user()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $anotherUser = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('admin-user-update'), [
            'id' => $anotherUser->id,
            'name' => 'Test1',
            'email' => 'mail@mail.com',
            'role' => RoleEnum::Client,
        ])->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $anotherUser->id,
            'name' => 'Test1',
            'email' => 'mail@mail.com',
            'role' => RoleEnum::Client,
        ]);
    }

    public function test_admin_user_can_see_an_user()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $anotherUser = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('admin-user-show'), [
            'id' => $anotherUser->id,
        ])->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role'
                ],
            ]);
    }

    public function test_admin_user_can_see_all_users()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        User::factory()->createMany(10);

        $this->actingAs($user, 'sanctum');
        $this->getJson(route('admin-user-index'))
            ->assertOk()
            ->assertJsonStructure([
                'data'
            ]);
    }
}