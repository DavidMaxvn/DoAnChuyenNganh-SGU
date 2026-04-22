<?php

namespace Tests\Feature\Vibe\Stage07;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as Database;
use Tests\Concerns\CreatesStage07Schema;
use Tests\TestCase;

class MicroserviceArchitectureTest extends TestCase
{
    use CreatesStage07Schema;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        Database::purge('sqlite');
        Database::setDefaultConnection('sqlite');
        Database::reconnect('sqlite');

        $this->createStage07Schema();
    }

    public function test_overview_endpoint_describes_microservice_boundaries(): void
    {
        $this->getJson(route('vibe.stage07.microservices.overview'))
            ->assertOk()
            ->assertJsonPath('data.stage', 'Stage 07')
            ->assertJsonPath('data.boundaries.inventory', 'Kiem tra va reserve ton kho truoc khi tao order.');
    }

    public function test_inventory_service_subtracts_existing_reservations_from_available_stock(): void
    {
        $product = $this->createStage06Product([
            'name' => 'Sua tuoi',
            'quantity' => 5,
        ]);

        DB::table('inventory_reservations')->insert([
            'reservation_group' => 'group-001',
            'reservation_code' => 'reservation-001',
            'product_id' => $product->id,
            'quantity' => 2,
            'status' => 'RESERVED',
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->postJson(route('vibe.stage07.microservices.inventory.check'), [
            'correlation_id' => 'corr-inventory-001',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                ],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.is_available', false)
            ->assertJsonPath('data.items.0.active_stock', 5)
            ->assertJsonPath('data.items.0.reserved', 2)
            ->assertJsonPath('data.items.0.available', 3);
    }

    public function test_pricing_service_calculates_shipping_coupon_and_writes_outbox_event(): void
    {
        $product = $this->createStage06Product([
            'name' => 'Gao huu co',
            'price' => 10000,
            'quantity' => 20,
        ]);
        $city = $this->createStage05City([
            'shipping_fee' => 5000,
        ]);
        $coupon = $this->createStage05Coupon([
            'name' => 'MICRO3000',
            'discount' => 3000,
            'discount_max' => 3000,
            'type' => 'price',
        ]);

        $response = $this->postJson(route('vibe.stage07.microservices.pricing.quote'), [
            'correlation_id' => 'corr-pricing-001',
            'city_id' => $city->id,
            'coupon_id' => $coupon->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.subtotal', 20000)
            ->assertJsonPath('data.shipping_fee', 5000)
            ->assertJsonPath('data.discount', 3000)
            ->assertJsonPath('data.grand_total', 22000);

        $this->assertDatabaseHas('microservice_outbox_events', [
            'service_name' => 'pricing',
            'event_type' => 'pricing.quoted',
            'correlation_id' => 'corr-pricing-001',
        ]);
    }

    public function test_checkout_orchestrator_calls_pricing_inventory_and_outbox(): void
    {
        $user = $this->createStage05User();
        $product = $this->createStage06Product([
            'name' => 'Tra xanh',
            'price' => 20000,
            'quantity' => 10,
        ]);
        $city = $this->createStage05City([
            'shipping_fee' => 10000,
        ]);

        $response = $this->postJson(route('vibe.stage07.microservices.checkout.simulate'), [
            'correlation_id' => 'corr-checkout-001',
            'user_id' => $user->id,
            'city_id' => $city->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.correlation_id', 'corr-checkout-001')
            ->assertJsonPath('data.services_called.0', 'pricing')
            ->assertJsonPath('data.services_called.1', 'inventory')
            ->assertJsonPath('data.quote.grand_total', 50000)
            ->assertJsonPath('data.reservation.items.0.product_id', $product->id);

        $this->assertDatabaseHas('inventory_reservations', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => 2,
            'status' => 'RESERVED',
            'correlation_id' => 'corr-checkout-001',
        ]);

        $this->assertDatabaseHas('microservice_outbox_events', [
            'event_type' => 'inventory.reserved',
            'correlation_id' => 'corr-checkout-001',
        ]);
        $this->assertDatabaseHas('microservice_outbox_events', [
            'event_type' => 'checkout.simulated',
            'correlation_id' => 'corr-checkout-001',
        ]);
    }

    public function test_checkout_orchestrator_rejects_when_inventory_is_not_available(): void
    {
        $product = $this->createStage06Product([
            'name' => 'Banh mi',
            'price' => 15000,
            'quantity' => 1,
        ]);

        $response = $this->postJson(route('vibe.stage07.microservices.checkout.simulate'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['inventory']);

        $this->assertSame(0, DB::table('inventory_reservations')->count());
    }
}
