<?php

namespace Tests\Unit;

use App\Models\AppSetting;
use App\Services\DeliveryDistanceService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DeliveryDistanceServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        Schema::disableForeignKeyConstraints();
        AppSetting::query()->delete();
        Schema::enableForeignKeyConstraints();
    }

    public function test_it_calculates_driving_distance_and_fee_from_shop_config(): void
    {
        Config::set('washly.shop.latitude', '14.0366133');
        Config::set('washly.shop.longitude', '100.6558354');
        Config::set('washly.delivery.free_radius_km', 1.5);
        Config::set('washly.delivery.rate_per_km', 5);

        Http::fake([
            '*' => Http::response([
                'routes' => [
                    ['distance' => 4200],
                ],
            ]),
        ]);

        $service = app(DeliveryDistanceService::class);
        $quote = $service->calculate(14.05, 100.66);

        $this->assertTrue($quote['shop_configured']);
        $this->assertTrue($quote['has_customer_coordinates']);
        $this->assertSame('osrm', $quote['source']);
        $this->assertSame(4.2, $quote['driving_distance_km']);
        $this->assertSame(3.0, $quote['billable_distance_km']);
        $this->assertSame(15.0, $quote['fee']);
    }

    public function test_it_falls_back_to_straight_line_distance_when_route_api_fails(): void
    {
        Config::set('washly.shop.latitude', '14.0366133');
        Config::set('washly.shop.longitude', '100.6558354');

        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $service = app(DeliveryDistanceService::class);
        $quote = $service->calculate(14.05, 100.66);

        $this->assertSame('haversine', $quote['source']);
        $this->assertNotNull($quote['straight_line_distance_km']);
        $this->assertSame($quote['straight_line_distance_km'], $quote['driving_distance_km']);
    }

    public function test_it_prefers_shop_coordinates_from_settings_over_env_config(): void
    {
        Config::set('washly.shop.latitude', '0');
        Config::set('washly.shop.longitude', '0');

        $settingService = app(\App\Services\AppSettingService::class);
        $settingService->updateDeliverySettings(1.5, 5, 14.0366133, 100.6558354);

        Http::fake([
            '*' => Http::response([
                'routes' => [
                    ['distance' => 2500],
                ],
            ]),
        ]);

        $service = app(DeliveryDistanceService::class);
        $shop = $service->shopCoordinates();

        $this->assertSame(14.0366133, $shop['latitude']);
        $this->assertSame(100.6558354, $shop['longitude']);
    }
}