<?php

namespace Tests\Feature\Vibe\Stage03;

use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\CreatesStage02Schema;
use Tests\TestCase;

class StorefrontCatalogTest extends TestCase
{
    use CreatesStage02Schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createStage02Schema();
    }

    public function test_home_endpoint_returns_only_root_products(): void
    {
        $root = Product::query()->create([
            'name' => 'Gao huu co',
            'price' => 80000,
            'quantity' => 20,
            'type' => 'simple',
        ]);

        $this->createVariantProduct([
            'name' => 'Gao huu co size L',
            'price' => 90000,
            'quantity' => 10,
            'parent_id' => $root->id,
            'type' => 'simple',
        ]);

        $response = $this->getJson(route('vibe.stage03.storefront.home'));

        $response
            ->assertOk()
            ->assertJsonPath('data.count', 1)
            ->assertJsonPath('data.items.0.name', 'Gao huu co');
    }

    public function test_catalog_search_matches_root_or_variant_names(): void
    {
        $root = Product::query()->create([
            'name' => 'Xoai cat chu',
            'price' => 65000,
            'quantity' => 12,
            'type' => 'configurable',
        ]);

        $this->createVariantProduct([
            'name' => 'Xoai cat size M',
            'price' => 70000,
            'quantity' => 6,
            'parent_id' => $root->id,
            'type' => 'simple',
        ]);

        $response = $this->getJson(route('vibe.stage03.storefront.search', [
            'q' => 'size M',
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('data.count', 1)
            ->assertJsonPath('data.items.0.name', 'Xoai cat chu');
    }

    public function test_product_detail_returns_gallery_shared_attributes_and_variants(): void
    {
        $product = Product::query()->create([
            'name' => 'Hat dieu rang',
            'price' => 120000,
            'quantity' => 50,
            'type' => 'configurable',
            'image' => 'images/products/cashew-main.jpg',
        ]);

        $weight = Attribute::query()->create(['name' => 'Trong luong']);
        $size = Attribute::query()->create(['name' => 'Kich co']);

        DB::table('values')->insert([
            'product_id' => $product->id,
            'attribute_id' => $weight->id,
            'text_value' => '500g',
        ]);

        DB::table('product_images')->insert([
            [
                'image' => 'images/products/cashew-1.jpg',
                'product_id' => $product->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'images/products/cashew-2.jpg',
                'product_id' => $product->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $variant = $this->createVariantProduct([
            'name' => 'Hat dieu rang size L',
            'price' => 135000,
            'quantity' => 18,
            'parent_id' => $product->id,
            'type' => 'simple',
        ]);

        DB::table('values')->insert([
            'product_id' => $variant->id,
            'attribute_id' => $size->id,
            'text_value' => 'L',
        ]);

        $response = $this->getJson(route('vibe.stage03.storefront.products.show', $product));

        $response
            ->assertOk()
            ->assertJsonPath('data.product.name', 'Hat dieu rang')
            ->assertJsonPath('data.shared_attributes.0.attribute_name', 'Trong luong')
            ->assertJsonCount(2, 'data.gallery')
            ->assertJsonCount(1, 'data.variants')
            ->assertJsonPath('data.variants.0.attribute_values.0.value', 'L');
    }

    public function test_product_detail_can_resolve_from_variant_id(): void
    {
        $root = Product::query()->create([
            'name' => 'Cam mat ong',
            'price' => 50000,
            'quantity' => 30,
            'type' => 'configurable',
        ]);

        $variant = $this->createVariantProduct([
            'name' => 'Cam mat ong size S',
            'price' => 55000,
            'quantity' => 12,
            'parent_id' => $root->id,
            'type' => 'simple',
        ]);

        $response = $this->getJson(route('vibe.stage03.storefront.products.show', $variant));

        $response
            ->assertOk()
            ->assertJsonPath('data.product.id', $root->id)
            ->assertJsonPath('data.product.name', 'Cam mat ong');
    }

    protected function createVariantProduct(array $attributes): Product
    {
        $product = new Product();
        $product->setAttribute('name', $attributes['name']);
        $product->setAttribute('price', $attributes['price']);
        $product->setAttribute('quantity', $attributes['quantity']);
        $product->setAttribute('parent_id', $attributes['parent_id']);
        $product->setAttribute('type', $attributes['type'] ?? 'simple');
        $product->save();

        return $product;
    }
}
