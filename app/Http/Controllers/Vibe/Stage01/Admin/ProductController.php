<?php

namespace App\Http\Controllers\Vibe\Stage01\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage01\Admin\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->select(['id', 'name', 'price', 'quantity', 'parent_id', 'created_at'])
            ->orderByDesc('id')
            ->get()
            ->map(fn (Product $product) => $this->transformProduct($product));

        return response()->json([
            'stage' => 'stage-01',
            'message' => 'Danh sach du lieu san pham nen tang admin.',
            'count' => $products->count(),
            'data' => $products,
        ]);
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = new Product();
        $product->setAttribute('name', trim((string) $request->input('name')));
        $product->setAttribute('price', (float) $request->input('price'));
        $product->setAttribute('quantity', (int) $request->input('quantity'));
        $product->setAttribute('parent_id', $request->input('parent_id'));
        $product->save();

        return response()->json([
            'message' => 'Tao san pham nen tang thanh cong.',
            'data' => $this->transformProduct($product->fresh()),
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-01',
            'data' => $this->transformProduct($product),
        ]);
    }

    protected function transformProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => (int) $product->quantity,
            'parent_id' => $product->parent_id,
            'is_variant' => !empty($product->parent_id),
            'created_at' => optional($product->created_at)->toDateTimeString(),
        ];
    }
}
