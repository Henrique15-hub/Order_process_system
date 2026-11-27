<?php

namespace Tests\Feature\Cart;

use App\Enums\CartStatus;
use App\Enums\RoleEnum;
use App\Models\Merchandise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_is_created_with_user()
    {
        $user = [
            'name' => 'test1',
            'email' => 'mail@mail.com',
            'password' => 'password'
        ];

        $this->postJson(route('user-store'), $user)
            ->assertCreated();

        $userDB = User::where('email', data_get($user, 'email'))->firstOrFail();

        $this->assertDatabaseHas('carts', [
            'user_id' => $userDB->id
        ]);
    }

    public function test_cart_is_deleted_with_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->deleteJson(route('user-destroy'))
            ->assertOk();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email
        ]);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id
        ]);
    }

    public function test_admin_user_cannot_see_items()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $this->actingAs($user, 'sanctum');

        $this->getJson(route('cart-showItems'))
            ->assertForbidden()
            ->assertJsonStructure(['message'])
            ->assertJsonMissing([
                'items'
            ]);
    }

    public function test_admin_user_cannot_add_merchandise_to_cart()
    {

        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $merchandise = Merchandise::factory()->create();
        $this->actingAs($user, 'sanctum');
        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1,
        ])
            ->assertForbidden()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'items' => json_encode([
                'id' => $merchandise->id,
                'public_id' => $merchandise->public_id,
                'name' => $merchandise->name,
                'price' => $merchandise->price,
                'quantity' => 1
            ]),
            'actual_value' => $merchandise->price,
            'status' => CartStatus::Open,
        ]);
    }

    public function test_admin_user_cannot_remove_or_decreace_merchandise_to_cart()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        $merchandise = Merchandise::factory()->create();
        $this->actingAs($user, 'sanctum');
        $this->postJson(route('cart-removeMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1
        ])
            ->assertForbidden()
            ->assertJsonStructure(['message']);
    }

    public function test_user_can_see_his_own_items()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1,
        ])
            ->assertOk();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'items' => json_encode([
                [
                    'id' => $merchandise->id,
                    'public_id' => $merchandise->public_id,
                    'name' => $merchandise->name,
                    'price' => $merchandise->price,
                    'quantity' => 1
                ]
            ]),
            'actual_value' => $merchandise->price,
            'status' => CartStatus::Open,
        ]);
        $this->getJson(route('cart-showItems'))
            ->assertOk()
            ->assertJsonFragment([
                'data' => [
                    [
                        'public_id' => $merchandise->public_id,
                        'name' => $merchandise->name,
                        'price' => $merchandise->price,
                        'quantity' => 1,
                    ]
                ],
            ]);
    }

    public function test_user_can_add_merchandise()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1,
        ])
            ->assertOk()
            ->assertJsonFragment([
                'data' => [
                    'public_id' => $merchandise->public_id,
                    'name' => $merchandise->name,
                    'price' => $merchandise->price,
                    'quantity_added_to_cart' => 1,

                ]
            ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'items' => json_encode([
                [
                    'id' => $merchandise->id,
                    'public_id' => $merchandise->public_id,
                    'name' => $merchandise->name,
                    'price' => $merchandise->price,
                    'quantity' => 1
                ]
            ]),
            'actual_value' => $merchandise->price,
            'status' => CartStatus::Open,
        ]);
    }

    public function test_user_can_delete_or_decreace_merchandise()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();
        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1,
        ])
            ->assertOk();

        $this->postJson(route('cart-removeMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1,
        ])->assertOk()
            ->assertJsonFragment([
                'data' => [
                    'public_id' => $merchandise->public_id,
                    'name' => $merchandise->name,
                    'price' => $merchandise->price,
                    'quantity_remaining_in_cart' => 0,
                ]
            ]);

        $this->assertDatabaseMissing('carts', [
            [
                'user_id' => $user->id,
                'items' => json_encode([
                    [
                        'id' => $merchandise->id,
                        'public_id' => $merchandise->public_id,
                        'name' => $merchandise->name,
                        'price' => $merchandise->price,
                        'quantity' => 1
                    ]
                ]),
                'actual_value' => $merchandise->price,
                'status' => CartStatus::Open,
            ]
        ]);
    }

    public function test_user_cannot_put_more_quantity_than_stock_quantity()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create(['stock_quantity' => 10]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 11,
        ])->assertUnprocessable();

        $this->assertDatabaseMissing('carts', [
            [
                'user_id' => $user->id,
                'items' => json_encode([
                    [
                        'id' => $merchandise->id,
                        'public_id' => $merchandise->public_id,
                        'name' => $merchandise->name,
                        'price' => $merchandise->price,
                        'quantity' => 11
                    ]
                ]),
                'actual_value' => $merchandise->price,
                'status' => CartStatus::Open,
            ]
        ]);

        $this->assertDatabaseHas('merchandises', [
            'id' => $merchandise->id,
            'public_id' => $merchandise->public_id,
            'stock_quantity' => $merchandise->stock_quantity,
        ]);
    }

    public function test_actual_value_is_added()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create([
            'price' => 10,
            'stock_quantity' => 20,
        ]);

        $this->actingAs($user, 'sanctum');

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'actual_value' => 0,
        ]);

        $requestQuantity = 5;
        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => $requestQuantity,
        ])->assertOk();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'actual_value' => $merchandise->price * $requestQuantity
        ]);
    }

    public function test_actual_value_is_subtracted()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create([
            'price' => 10,
            'stock_quantity' => 20,
        ]);

        $this->actingAs($user, 'sanctum');


        $requestQuantity = 5;
        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => $requestQuantity,
        ])->assertOk();


        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'actual_value' => $merchandise->price * $requestQuantity
        ]);

        $newRequestQuantity = 2;
        $this->postJson(route('cart-removeMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => $newRequestQuantity,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'actual_value' => $merchandise->price * ($requestQuantity - $newRequestQuantity)
        ]);
    }

    public function test_user_cannot_subtract_more_than_quantity_in_cart()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create(['stock_quantity' => 10]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 5
        ])->assertOk();

        $this->postJson(route('cart-removeMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 6
        ])->assertUnprocessable();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'items' => json_encode([
                [
                    'id' => $merchandise->id,
                    'public_id' => $merchandise->public_id,
                    'name' => $merchandise->name,
                    'price' => $merchandise->price,
                    'quantity' => 5
                ]
            ]),
        ]);
    }
}