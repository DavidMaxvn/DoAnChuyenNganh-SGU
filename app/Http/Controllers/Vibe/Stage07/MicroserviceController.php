<?php

namespace App\Http\Controllers\Vibe\Stage07;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vibe\Stage07\CheckoutSimulationRequest;
use App\Http\Requests\Vibe\Stage07\InventoryCheckRequest;
use App\Http\Requests\Vibe\Stage07\PricingQuoteRequest;
use App\Services\Vibe\Stage07\CheckoutOrchestratorService;
use App\Services\Vibe\Stage07\InventoryMicroservice;
use App\Services\Vibe\Stage07\MicroserviceOutboxService;
use App\Services\Vibe\Stage07\PricingMicroservice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MicroserviceController extends Controller
{
    public function overview(): JsonResponse
    {
        return response()->json([
            'message' => 'Stage 07 them lop microservices thuc tien cho checkout.',
            'data' => [
                'stage' => 'Stage 07',
                'boundaries' => [
                    'inventory' => 'Kiem tra va reserve ton kho truoc khi tao order.',
                    'pricing' => 'Tinh quote gom item total, shipping fee, coupon va grand total.',
                    'checkout_orchestrator' => 'Dieu phoi pricing, inventory va outbox event bang correlation_id.',
                    'outbox' => 'Luu event de sau nay tach service hoac dispatch async.',
                ],
                'endpoints' => [
                    'POST /inventory/check',
                    'POST /pricing/quote',
                    'POST /checkout/simulate',
                    'GET /outbox',
                ],
            ],
        ]);
    }

    public function inventoryCheck(InventoryCheckRequest $request, InventoryMicroservice $inventory): JsonResponse
    {
        return response()->json([
            'message' => 'Inventory service da kiem tra ton kho.',
            'data' => $inventory->check(
                $request->validated('items'),
                $request->validated('correlation_id')
            ),
        ]);
    }

    public function pricingQuote(PricingQuoteRequest $request, PricingMicroservice $pricing): JsonResponse
    {
        return response()->json([
            'message' => 'Pricing service da tinh quote.',
            'data' => $pricing->quote(
                $request->validated('items'),
                $request->validated('city_id'),
                $request->validated('coupon_id'),
                $request->validated('correlation_id')
            ),
        ]);
    }

    public function checkoutSimulation(
        CheckoutSimulationRequest $request,
        CheckoutOrchestratorService $orchestrator
    ): JsonResponse {
        return response()->json([
            'message' => 'Checkout orchestrator da goi pricing, inventory va outbox.',
            'data' => $orchestrator->simulate($request->validated()),
        ]);
    }

    public function outbox(Request $request, MicroserviceOutboxService $outbox): JsonResponse
    {
        return response()->json([
            'message' => 'Danh sach outbox events moi nhat.',
            'data' => [
                'items' => $outbox->latest((int) $request->query('limit', 20)),
            ],
        ]);
    }
}
