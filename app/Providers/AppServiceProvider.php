<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Line\Provider as LineProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use App\Models\Package;
use App\Models\AddonOption;
use Carbon\Carbon; // 1. เพิ่ม Carbon สำหรับเช็กเวลา

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (request()->header('x-forwarded-proto') === 'https' || request()->isSecure()) {
            URL::forceScheme('https');
        }
        
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('line', LineProvider::class);
        });

        // 2. ส่งตัวแปร $isClosedToday เพิ่มเข้าไปด้วย
        View::composer('layouts.customer', function ($view) {
            
            // เช็กเวลาว่าเกิน 17:00 หรือยัง (ตามเวลาประเทศไทย)
            $now = Carbon::now()->timezone('Asia/Bangkok');
            $isClosedToday = $now->format('H:i') >= '17:00';

            $view->with([
                'packages' => Package::where('is_active', true)->get(),
                'detergentAddons' => AddonOption::where('category', 'detergent')->where('is_active', true)->get(),
                'softenerAddons' => AddonOption::where('category', 'softener')->where('is_active', true)->get(),
                
                // ส่งตัวแปรนี้ไปให้ Layout ใช้งานได้ทุกหน้า
                'isClosedToday' => $isClosedToday, 
            ]);
        });
    }
}