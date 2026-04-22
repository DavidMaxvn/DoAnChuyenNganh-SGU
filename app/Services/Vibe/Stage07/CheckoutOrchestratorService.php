<?php

namespace App\Services\Vibe\Stage07;

use Illuminate\Support\Str;

class CheckoutOrchestratorService
{
    public function __construct(
        protected PricingMicroservice $pricing,
        protected InventoryMicroservice $inventory,
        protected MicroserviceOutboxService $outbox
    ) {
    }

    public function simulate(array $payload): array
    {
        $correlationId = $payload['correlation_id'] ?? (string) Str::uuid();

        $quote = $this->pricing->quote(
            $payload['items'],
            $payload['city_id'] ?? null,
            $payload['coupon_id'] ?? null,
            $correlationId
        );

        $reservation = $this->inventory->reserve(
            $payload['items'],
            $payload['user_id'] ?? null,
            $correlationId
        );

        $result = [
            'correlation_id' => $correlationId,
            'services_called' => [
                'pricing',
                'inventory',
                'outbox',
            ],
            'quote' => $quote,
            'reservation' => $reservation,
            'next_step' => 'create_order_or_publish_order_requested_event',
        ];

        $this->outbox->publish(
            'checkout-orchestrator',
            'checkout.simulated',
            $result,
            $correlationId,
            'checkout',
            $correlationId
        );

        return $result;
    }
}
