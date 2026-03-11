<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeliveryDistanceService
{
    public function __construct(private readonly AppSettingService $appSettingService)
    {
    }

    public function calculate(mixed $customerLat, mixed $customerLng): array
    {
        $shop = $this->shopCoordinates();
        $normalizedCustomerLat = $this->normalizeCoordinate($customerLat, -90, 90);
        $normalizedCustomerLng = $this->normalizeCoordinate($customerLng, -180, 180);

        if (!$shop || $normalizedCustomerLat === null || $normalizedCustomerLng === null) {
            return [
                'shop_configured' => $shop !== null,
                'has_customer_coordinates' => $normalizedCustomerLat !== null && $normalizedCustomerLng !== null,
                'source' => 'none',
                'straight_line_distance_km' => null,
                'driving_distance_km' => null,
                'distance_km' => null,
                'billable_distance_km' => 0,
                'fee' => 0,
                'free_radius_km' => $this->freeRadiusKm(),
                'rate_per_km' => $this->ratePerKm(),
            ];
        }

        $straightLineDistanceKm = round(
            $this->calculateHaversineDistance(
                $normalizedCustomerLat,
                $normalizedCustomerLng,
                $shop['latitude'],
                $shop['longitude']
            ),
            2
        );

        $drivingDistanceKm = $this->fetchDrivingDistanceKm(
            $shop['latitude'],
            $shop['longitude'],
            $normalizedCustomerLat,
            $normalizedCustomerLng
        );

        $source = 'osrm';

        if ($drivingDistanceKm === null) {
            $drivingDistanceKm = $straightLineDistanceKm;
            $source = 'haversine';
        }

        $freeRadiusKm = $this->freeRadiusKm();
        $billableDistanceKm = $drivingDistanceKm > $freeRadiusKm ? (float) ceil($drivingDistanceKm - $freeRadiusKm) : 0.0;
        $fee = $billableDistanceKm * $this->ratePerKm();

        return [
            'shop_configured' => true,
            'has_customer_coordinates' => true,
            'source' => $source,
            'straight_line_distance_km' => $straightLineDistanceKm,
            'driving_distance_km' => round($drivingDistanceKm, 2),
            'distance_km' => round($drivingDistanceKm, 2),
            'billable_distance_km' => $billableDistanceKm,
            'fee' => (float) $fee,
            'free_radius_km' => $freeRadiusKm,
            'rate_per_km' => $this->ratePerKm(),
        ];
    }

    public function shopCoordinates(): ?array
    {
        $latitude = $this->normalizeCoordinate(
            $this->appSettingService->get('shop.latitude', config('washly.shop.latitude')),
            -90,
            90
        );
        $longitude = $this->normalizeCoordinate(
            $this->appSettingService->get('shop.longitude', config('washly.shop.longitude')),
            -180,
            180
        );

        if ($latitude === null || $longitude === null) {
            return null;
        }

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

    public function makeMapLink(mixed $latitude, mixed $longitude): ?string
    {
        $normalizedLatitude = $this->normalizeCoordinate($latitude, -90, 90);
        $normalizedLongitude = $this->normalizeCoordinate($longitude, -180, 180);

        if ($normalizedLatitude === null || $normalizedLongitude === null) {
            return null;
        }

        return sprintf('https://maps.google.com/maps?q=%s,%s', $normalizedLatitude, $normalizedLongitude);
    }

    public function normalizeCoordinate(mixed $value, float $min, float $max): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_numeric($value)) {
            return null;
        }

        $numericValue = (float) $value;

        if ($numericValue < $min || $numericValue > $max) {
            return null;
        }

        return $numericValue;
    }

    private function fetchDrivingDistanceKm(float $shopLat, float $shopLng, float $customerLat, float $customerLng): ?float
    {
        $baseUrl = rtrim((string) config('washly.delivery.osrm_base_url'), '/');

        if ($baseUrl === '') {
            return null;
        }

        $url = sprintf(
            '%s/route/v1/driving/%s,%s;%s,%s',
            $baseUrl,
            $shopLng,
            $shopLat,
            $customerLng,
            $customerLat
        );

        try {
            $response = Http::timeout(max(1, (int) config('washly.delivery.timeout_seconds', 5)))
                ->acceptJson()
                ->get($url, [
                    'overview' => 'false',
                    'alternatives' => 'false',
                    'steps' => 'false',
                ]);

            if (!$response->successful()) {
                return null;
            }

            $distanceMeters = data_get($response->json(), 'routes.0.distance');

            if (!is_numeric($distanceMeters)) {
                return null;
            }

            return (float) $distanceMeters / 1000;
        } catch (\Throwable) {
            return null;
        }
    }

    private function freeRadiusKm(): float
    {
        return max(0, (float) $this->appSettingService->get('delivery.free_radius_km', config('washly.delivery.free_radius_km', 1.5)));
    }

    private function ratePerKm(): float
    {
        return max(0, (float) $this->appSettingService->get('delivery.rate_per_km', config('washly.delivery.rate_per_km', 5)));
    }

    private function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat1 - $lat2);
        $lngDelta = deg2rad($lon1 - $lon2);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat2)) * cos(deg2rad($lat1)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}