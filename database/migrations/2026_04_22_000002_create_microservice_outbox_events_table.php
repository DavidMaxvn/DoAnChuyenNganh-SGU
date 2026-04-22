<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

    public function down()
    {
        Schema::dropIfExists('microservice_outbox_events');
    }
};
