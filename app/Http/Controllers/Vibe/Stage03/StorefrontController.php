<?php

namespace App\Http\Controllers\Vibe\Stage03;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage03\CatalogRequest;
use App\Models\Product;
use App\Services\Vibe\Stage03\StorefrontCatalogService;
use Illuminate\Http\JsonResponse;

class StorefrontController extends Controller
{
    public function __construct(
        protected StorefrontCatalogService $service
    ) {
    }

    public function overview(): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-03',
            'message' => 'Storefront public backend is ready.',
            'capabilities' => [
                'home_feed',
                'catalog_listing',
                'product_detail',
                'keyword_search',
            ],
        ]);
    }

    public function home(CatalogRequest $request): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-03',
            'data' => $this->service->homeFeed((int) $request->input('limit', 8)),
        ]);
    }

    public function catalog(CatalogRequest $request): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-03',
            'data' => $this->service->catalog(
                $request->input('q'),
                $request->input('type'),
                (int) $request->input('limit', 12)
            ),
        ]);
    }

    public function search(CatalogRequest $request): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-03',
            'data' => $this->service->catalog(
                $request->input('q'),
                $request->input('type'),
                (int) $request->input('limit', 12)
            ),
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'stage' => 'stage-03',
            'data' => $this->service->detail($product),
        ]);
    }
}
