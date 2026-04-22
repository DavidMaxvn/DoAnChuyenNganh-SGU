<?php

namespace Tests\Concerns;

use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

trait CreatesStage05Schema
{
    use CreatesStage01Schema;

    protected function createStage05Schema(): void
    {
        $this->createStage01Schema();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('city');
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();

        Schema::table('products', function (Blueprint $table) {
            $table->text('image')->nullable();
            $table->string('type')->default('simple');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->double('shipping_fee')->default(0);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('discount')->nullable();
            $table->string('type')->default('price');
            $table->double('discount_max')->nullable();
            $table->integer('number_use')->nullable();
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_type')->default('COD');
            $table->string('status')->default('PENDING');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('ship_code')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->double('discount')->nullable();
            $table->string('payment_status')->default('UNPAID');
            $table->text('payment_response')->nullable();
            $table->date('success_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('city');
            $table->double('shipping_fee')->default(0);
            $table->timestamps();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->integer('quantity');
            $table->double('price');
            $table->primary(['product_id', 'order_id']);
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['product_id', 'user_id']);
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('activity_type');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('search_query')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    protected function createStage05User(array $overrides = []): User
    {
        return User::query()->create(array_merge([
            'name' => 'Stage 05 User',
            'email' => 'stage05-user@example.com',
            'password' => Hash::make('secret123'),
            'phone' => '0900000000',
            'address' => 'Ho Chi Minh City',
            'status' => true,
        ], $overrides));
    }

    protected function createStage05Product(array $overrides = []): Product
    {
        $product = new Product();
        $product->setAttribute('name', $overrides['name'] ?? 'San pham Stage 05');
        $product->setAttribute('price', $overrides['price'] ?? 50000);
        $product->setAttribute('quantity', $overrides['quantity'] ?? 20);
        $product->setAttribute('image', $overrides['image'] ?? null);
        $product->setAttribute('parent_id', $overrides['parent_id'] ?? null);
        $product->setAttribute('type', $overrides['type'] ?? 'simple');
        $product->save();

        return $product->fresh();
    }

    protected function createStage05City(array $overrides = []): City
    {
        return City::query()->create(array_merge([
            'code' => 'HCM',
            'name' => 'Ho Chi Minh',
            'shipping_fee' => 15000,
        ], $overrides));
    }

    protected function createStage05Coupon(array $overrides = []): Coupon
    {
        return Coupon::query()->create(array_merge([
            'name' => 'SALE5000',
            'discount' => 5000,
            'type' => 'price',
            'discount_max' => 5000,
            'number_use' => 10,
            'start' => null,
            'end' => null,
        ], $overrides));
    }

    protected function createStage05Order(User $user, array $overrides = [], array $products = []): Order
    {
        $orderId = $overrides['id'] ?? (int) (time() * 10 + rand(1, 9));

        Order::query()->insert([
            'id' => $orderId,
            'note' => $overrides['note'] ?? null,
            'user_id' => $user->id,
            'address' => $overrides['address'] ?? $user->address,
            'phone' => $overrides['phone'] ?? $user->phone,
            'payment_type' => $overrides['payment_type'] ?? 'COD',
            'status' => $overrides['status'] ?? 'PENDING',
            'name' => $overrides['name'] ?? $user->name,
            'email' => $overrides['email'] ?? $user->email,
            'ship_code' => $overrides['ship_code'] ?? null,
            'coupon_id' => $overrides['coupon_id'] ?? null,
            'discount' => $overrides['discount'] ?? 0,
            'payment_status' => $overrides['payment_status'] ?? 'UNPAID',
            'payment_response' => $overrides['payment_response'] ?? null,
            'success_at' => $overrides['success_at'] ?? null,
            'admin_note' => $overrides['admin_note'] ?? null,
            'city_id' => $overrides['city_id'] ?? null,
            'shipping_fee' => $overrides['shipping_fee'] ?? 0,
            'created_at' => $overrides['created_at'] ?? now(),
            'updated_at' => $overrides['updated_at'] ?? now(),
        ]);

        foreach ($products as $item) {
            \DB::table('order_products')->insert([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return Order::query()->findOrFail($orderId);
    }
}