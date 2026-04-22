<?php

namespace App\Services\Vibe\Stage07;

use App\Models\City;
use App\Models\Coupon;
use App\Models\Product;

class PricingMicroservice
{
    public function __construct(protected MicroserviceOutboxService $outbox)
    {
    }

    public function quote(array $items, ?int $cityId = null, ?int $couponId = null, ?string $correlationId = null): array
    {
        $products = Product::query()
            ->whereIn('id', collect($items)->pluck('product_id')->all())
            ->get()
            ->keyBy('id');

        $lines = collect($items)->map(function (array $item) use ($products) {
            /** @var Product $product */
            $product = $products->get($item['product_id']);
            $quantity = (int) $item['quantity'];
            $unitPrice = (float) $product->price;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $unitPrice * $quantity,
            ];
        })->values();

        $subtotal = (float) $lines->sum('line_total');
        $city = $cityId ? City::query()->find($cityId) : null;
        $coupon = $couponId ? Coupon::query()->find($couponId) : null;
        $shippingFee = (float) ($city?->shipping_fee ?? 0);
        $discount = $this->calculateDiscount($coupon, $subtotal);
        $grandTotal = max(0, $subtotal + $shippingFee - $discount);

        $quote = [
            'correlation_id' => $correlationId,
            'currency' => 'VND',
            'items' => $lines->all(),
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'city' => $city ? [
                'id' => $city->id,
                'name' => $city->name,
            ] : null,
            'coupon' => $coupon ? [
                'id' => $coupon->id,
                'name' => $coupon->name,
                'type' => $coupon->type,
            ] : null,
        ];

        $this->outbox->publish(
            'pricing',
            'pricing.quoted',
            $quote,
            $correlationId,
            'quote',
            $correlationId
        );

        return $quote;
    }

    protected function calculateDiscount(?Coupon $coupon, float $subtotal): float
    {
        if ($coupon === null) {
            return 0;
        }

        if (! empty($coupon->number_use) && (int) $coupon->number_use <= 0) {
            return 0;
        }

        $discount = $coupon->type === 'percent'
            ? ((float) $coupon->discount * $subtotal / 100)
            : (float) $coupon->discount;

        if (! empty($coupon->discount_max)) {
            $discount = min($discount, (float) $coupon->discount_max);
        }

        return min($discount, $subtotal);
    }
}
