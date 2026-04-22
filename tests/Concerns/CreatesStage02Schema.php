<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreatesStage02Schema
{
    use CreatesStage01Schema;

    protected function createStage02Schema(): void
    {
        $this->createStage01Schema();

        Schema::table('products', function (Blueprint $table) {
            $table->text('image')->nullable();
            $table->string('type')->default('simple');
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->timestamps();
        });

        Schema::create('values', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->string('text_value')->nullable();
            $table->primary(['product_id', 'attribute_id']);
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('attribute_id')->references('id')->on('attributes');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->text('image');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });

        Schema::create('product_attr_config', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->boolean('is_private')->default(false);
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('attribute_id')->references('id')->on('attributes');
        });
    }
}
