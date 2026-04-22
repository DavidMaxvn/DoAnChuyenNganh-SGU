<?php

namespace App\Http\Controllers\Vibe\Stage02\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage02\Admin\ProductModelRequest;
use App\Http\Requests\Vibe\Stage02\Admin\VariantStoreRequest;
use App\Models\Product;
use App\Services\Vibe\Stage02\ProductModelingService;
use Illuminate\Http\JsonResponse;

class ProductModelingController extends Controller
{
    public function __construct(
        protected ProductModelingService $service
    ) {
    }

    public function overview(): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-02',
            'message' => 'Stage 02 mo hinh hoa san pham, thuoc tinh, bien the va hinh anh.',
            'capabilities' => [
                'attribute_catalog',
                'shared_attribute_configuration',
                'variant_attribute_configuration',
                'product_gallery',
                'variant_creation',
            ],
        ]);
    }

    public function configure(ProductModelRequest $request, Product $product): JsonResponse
    {
        $product = $this->service->configureProduct($product, $request->validated());

        return response()->json([
            'message' => 'Cap nhat cau truc san pham thanh cong.',
            'data' => $this->service->snapshot($product),
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-02',
            'data' => $this->service->snapshot($product),
        ]);
    }

    public function storeVariant(VariantStoreRequest $request, Product $product): JsonResponse
    {
        $variant = $this->service->createVariant($product, $request->validated());

        return response()->json([
            'message' => 'Tao san pham bien the thanh cong.',
            'data' => $this->service->snapshot($variant->Parent),
        ], 201);
    }
}
