<?php

namespace App\Http\Controllers\Vibe\Stage02\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage02\Admin\AttributeStoreRequest;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    public function index(): JsonResponse
    {
        $attributes = Attribute::query()
            ->select(['id', 'name', 'created_at'])
            ->orderBy('name')
            ->get()
            ->map(fn (Attribute $attribute) => [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'created_at' => optional($attribute->created_at)->toDateTimeString(),
            ]);

        return response()->json([
            'stage' => 'stage-02',
            'message' => 'Danh sach thuoc tinh san pham.',
            'count' => $attributes->count(),
            'data' => $attributes,
        ]);
    }

    public function store(AttributeStoreRequest $request): JsonResponse
    {
        $attribute = Attribute::query()->create([
            'name' => trim((string) $request->input('name')),
        ]);

        return response()->json([
            'message' => 'Tao thuoc tinh thanh cong.',
            'data' => [
                'id' => $attribute->id,
                'name' => $attribute->name,
            ],
        ], 201);
    }
}
