<?php

namespace App\Services;

use App\Models\AppSetting;

class AppSettingService
{
    private ?array $settingsCache = null;

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    public function all(): array
    {
        if ($this->settingsCache !== null) {
            return $this->settingsCache;
        }

        if (!$this->settingsTableExists()) {
            return $this->settingsCache = [];
        }

        return $this->settingsCache = AppSetting::query()
            ->pluck('value', 'key')
            ->all();
    }

    public function getDeliverySettings(): array
    {
        return [
            'free_radius_km' => (float) $this->get('delivery.free_radius_km', config('washly.delivery.free_radius_km', 1.5)),
            'rate_per_km' => (float) $this->get('delivery.rate_per_km', config('washly.delivery.rate_per_km', 5)),
            'shop_latitude' => $this->get('shop.latitude', config('washly.shop.latitude')),
            'shop_longitude' => $this->get('shop.longitude', config('washly.shop.longitude')),
        ];
    }

    public function updateDeliverySettings(float $freeRadiusKm, float $ratePerKm, ?float $shopLatitude = null, ?float $shopLongitude = null): void
    {
        AppSetting::updateOrCreate(
            ['key' => 'delivery.free_radius_km'],
            ['value' => (string) $freeRadiusKm]
        );

        AppSetting::updateOrCreate(
            ['key' => 'delivery.rate_per_km'],
            ['value' => (string) $ratePerKm]
        );

        AppSetting::updateOrCreate(
            ['key' => 'shop.latitude'],
            ['value' => $shopLatitude === null ? null : (string) $shopLatitude]
        );

        AppSetting::updateOrCreate(
            ['key' => 'shop.longitude'],
            ['value' => $shopLongitude === null ? null : (string) $shopLongitude]
        );

        $this->settingsCache = null;
    }

    private function settingsTableExists(): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable('app_settings');
        } catch (\Throwable) {
            return false;
        }
    }
}