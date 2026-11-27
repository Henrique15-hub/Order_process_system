<?php

namespace Tests\Feature\Order;

use App\Enums\CartStatus;
use App\Enums\PaymentStatusEnum;
use App\Enums\RoleEnum;
use App\Integrations\Stripe\Contracts\StripeClientInterface;
use App\Integrations\Stripe\DTOs\PaymentLinkPayload;
use App\Integrations\Stripe\DTOs\PaymentLinkResponse;
use App\Models\Merchandise;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_cannot_create_order()
    {
        $user = User::factory()->create(['role' => RoleEnum::Admin]);

        Merchandise::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('order-store'))->assertForbidden();

        $this->assertDatabaseEmpty('orders');

        $this->assertDatabaseEmpty('payments');
    }

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create(['stock_quantity' => 10]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 5
        ]);

        $this->postJson(route('order-store'))->assertOk();

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_provider' => config('payment.active_provider'),
            'items' => json_encode([
                [
                    'id' => $merchandise->id,
                    'public_id' => $merchandise->public_id,
                    'name' => $merchandise->name,
                    'price' => $merchandise->price,
                    'quantity' => 5
                ]
            ]),
            'value' => $user->cart->actual_value,
        ]);
    }

    public function test_user_cart_get_closed()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1
        ])->assertOk();

        $this->postJson(route('order-store'))->assertOk();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'status' => CartStatus::Closed
        ]);
    }

    public function test_payment_is_created_with_order()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create([
            'stock_quantity' => 10,
        ]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 5,
        ])->assertOk();

        $stripeClient = Mockery::mock(StripeClientInterface::class);

        $stripeClient->shouldReceive('createPaymentLink')
            ->once()
            ->withArgs(function (PaymentLinkPayload $payload, int $orderId) {
                $this->assertDatabaseHas('orders', [
                    'id' => $orderId,
                ]);

                return true;
            })
            ->andReturnUsing(
                fn(PaymentLinkPayload $payload, int $orderId) =>
                    new PaymentLinkResponse(
                        id: (string) $orderId,
                        url: config('services.stripe.test_session'),
                    )
            );

        $this->instance(StripeClientInterface::class, $stripeClient);

        $response = $this->postJson(route('order-store'));

        $response->assertOk();

        $orderId = $response->json('order_id');

        $order = Order::findOrFail($orderId);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_provider' => $order->payment_provider,
            'status' => PaymentStatusEnum::Pending->value,
            'value' => $order->value,
        ]);
    }

    public function test_payment_link_is_created()
    {

        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create([
            'stock_quantity' => 10,
        ]);

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 5,
        ])->assertOk();

        $stripeClient = Mockery::mock(StripeClientInterface::class);

        $stripeClient->shouldReceive('createPaymentLink')
            ->once()
            ->withArgs(function (PaymentLinkPayload $payload, int $orderId) use ($user, $merchandise) {
                $order = Order::findOrFail($orderId);

                $payloadData = $payload->toArray();

                $this->assertSame('payment', $payloadData['mode']);

                $this->assertEquals(
                    $merchandise->name,
                    data_get($payloadData, 'line_items.0.price_data.product_data.name')
                );

                $this->assertEquals(
                    $merchandise->price,
                    data_get($payloadData, 'line_items.0.price_data.unit_amount')
                );

                $this->assertSame(
                    5,
                    data_get($payloadData, 'line_items.0.quantity')
                );

                $this->assertSame(
                    $order->id,
                    data_get($payloadData, 'metadata.order_id')
                );

                $this->assertSame(
                    $user->id,
                    data_get($payloadData, 'metadata.user_id')
                );

                return true;
            })
            ->andReturnUsing(
                fn(PaymentLinkPayload $payload, int $orderId) =>
                    new PaymentLinkResponse(
                        id: (string) $orderId,
                        url: config('services.stripe.test_session')
                    )
            );

        $this->instance(StripeClientInterface::class, $stripeClient);

        $response = $this->postJson(route('order-store'));

        $response->assertOk()
            ->assertJsonStructure([
                'order_id',
                'url',
            ])
            ->assertJsonFragment([
                'url' => config('services.stripe.test_session'),
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $response->json('order_id'),
            'user_id' => $user->id,
        ]);

    }

    public function test_user_cart_is_closed()
    {
        $user = User::factory()->create();

        $merchandise = Merchandise::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->postJson(route('cart-addMerchandiseToCart'), [
            'public_id' => $merchandise->public_id,
            'quantity' => 1
        ])->assertOk();
        $stripeClient = Mockery::mock(StripeClientInterface::class);

        $stripeClient->shouldReceive('createPaymentLink')
            ->once()
            ->andReturnUsing(
                function (PaymentLinkPayload $payload, int $orderId) {
                    return new PaymentLinkResponse(
                        id: (string) $orderId,
                        url: config('services.stripe.test_session'),
                    );
                }
            );

        $this->instance(StripeClientInterface::class, $stripeClient);

        $this->postJson(route('order-store'))->assertOk();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'status' => CartStatus::Closed
        ]);

    }

}