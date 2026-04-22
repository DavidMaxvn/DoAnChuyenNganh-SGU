<?php

namespace App\Services\Vibe\Stage07;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InventoryMicroservice
{
    public function __construct(protected MicroserviceOutboxService $outbox)
    {
    }

    public function check(array $items, ?string $correlationId = null): array
    {
        $productIds = collect($items)->pluck('product_id')->all();
        $products = Product::query()->whereIn('id', $productIds)->get()->keyBy('id');

        $lines = collect($items)->map(function (array $item) use ($products) {
            /** @var Product|null $product */
            $product = $products->get($item['product_id']);
            $requested = (int) $item['quantity'];
            $reserved = $this->reservedQuantity((int) $item['product_id']);
            $activeStock = $product ? (int) $product->getQuantityActive() : 0;
            $available = max(0, $activeStock - $reserved);

            return [
                'product_id' => (int) $item['product_id'],
                'product_name' => $product?->name,
                'requested' => $requested,
                'active_stock' => $activeStock,
                'reserved' => $reserved,
                'available' => $available,
                'is_available' => $product !== null && $requested <= $available,
            ];
        })->values();

        return [
            'correlation_id' => $correlationId,
            'is_available' => $lines->every(fn (array $line) => $line['is_available']),
            'items' => $lines->all(),
        ];
    }

    public function reserve(array $items, ?int $userId = null, ?string $correlationId = null): array
    {
        return DB::transaction(function () use ($items, $userId, $correlationId) {
            $check = $this->check($items, $correlationId);

            if (! $check['is_available']) {
                throw ValidationException::withMessages([
                    'inventory' => ['Ton kho khong du de tao reservation.'],
                ]);
            }

            $reservationGroup = (string) Str::uuid();
            $reservationItems = [];

            foreach ($items as $item) {
                $reservationCode = (string) Str::uuid();

                DB::table('inventory_reservations')->insert([
                    'reservation_group' => $reservationGroup,
                    'reservation_code' => $reservationCode,
                    'product_id' => $item['product_id'],
                    'user_id' => $userId,
                    'quantity' => $item['quantity'],
                    'status' => 'RESERVED',
                    'correlation_id' => $correlationId,
                    'expires_at' => now()->addMinutes(15),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $reservationItems[] = [
                    'reservation_code' => $reservationCode,
                    'product_id' => (int) $item['product_id'],
                    'quantity' => (int) $item['quantity'],
                ];
            }

            $payload = [
                'reservation_group' => $reservationGroup,
                'user_id' => $userId,
                'items' => $reservationItems,
            ];

            $this->outbox->publish(
                'inventory',
                'inventory.reserved',
                $payload,
                $correlationId,
                'inventory_reservation',
                $reservationGroup
            );

            return [
                'correlation_id' => $correlationId,
                'reservation_group' => $reservationGroup,
                'expires_in_minutes' => 15,
                'items' => $reservationItems,
            ];
        });
    }

    protected function reservedQuantity(int $productId): int
    {
        return (int) DB::table('inventory_reservations')
            ->where('product_id', $productId)
            ->where('status', 'RESERVED')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->sum('quantity');
    }
}
