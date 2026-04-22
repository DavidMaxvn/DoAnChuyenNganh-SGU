<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreatesStage07Schema
{
    use CreatesStage06Schema;

    protected function createStage07Schema(): void
    {
        $this->createStage06Schema();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('microservice_outbox_events');
        Schema::dropIfExists('inventory_reservations');
        Schema::enableForeignKeyConstraints();

        Schema::create('inventory_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_group')->index();
            $table->string('reservation_code')->unique();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->integer('quantity');
            $table->string('status')->default('RESERVED')->index();
            $table->string('correlation_id')->nullable()->index();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('committed_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });

        Schema::create('microservice_outbox_events', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->index();
            $table->string('event_type')->index();
            $table->string('aggregate_type')->nullable();
            $table->string('aggregate_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('status')->default('PENDING')->index();
            $table->string('correlation_id')->nullable()->index();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamps();
        });
    }
}
