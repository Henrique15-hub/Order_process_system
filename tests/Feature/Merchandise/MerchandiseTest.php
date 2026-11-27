<?php

namespace Tests\Feature\Merchandise;

use App\Enums\RoleEnum;
use App\Models\Merchandise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class MerchandiseTest extends TestCase
{
    use RefreshDatabase;



    public function test_client_user_cannot_create_merchandise()
    {
        $user = User::factory()->create();

        $merchandise = [
            'name' => 'test1',
            'price' => 30.0,
            'stock_quantity' => 100
        ];

        $this->actingAs($user, 'sanctum');
        $this->postJson(route('merchandise-store'), $merchandise)
            ->assertForbidden()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseEmpty('merchandises');
    }

    public function test_client_user_cannot_update_merchandise()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();

        $infosToUpdate = [
            'name' => 'test1',
            'price' => 30.0,
            'stock_quantity' => 100
        ];

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

        $this->assertDatabaseMissing('merchandises', [
            'name' => data_get($infosToUpdate, 'name'),
            'price' => data_get($infosToUpdate, 'price'),
            'stock_quantity' => data_get($infosToUpdate, 'stock_quantity'),
        ]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('merchandise-update'), [
            'public_id' => $merchandise->public_id,
            'name' => data_get($infosToUpdate, 'name'),
            'price' => data_get($infosToUpdate, 'name'),
            'stock_quantity' => data_get($infosToUpdate, 'stock_quantity'),
        ])
            ->assertForbidden()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

        $this->assertDatabaseMissing('merchandises', [
            'name' => data_get($infosToUpdate, 'name'),
            'price' => data_get($infosToUpdate, 'price'),
            'stock_quantity' => data_get($infosToUpdate, 'stock_quantity'),
        ]);


    }

    public function test_client_user_cannot_delete_merchandise()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

        $this->actingAs($user, 'sanctum');

        $this->deleteJson(route('merchandise-delete'), ['public_id' => $merchandise->public_id])
            ->assertForbidden()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

    }

    public function test_admin_user_can_create_merchandise()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $merchandise = [
            'name' => 'test1',
            'price' => 30.0,
            'stock_quantity' => 100
        ];
        $this->actingAs($user, 'sanctum');

        $this->postJson(route('merchandise-store'), $merchandise)
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name',
                    'price',
                    'stock_quantity'
                ],
            ]);

        $merchandiseDB = Merchandise::where('name', data_get($merchandise, 'name'))
            ->where('price', data_get($merchandise, 'price'))
            ->where('stock_quantity', data_get($merchandise, 'stock_quantity'))
            ->firstOrFail();

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandiseDB->public_id,
            'name' => data_get($merchandise, 'name'),
            'price' => data_get($merchandise, 'price'),
            'stock_quantity' => data_get($merchandise, 'stock_quantity'),
        ]);
    }

    public function test_user_can_see_merchandise()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();

        $this->actingAs($user, 'sanctum');
        $this->postJson(route('merchandise-show'), [
            'public_id' => data_get($merchandise, 'public_id')
        ])
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'public_id',
                    'name',
                    'price',
                    'stock_quantity'
                ]
            ])
            ->assertJsonFragment([
                'public_id' => data_get($merchandise, 'public_id'),
            ]);
    }

    public function test_admin_user_can_update_merchandise()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $merchandise = Merchandise::factory()->create();

        $infosToUpdate = [
            'name' => 'test1',
            'price' => 30.0,
            'stock_quantity' => 100
        ];

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

        $this->assertDatabaseMissing('merchandises', [
            'name' => data_get($infosToUpdate, 'name'),
            'price' => data_get($infosToUpdate, 'price'),
            'stock_quantity' => data_get($infosToUpdate, 'stock_quantity'),
        ]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('merchandise-update'), [
            'public_id' => $merchandise->public_id,
            'name' => data_get($infosToUpdate, 'name'),
            'price' => data_get($infosToUpdate, 'price'),
            'stock_quantity' => data_get($infosToUpdate, 'stock_quantity'),
        ])->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'public_id',
                    'name',
                    'price',
                    'stock_quantity'
                ],
            ])->assertJsonFragment([
                    'name' => data_get($infosToUpdate, 'name'),
                    'price' => data_get($infosToUpdate, 'price'),
                    'stock_quantity' => data_get($infosToUpdate, 'stock_quantity')
                ]);

        $this->assertDatabaseMissing('merchandises', [
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

        $this->assertDatabaseHas('merchandises', [
            'name' => data_get($infosToUpdate, 'name'),
            'price' => data_get($infosToUpdate, 'price'),
            'stock_quantity' => data_get($infosToUpdate, 'stock_quantity')
        ]);


    }

    public function test_admin_user_can_delete_merchandise()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $merchandise = Merchandise::factory()->create();

        $this->assertDatabaseHas('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);

        $this->actingAs($user, 'sanctum');

        $this->deleteJson(route('merchandise-delete'), ['public_id' => $merchandise->public_id])
            ->assertOk()
            ->assertExactJsonStructure(['message']);

        $this->assertDatabaseMissing('merchandises', [
            'public_id' => $merchandise->public_id,
            'name' => $merchandise->name,
            'price' => $merchandise->price,
            'stock_quantity' => $merchandise->stock_quantity
        ]);
    }
}
