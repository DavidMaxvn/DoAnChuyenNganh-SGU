<?php

namespace Tests\Concerns;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

trait CreatesStage06Schema
{
    use CreatesStage05Schema;

    protected function createStage06Schema(): void
    {
        $this->createStage05Schema();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('ai_suggestions');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cong_thuc');
        Schema::enableForeignKeyConstraints();

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->text('image')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->text('image');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });

        Schema::create('ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('suggestion_type');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('action_recommendation')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_dismissed')->default(false);
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cong_thuc', function (Blueprint $table) {
            $table->id();
            $table->string('tenmonan');
            $table->text('congthuc');
            $table->timestamps();
        });
    }

    protected function createStage06Admin(array $overrides = []): Admin
    {
        return Admin::query()->create(array_merge([
            'name' => 'Stage 06 Admin',
            'email' => 'stage06-admin@example.com',
            'password' => Hash::make('secret123'),
        ], $overrides));
    }

    protected function createStage06Product(array $overrides = []): Product
    {
        $product = $this->createStage05Product(array_merge([
            'name' => 'San pham Stage 06',
            'price' => 100000,
            'quantity' => 20,
        ], $overrides));

        $product->status = $overrides['status'] ?? 1;
        $product->category_id = $overrides['category_id'] ?? null;
        $product->save();

        return $product->fresh();
    }
}