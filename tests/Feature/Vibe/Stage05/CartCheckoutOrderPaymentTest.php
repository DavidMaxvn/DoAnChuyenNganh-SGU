<?php

namespace Tests\Feature\Vibe\Stage05;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Concerns\CreatesStage05Schema;
use Tests\TestCase;

class CartCheckoutOrderPaymentTest extends TestCase
{
    use CreatesStage05Schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createStage05Schema();
    }

    public function test_guest_cannot_access_cart_checkout_and_order_pages(): void
    {
        $this->get(route('web.list.product.cart'))
            ->assertRedirect(route('web.login'));

        $this->get(route('web.checkout.order', [
            'list_product' => [],
        ]))->assertRedirect(route('web.login'));

        $this->post(route('web.create.order'), [])
            ->assertRedirect(route('web.login'));

        $this->get(route('web.list_order_of_user'))
            ->assertRedirect(route('web.login'));

        $this->get(route('web.order_detail', 1))
            ->assertRedirect(route('web.login'));
    }

    public function test_add_cart_updates_quantity_total_and_tracks_activity(): void
    {
        $user = $this->createStage05User();
        $product = $this->createStage05Product([
            'name' => 'Sua chua uong',
            'price' => 12000,
            'quantity' => 5,
        ]);

        $response = $this->actingAs($user, 'web')->getJson(route('web.cart.add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.qty', 2)
            ->assertJsonPath('data.total', 24000)
            ->assertJsonPath('data.total_row', 24000);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user, 'web')->getJson(route('web.cart.add', [
            'product_id' => $product->id,
            'quantity' => 4,
        ]))->assertOk()->assertJsonPath('success', false);

        $this->actingAs($user, 'web')->getJson(route('web.delete.product.cart', [
            'product_id' => $product->id,
        ]))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.qty', 0)
            ->assertJsonPath('data.total', 0);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertSame(1, DB::table('activity_logs')->where('activity_type', 'add_to_cart')->count());
        $this->assertSame(1, DB::table('activity_logs')->where('activity_type', 'remove_from_cart')->count());
    }

    public function test_checkout_view_renders_cart_items_shipping_and_coupon_data(): void
    {
        $user = $this->createStage05User();
        $product = $this->createStage05Product([
            'name' => 'Xoai cat',
            'price' => 45000,
            'quantity' => 10,
        ]);

        $city = $this->createStage05City([
            'name' => 'Can Tho',
            'shipping_fee' => 18000,
        ]);

        $coupon = $this->createStage05Coupon([
            'name' => 'SALE10K',
            'discount' => 10000,
            'discount_max' => 10000,
            'number_use' => 5,
        ]);

        $this->actingAs($user, 'web');

        $response = $this->get(route('web.checkout.order', [
            'list_product' => [
                $product->id => [
                    'id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]));

        $response
            ->assertOk()
            ->assertSee('Xoai cat')
            ->assertSee('Phí vận chuyển')
            ->assertSee('SALE10K')
            ->assertSee($city->name)
            ->assertSee((string) ($product->price * 2));

        $this->assertDatabaseHas('coupons', [
            'id' => $coupon->id,
            'name' => 'SALE10K',
        ]);
    }

    public function test_cod_order_creation_persists_rows_clears_cart_and_sends_mail(): void
    {
        $user = $this->createStage05User();
        $product = $this->createStage05Product([
            'name' => 'Hat sen',
            'price' => 60000,
            'quantity' => 12,
        ]);
        $city = $this->createStage05City([
            'name' => 'Da Nang',
            'shipping_fee' => 20000,
        ]);
        $coupon = $this->createStage05Coupon([
            'name' => 'COD5000',
            'discount' => 5000,
            'discount_max' => 5000,
            'number_use' => 3,
        ]);

        $this->actingAs($user, 'web')->getJson(route('web.cart.add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]))->assertOk();

        $response = $this->actingAs($user, 'web')->post(route('web.create.order'), [
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'phone' => $user->phone,
            'note' => 'Giao buoi sang',
            'city_id' => $city->id,
            'coupon_id' => $coupon->id,
            'payment_type' => 'COD',
            'list_product' => [
                $product->id => [
                    'id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $order = Order::query()->where('user_id', $user->id)->latest('id')->firstOrFail();

        $response->assertRedirect(route('web.order_detail', $order->id));

        $this->assertSame(1, Order::query()->where('user_id', $user->id)->count());
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'phone' => $user->phone,
            'payment_type' => 'COD',
            'payment_status' => 'UNPAID',
            'coupon_id' => $coupon->id,
            'city_id' => $city->id,
            'shipping_fee' => $city->shipping_fee,
            'discount' => 5000,
        ]);

        $this->assertDatabaseHas('order_products', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);

        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertSame(1, DB::table('activity_logs')->where('activity_type', 'purchase')->count());
    }

    public function test_online_payment_redirects_to_vnpay_and_callback_marks_order_paid(): void
    {
        $user = $this->createStage05User([
            'email' => 'vnpay-user@example.com',
        ]);
        $product = $this->createStage05Product([
            'name' => 'Sua hat',
            'price' => 30000,
            'quantity' => 20,
        ]);
        $city = $this->createStage05City([
            'name' => 'Hai Phong',
            'shipping_fee' => 15000,
        ]);

        $this->actingAs($user, 'web')->getJson(route('web.cart.add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]))->assertOk();

        $response = $this->actingAs($user, 'web')->post(route('web.create.order'), [
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'phone' => $user->phone,
            'note' => null,
            'city_id' => $city->id,
            'payment_type' => 'ONLINE',
            'list_product' => [
                $product->id => [
                    'id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        $order = Order::query()->where('user_id', $user->id)->latest('id')->firstOrFail();

        $response->assertRedirect(route('web.vnpay.create', ['order_id' => $order->id]));

        $createResponse = $this->actingAs($user, 'web')->get(route('web.vnpay.create', [
            'order_id' => $order->id,
        ]));

        $createResponse->assertRedirect();
        $this->assertStringContainsString('sandbox.vnpayment.vn', $createResponse->headers->get('Location'));

        $vnpParams = [
            'vnp_Amount' => (int) ($order->total() * 100),
            'vnp_BankCode' => 'NCB',
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => '20260417010101',
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => '127.0.0.1',
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toan don hang',
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => url('/vnpay/return'),
            'vnp_ResponseCode' => '00',
            'vnp_TmnCode' => '9B356UF8',
            'vnp_TxnRef' => (string) $order->id,
            'vnp_Version' => '2.1.0',
        ];
        $vnpParams['vnp_SecureHashType'] = 'HmacSHA512';
        $vnpParams['vnp_SecureHash'] = $this->buildVnpayHash($vnpParams);

        $returnResponse = $this->get(route('web.vnpay.return', $vnpParams));

        $returnResponse->assertRedirect(route('web.success.order'));

        $order->refresh();

        $this->assertSame('PAID', $order->payment_status);
        $this->assertNotNull($order->success_at);
        $this->assertNotEmpty($order->payment_response);
    }

    public function test_momo_return_marks_order_paid_without_duplicate_order_data(): void
    {
        $user = $this->createStage05User([
            'email' => 'momo-user@example.com',
        ]);
        $product = $this->createStage05Product([
            'name' => 'Nuoc ep cam',
            'price' => 28000,
            'quantity' => 15,
        ]);

        $order = $this->createStage05Order($user, [
            'payment_type' => 'MOMO',
            'payment_status' => 'UNPAID',
            'shipping_fee' => 0,
        ], [
            [
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ],
        ]);

        $response = $this->actingAs($user, 'web')->get(route('web.momo_return', [
            'orderId' => $order->id . '_repay123',
            'payType' => 'napas',
            'message' => 'success',
        ]));

        $response->assertRedirect(route('web.order_detail', $order->id));

        $order->refresh();

        $this->assertSame('PAID', $order->payment_status);
        $this->assertNotEmpty($order->payment_response);
    }

    public function test_orders_are_scoped_to_the_signed_in_user(): void
    {
        $userA = $this->createStage05User([
            'email' => 'user-a@example.com',
        ]);
        $userB = $this->createStage05User([
            'email' => 'user-b@example.com',
        ]);
        $product = $this->createStage05Product([
            'name' => 'Trai cay tong hop',
            'price' => 70000,
            'quantity' => 10,
        ]);

        $orderA = $this->createStage05Order($userA, [
            'shipping_fee' => 12000,
        ], [[
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]]);
        $orderB = $this->createStage05Order($userB, [
            'shipping_fee' => 15000,
        ], [[
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]]);

        $response = $this->actingAs($userA, 'web')->get(route('web.list_order_of_user'));

        $response
            ->assertOk()
            ->assertSee((string) $orderA->id)
            ->assertDontSee((string) $orderB->id);

        $this->actingAs($userA, 'web')
            ->get(route('web.order_detail', $orderB->id))
            ->assertNotFound();
    }

    private function buildVnpayHash(array $params): string
    {
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);
        ksort($params);

        $hashPieces = [];
        foreach ($params as $key => $value) {
            $hashPieces[] = urlencode($key) . '=' . urlencode($value);
        }

        return hash_hmac('sha512', implode('&', $hashPieces), '9LNWW09XJSAT2H6H89DA71Q0FD6O92NS');
    }
}