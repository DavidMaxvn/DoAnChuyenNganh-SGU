<?php

namespace App\Services\Vibe\Stage02;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductModelingService
{
    public function configureProduct(Product $product, array $payload): Product
    {
        $sharedIds = $this->normalizeIds($payload['shared_attribute_ids'] ?? []);
        $variantIds = $this->normalizeIds($payload['variant_attribute_ids'] ?? []);
        $type = (string) ($payload['type'] ?? 'simple');

        $this->ensureNoAttributeOverlap($sharedIds, $variantIds);

        if ($type === 'configurable' && empty($variantIds)) {
            throw ValidationException::withMessages([
                'variant_attribute_ids' => 'San pham configurable phai co it nhat 1 thuoc tinh bien the.',
            ]);
        }

        return DB::transaction(function () use ($product, $payload, $type, $sharedIds, $variantIds) {
            $product->setAttribute('type', $type);
            $product->setAttribute('image', $payload['main_image'] ?? null);
            $product->save();

            $this->syncAttributeConfiguration($product, $sharedIds, $variantIds);
            $this->syncSharedAttributeValues($product, $payload['shared_attribute_values'] ?? [], $sharedIds);
            $this->syncGalleryImages($product, $payload['gallery'] ?? []);

            return $product->fresh();
        });
    }

    public function createVariant(Product $parent, array $payload): Product
    {
        $this->ensureConfigurableParent($parent);

        $requiredAttributeIds = $this->getConfiguredAttributeIds($parent, true);
        $attributeValues = $this->normalizeAttributeMap($payload['attribute_values'] ?? []);

        $this->ensureVariantAttributesProvided($requiredAttributeIds, $attributeValues);
        $this->ensureVariantCombinationIsUnique($parent, $requiredAttributeIds, $attributeValues);

        return DB::transaction(function () use ($parent, $payload, $attributeValues) {
            $variant = new Product();
            $variant->setAttribute('name', trim((string) $payload['name']));
            $variant->setAttribute('price', (float) $payload['price']);
            $variant->setAttribute('quantity', (int) $payload['quantity']);
            $variant->setAttribute('image', $payload['image'] ?? null);
            $variant->setAttribute('parent_id', $parent->id);
            $variant->setAttribute('type', 'simple');
            $variant->save();

            $valueRows = [];
            foreach ($attributeValues as $attributeId => $textValue) {
                $valueRows[] = [
                    'product_id' => $variant->id,
                    'attribute_id' => $attributeId,
                    'text_value' => $textValue,
                ];
            }

            DB::table('values')->insert($valueRows);

            return $variant->fresh();
        });
    }

    public function snapshot(Product $product): array
    {
        $product = $product->fresh();

        $sharedAttributeIds = $this->getConfiguredAttributeIds($product, false);
        $variantAttributeIds = $this->getConfiguredAttributeIds($product, true);

        $sharedAttributeValues = DB::table('values')
            ->where('product_id', $product->id)
            ->pluck('text_value', 'attribute_id')
            ->toArray();

        $gallery = DB::table('product_images')
            ->where('product_id', $product->id)
            ->orderBy('id')
            ->get(['id', 'image'])
            ->map(fn ($image) => [
                'id' => $image->id,
                'image' => $image->image,
            ])
            ->values()
            ->all();

        $variants = Product::query()
            ->where('parent_id', $product->id)
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
                    'image' => $variant->image,
                    'attribute_values' => $attributeValues,
                ];
            })
            ->values()
            ->all();

        return [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type ?? 'simple',
                'main_image' => $product->image,
                'price' => (float) $product->price,
                'quantity' => (int) $product->quantity,
            ],
            'shared_attribute_ids' => $sharedAttributeIds,
            'variant_attribute_ids' => $variantAttributeIds,
            'shared_attribute_values' => $sharedAttributeValues,
            'gallery' => $gallery,
            'variants' => $variants,
        ];
    }

    protected function normalizeIds(array $ids): array
    {
        return collect($ids)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    protected function normalizeAttributeMap(array $attributeValues): array
    {
        return collect($attributeValues)
            ->mapWithKeys(function ($value, $attributeId) {
                $attributeId = (int) $attributeId;
                $value = trim((string) $value);

                return [$attributeId => $value];
            })
            ->filter(fn ($value, $attributeId) => $attributeId > 0 && $value !== '')
            ->all();
    }

    protected function ensureNoAttributeOverlap(array $sharedIds, array $variantIds): void
    {
        $overlap = array_values(array_intersect($sharedIds, $variantIds));

        if (!empty($overlap)) {
            throw ValidationException::withMessages([
                'shared_attribute_ids' => 'Thuoc tinh chung va thuoc tinh bien the khong duoc trung nhau.',
            ]);
        }
    }

    protected function syncAttributeConfiguration(Product $product, array $sharedIds, array $variantIds): void
    {
        DB::table('product_attr_config')
            ->where('product_id', $product->id)
            ->delete();

        $rows = [];

        foreach ($sharedIds as $attributeId) {
            $rows[] = [
                'product_id' => $product->id,
                'attribute_id' => $attributeId,
                'is_private' => 0,
            ];
        }

        foreach ($variantIds as $attributeId) {
            $rows[] = [
                'product_id' => $product->id,
                'attribute_id' => $attributeId,
                'is_private' => 1,
            ];
        }

        if (!empty($rows)) {
            DB::table('product_attr_config')->insert($rows);
        }
    }

    protected function syncSharedAttributeValues(Product $product, array $sharedAttributeValues, array $sharedIds): void
    {
        DB::table('values')
            ->where('product_id', $product->id)
            ->delete();

        $normalizedValues = $this->normalizeAttributeMap($sharedAttributeValues);
        $rows = [];

        foreach ($sharedIds as $attributeId) {
            if (isset($normalizedValues[$attributeId])) {
                $rows[] = [
                    'product_id' => $product->id,
                    'attribute_id' => $attributeId,
                    'text_value' => $normalizedValues[$attributeId],
                ];
            }
        }

        if (!empty($rows)) {
            DB::table('values')->insert($rows);
        }
    }

    protected function syncGalleryImages(Product $product, array $gallery): void
    {
        DB::table('product_images')
            ->where('product_id', $product->id)
            ->delete();

        $rows = collect($gallery)
            ->map(fn ($image) => trim((string) $image))
            ->filter()
            ->values()
            ->map(fn ($image) => [
                'image' => $image,
                'product_id' => $product->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->all();

        if (!empty($rows)) {
            DB::table('product_images')->insert($rows);
        }
    }

    protected function ensureConfigurableParent(Product $parent): void
    {
        if (($parent->type ?? 'simple') !== 'configurable') {
            throw ValidationException::withMessages([
                'product' => 'Chi san pham configurable moi duoc tao bien the.',
            ]);
        }
    }

    protected function getConfiguredAttributeIds(Product $product, bool $isPrivate): array
    {
        return DB::table('product_attr_config')
            ->where('product_id', $product->id)
            ->where('is_private', $isPrivate ? 1 : 0)
            ->pluck('attribute_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
    }

    protected function ensureVariantAttributesProvided(array $requiredIds, array $attributeValues): void
    {
        $providedIds = array_keys($attributeValues);
        sort($requiredIds);
        sort($providedIds);

        if ($requiredIds !== $providedIds) {
            throw ValidationException::withMessages([
                'attribute_values' => 'Can cung cap day du gia tri cho toan bo thuoc tinh bien the.',
            ]);
        }
    }

    protected function ensureVariantCombinationIsUnique(Product $parent, array $requiredIds, array $attributeValues): void
    {
        $normalizedIncoming = $this->normalizeForComparison($attributeValues, $requiredIds);

        $variantIds = Product::query()
            ->where('parent_id', $parent->id)
            ->pluck('id');

        foreach ($variantIds as $variantId) {
            $existingValues = DB::table('values')
                ->where('product_id', $variantId)
                ->whereIn('attribute_id', $requiredIds)
                ->pluck('text_value', 'attribute_id')
                ->toArray();

            if ($this->normalizeForComparison($existingValues, $requiredIds) === $normalizedIncoming) {
                throw ValidationException::withMessages([
                    'attribute_values' => 'To hop bien the nay da ton tai.',
                ]);
            }
        }
    }

    protected function normalizeForComparison(array $attributeValues, array $requiredIds): array
    {
        $normalized = [];

        foreach ($requiredIds as $attributeId) {
            $normalized[$attributeId] = mb_strtolower(trim((string) ($attributeValues[$attributeId] ?? '')));
        }

        ksort($normalized);

        return $normalized;
    }
}
