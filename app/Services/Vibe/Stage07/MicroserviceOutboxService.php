<?php

namespace App\Services\Vibe\Stage07;

use Illuminate\Support\Facades\DB;

class MicroserviceOutboxService
{
    public function publish(
        string $serviceName,
        string $eventType,
        array $payload,
        ?string $correlationId = null,
        ?string $aggregateType = null,
        ?string $aggregateId = null
    ): int {
        return DB::table('microservice_outbox_events')->insertGetId([
            'service_name' => $serviceName,
            'event_type' => $eventType,
            'aggregate_type' => $aggregateType,
            'aggregate_id' => $aggregateId,
            'payload' => json_encode($payload),
            'status' => 'PENDING',
            'correlation_id' => $correlationId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function latest(int $limit = 20): array
    {
        return DB::table('microservice_outbox_events')
            ->orderByDesc('id')
            ->limit(max(1, $limit))
            ->get()
            ->map(function ($event) {
                $event->payload = $event->payload ? json_decode($event->payload, true) : null;

                return (array) $event;
            })
            ->values()
            ->all();
    }
}
