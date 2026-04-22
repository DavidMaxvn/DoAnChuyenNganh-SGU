<?php

namespace Tests\Feature\Vibe\Stage01;

use App\Models\Product;
use Tests\Concerns\CreatesStage01Schema;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use CreatesStage01Schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createStage01Schema();
    }

    public function test_product_endpoints_require_admin_authentication(): void
    {
        $response = $this->getJson(route('vibe.stage01.admin.products.index'));

        $response->assertStatus(401);
    }

    public function test_admin_can_create_a_root_product_for_stage_01(): void
    {
        $admin = $this->createStage01Admin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('vibe.stage01.admin.products.store'), [
                'name' => 'Gao st25 nen tang',
                'price' => 25000,
                'quantity' => 20,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Gao st25 nen tang')
            ->assertJsonPath('data.parent_id', null);

        $this->assertDatabaseHas('products', [
            'name' => 'Gao st25 nen tang',
            'price' => 25000,
            'quantity' => 20,
        ]);
    }

    public function test_admin_can_create_a_child_product_when_parent_exists(): void
    {
        $admin = $this->createStage01Admin();
        $parent = Product::query()->create([
            'name' => 'Cam sanh',
            'price' => 30000,
            'quantity' => 15,
        ]);

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('vibe.stage01.admin.products.store'), [
                'name' => 'Cam sanh loai 1',
                'price' => 35000,
                'quantity' => 10,
                'parent_id' => $parent->id,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.parent_id', $parent->id)
            ->assertJsonPath('data.is_variant', true);

        $this->assertDatabaseHas('products', [
            'name' => 'Cam sanh loai 1',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_admin_can_view_stage_01_product_list(): void
    {
        $admin = $this->createStage01Admin();

        Product::query()->create([
            'name' => 'Xoai cat',
            'price' => 42000,
            'quantity' => 12,
        ]);

        $response = $this
            ->actingAs($admin, 'admin')
            ->getJson(route('vibe.stage01.admin.products.index'));

        $response
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('data.0.name', 'Xoai cat');
    }
}
