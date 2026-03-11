<?php

namespace App\Services;

use App\Models\Order;

class OrderDistanceBackfillService
{
    public function __construct(private readonly DeliveryDistanceService $deliveryDistanceService)
    {
    }

    public function backfillMissingDistances(): array
    {
        $updated = 0;
        $skipped = 0;

        Order::query()
            ->whereNull('distance')
            ->whereNotNull('pickup_latitude')
            ->whereNotNull('pickup_longitude')
            ->orderBy('id')
            ->chunkById(100, function ($orders) use (&$updated, &$skipped) {
                foreach ($orders as $order) {
                    $latitude = $this->deliveryDistanceService->normalizeCoordinate($order->pickup_latitude, -90, 90);
                    $longitude = $this->deliveryDistanceService->normalizeCoordinate($order->pickup_longitude, -180, 180);

                    if ($latitude === null || $longitude === null) {
                        $skipped++;
                        continue;
                    }

                    $quote = $this->deliveryDistanceService->calculate($latitude, $longitude);

                    if (!$quote['shop_configured'] || !$quote['has_customer_coordinates'] || $quote['driving_distance_km'] === null) {
                        $skipped++;
                        continue;
                    }

                    $payload = [
                        'distance' => $quote['driving_distance_km'],
                    ];

                    if (empty($order->pickup_map_link)) {
                        $payload['pickup_map_link'] = $this->deliveryDistanceService->makeMapLink($latitude, $longitude);
                    }

                    Order::query()
                        ->whereKey($order->id)
                        ->update($payload);

                    $updated++;
                }
            });

        $remaining = Order::query()
            ->whereNull('distance')
            ->whereNotNull('pickup_latitude')
            ->whereNotNull('pickup_longitude')
            ->count();

        return [
            'updated' => $updated,
            'skipped' => $skipped,
            'remaining' => $remaining,
        ];
    }
}