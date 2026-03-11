<?php

return [
    'shop' => [
        'latitude' => env('SHOP_LATITUDE'),
        'longitude' => env('SHOP_LONGITUDE'),
    ],

    'delivery' => [
        'free_radius_km' => (float) env('DELIVERY_FREE_RADIUS_KM', 1.5),
        'rate_per_km' => (float) env('DELIVERY_RATE_PER_KM', 5),
        'timeout_seconds' => (int) env('DELIVERY_ROUTE_TIMEOUT_SECONDS', 5),
        'osrm_base_url' => env('DELIVERY_OSRM_BASE_URL', 'https://router.project-osrm.org'),
    ],
];