<?php

namespace Tests\Feature\Vibe\Stage02;

use App\Models\Attribute;
use App\Models\Product;
use Tests\Concerns\CreatesStage02Schema;
use Tests\TestCase;

class ProductModelingTest extends TestCase
{
    use CreatesStage02Schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createStage02Schema();
    }

    public function test_stage_02_endpoints_require_admin_authentication(): void
    {
        $product = Product::query()->create([
            'name' => 'Gao nen tang',
            'price' => 30000,
            'quantity' => 10,
            'type' => 'simple',
        ]);

        $response = $this->getJson(route('vibe.stage02.admin.products.model.show', $product));

        $response->assertStatus(401);
    }

    public function test_admin_can_create_attributes_for_product_modeling(): void
    {
        $admin = $this->createStage01Admin();

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('vibe.stage02.admin.attributes.store'), [
                'name' => 'Mau sac',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Mau sac');

        $this->assertDatabaseHas('attributes', [
            'name' => 'Mau sac',
        ]);
    }

    public function test_configurable_product_cannot_overlap_shared_and_variant_attributes(): void
    {
        $admin = $this->createStage01Admin();
        $product = Product::query()->create([
            'name' => 'Cam sanh',
            'price' => 45000,
            'quantity' => 30,
            'type' => 'simple',
        ]);

        $attribute = Attribute::query()->create(['name' => 'Trong luong']);

        $response = $this
            ->actingAs($admin, 'admin')
            ->putJson(route('vibe.stage02.admin.products.model.configure', $product), [
                'type' => 'configurable',
                'shared_attribute_ids' => [$attribute->id],
                'variant_attribute_ids' => [$attribute->id],
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['shared_attribute_ids']);
    }

    public function test_admin_can_configure_product_with_attributes_gallery_and_shared_values(): void
    {
        $admin = $this->createStage01Admin();
        $product = Product::query()->create([
            'name' => 'Gao ST25',
            'price' => 50000,
            'quantity' => 25,
            'type' => 'simple',
        ]);

        $weight = Attribute::query()->create(['name' => 'Trong luong']);
        $color = Attribute::query()->create(['name' => 'Mau sac']);

        $response = $this
            ->actingAs($admin, 'admin')
            ->putJson(route('vibe.stage02.admin.products.model.configure', $product), [
                'type' => 'configurable',
                'main_image' => 'images/products/st25-main.jpg',
                'gallery' => [
                    'images/products/st25-1.jpg',
                    'images/products/st25-2.jpg',
                ],
                'shared_attribute_ids' => [$weight->id],
                'variant_attribute_ids' => [$color->id],
                'shared_attribute_values' => [
                    (string) $weight->id => '5kg',
                ],
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.product.type', 'configurable')
            ->assertJsonPath('data.product.main_image', 'images/products/st25-main.jpg')
            ->assertJsonPath('data.shared_attribute_ids.0', $weight->id)
            ->assertJsonPath('data.variant_attribute_ids.0', $color->id)
            ->assertJsonPath('data.shared_attribute_values.' . $weight->id, '5kg')
            ->assertJsonCount(2, 'data.gallery');

        $this->assertDatabaseHas('product_attr_config', [
            'product_id' => $product->id,
            'attribute_id' => $weight->id,
            'is_private' => 0,
        ]);

        $this->assertDatabaseHas('product_attr_config', [
            'product_id' => $product->id,
            'attribute_id' => $color->id,
            'is_private' => 1,
        ]);

        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'image' => 'images/products/st25-1.jpg',
        ]);
    }

    public function test_admin_can_create_variant_for_configurable_product(): void
    {
        $admin = $this->createStage01Admin();
        $product = Product::query()->create([
            'name' => 'Hat dieu',
            'price' => 120000,
            'quantity' => 40,
            'type' => 'configurable',
        ]);

        $size = Attribute::query()->create(['name' => 'Kich co']);

        $this
            ->actingAs($admin, 'admin')
            ->putJson(route('vibe.stage02.admin.products.model.configure', $product), [
                'type' => 'configurable',
                'variant_attribute_ids' => [$size->id],
            ])
            ->assertOk();

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('vibe.stage02.admin.products.variants.store', $product), [
                'name' => 'Hat dieu size L',
                'price' => 135000,
                'quantity' => 15,
                'image' => 'images/products/cashew-size-l.jpg',
                'attribute_values' => [
                    (string) $size->id => 'L',
                ],
            ]);

        $response
            ->assertCreated()
            ->assertJsonCount(1, 'data.variants')
            ->assertJsonPath('data.variants.0.name', 'Hat dieu size L')
            ->assertJsonPath('data.variants.0.attribute_values.0.value', 'L');

        $this->assertDatabaseHas('products', [
            'name' => 'Hat dieu size L',
            'parent_id' => $product->id,
        ]);
    }

    public function test_duplicate_variant_combination_is_rejected(): void
    {
        $admin = $this->createStage01Admin();
        $product = Product::query()->create([
            'name' => 'Xoai cat',
            'price' => 65000,
            'quantity' => 20,
            'type' => 'configurable',
        ]);

        $size = Attribute::query()->create(['name' => 'Kich co']);

        $this
            ->actingAs($admin, 'admin')
            ->putJson(route('vibe.stage02.admin.products.model.configure', $product), [
                'type' => 'configurable',
                'variant_attribute_ids' => [$size->id],
            ])
            ->assertOk();

        $payload = [
            'name' => 'Xoai cat size M',
            'price' => 70000,
            'quantity' => 8,
            'attribute_values' => [
                (string) $size->id => 'M',
            ],
        ];

        $this
            ->actingAs($admin, 'admin')
            ->postJson(route('vibe.stage02.admin.products.variants.store', $product), $payload)
            ->assertCreated();

        $response = $this
            ->actingAs($admin, 'admin')
            ->postJson(route('vibe.stage02.admin.products.variants.store', $product), array_merge($payload, [
                'name' => 'Xoai cat size M lan 2',
            ]));

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['attribute_values']);
    }
}
