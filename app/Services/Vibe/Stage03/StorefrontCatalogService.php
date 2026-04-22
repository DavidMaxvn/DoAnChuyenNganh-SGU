<?php

namespace App\Services\Vibe\Stage03;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StorefrontCatalogService
{
    public function homeFeed(int $limit = 8): array
    {
        $products = Product::query()
            ->whereNull('parent_id')
            ->orderByDesc('id')
            ->limit(max(1, $limit))
            ->get();

        return [
            'count' => $products->count(),
            'items' => $products->map(fn (Product $product) => $this->toCard($product))->values()->all(),
        ];
    }

    public function catalog(?string $query, ?string $type, int $limit = 12): array
    {
        $query = trim((string) $query);

        $productsQuery = Product::query()
            ->whereNull('parent_id')
            ->when($type, fn ($builder) => $builder->where('type', $type))
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($subQuery) use ($query) {
                    $subQuery->where('name', 'like', '%' . $query . '%')
                        ->orWhereIn('id', function ($variantQuery) use ($query) {
                            $variantQuery->select('parent_id')
                                ->from('products')
                                ->whereNotNull('parent_id')
                                ->where('name', 'like', '%' . $query . '%');
                        });
                });
            })
            ->orderByDesc('id')
            ->limit(max(1, $limit));

        $products = $productsQuery->get();

        return [
            'query' => $query,
            'count' => $products->count(),
            'items' => $products->map(fn (Product $product) => $this->toCard($product))->values()->all(),
        ];
    }

    public function detail(Product $product): array
    {
        $rootProduct = $product->parent_id ? $product->Parent : $product;
        $rootProduct = $rootProduct->fresh();

        $gallery = DB::table('product_images')
            ->where('product_id', $rootProduct->id)
            ->orderBy('id')
            ->pluck('image')
            ->values()
            ->all();

        $sharedAttributes = DB::table('values')
            ->join('attributes', 'attributes.id', '=', 'values.attribute_id')
            ->where('values.product_id', $rootProduct->id)
            ->orderBy('attributes.name')
            ->get(['attributes.id', 'attributes.name', 'values.text_value'])
            ->map(fn ($row) => [
                'attribute_id' => $row->id,
                'attribute_name' => $row->name,
                'value' => $row->text_value,
            ])
            ->values()
            ->all();

        $variants = Product::query()
            ->where('parent_id', $rootProduct->id)
            ->orderBy('id')
            ->get()
            ->map(function (Product $variant) {
                $attributeValues = DB::table('values')
                    ->join('attributes', 'attributes.id', '=', 'values.attribute_id')
                    ->where('values.product_id', $variant->id)
                    ->orderBy('attributes.name')
                    ->get(['attributes.id', 'attributes.name', 'values.text_value'])
                    ->map(fn ($row) => [
                        'attribute_id' => $row->id,
                        'attribute_name' => $row->name,
                        'value' => $row->text_value,
                    ])
                    ->values()
                    ->all();

                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'price' => (float) $variant->price,
                    'quantity' => (int) $variant->quantity,
                    'image' => $this->resolveMainImage($variant),
                    'attribute_values' => $attributeValues,
                ];
            })
            ->values()
            ->all();

        $priceRange = $this->resolvePriceRange($rootProduct, $variants);

        return [
            'product' => [
                'id' => $rootProduct->id,
                'name' => $rootProduct->name,
                'type' => $rootProduct->type ?? 'simple',
                'main_image' => $this->resolveMainImage($rootProduct),
                'price_from' => $priceRange['from'],
                'price_to' => $priceRange['to'],
                'quantity' => (int) $rootProduct->quantity,
            ],
            'shared_attributes' => $sharedAttributes,
            'gallery' => $gallery,
            'variants' => $variants,
        ];
    }

    protected function toCard(Product $product): array
    {
        $priceRange = $this->resolvePriceRange($product);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'type' => $product->type ?? 'simple',
            'main_image' => $this->resolveMainImage($product),
            'price_from' => $priceRange['from'],
            'price_to' => $priceRange['to'],
            'has_variants' => Product::query()->where('parent_id', $product->id)->exists(),
        ];
    }

    protected function resolveMainImage(Product $product): ?string
    {
        if (!empty($product->image)) {
            return $product->image;
        }

        return DB::table('product_images')
            ->where('product_id', $product->id)
            ->orderBy('id')
            ->value('image');
    }

    protected function resolvePriceRange(Product $product, ?array $variants = null): array
    {
        if ($variants === null) {
            $variants = Product::query()
                ->where('parent_id', $product->id)
                ->get(['price'])
                ->map(fn ($variant) => ['price' => (float) $variant->price])
                ->all();
        }

        if (empty($variants)) {
            return [
                'from' => (float) $product->price,
                'to' => (float) $product->price,
            ];
        }

        $prices = array_map(fn ($variant) => (float) $variant['price'], $variants);

        return [
            'from' => min($prices),
            'to' => max($prices),
        ];
    }
}
