@extends('layouts.customer')

@section('title', 'หน้าแรก - Washly')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-8 pt-2">

    <div class="relative bg-gradient-to-r from-pink-500 to-rose-400 rounded-3xl p-8 sm:p-10 shadow-lg overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="absolute -right-10 -top-10 text-white/10 text-[150px] transform rotate-12 pointer-events-none">
            <i class="fa-solid fa-shirt"></i>
        </div>
        
        <div class="relative z-10 text-center sm:text-left text-white w-full sm:w-2/3">
            <p class="text-pink-100 text-sm sm:text-base mb-1 font-medium">ยินดีต้อนรับ</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-3 line-clamp-1">คุณ {{ explode(' ', Auth::user()->fullname)[0] ?? 'ลูกค้าคนพิเศษ' }}</h1>
            <p class="text-white/90 text-sm sm:text-base mb-6">ผ้าล้นตะกร้าแล้วใช่ไหม? ปล่อยให้เป็นหน้าที่ของเราดูแลเสื้อผ้าตัวโปรดของคุณให้หอมสะอาดเหมือนใหม่!</p>
            
            <a href="{{ route('customer.book') }}" class="inline-flex items-center justify-center bg-white text-pink-600 font-bold py-3.5 px-8 rounded-full shadow-lg hover:shadow-xl hover:bg-pink-50 transition-all transform hover:-translate-y-1 w-full sm:w-auto">
                <i class="fa-solid fa-plus-circle text-lg mr-2"></i> จองคิวรับผ้าด่วน!
            </a>
        </div>

        <div class="hidden sm:flex relative z-10 w-1/3 justify-center items-center">
            <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/30 shadow-inner">
                <i class="fa-solid fa-jug-detergent text-6xl text-white drop-shadow-md"></i>
            </div>
        </div>
    </div>

    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 px-2 mt-8 mb-4">
        <i class="fa-solid fa-bolt text-yellow-500 mr-2"></i> เมนูลัด
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        
        <a href="{{ route('customer.book') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-pink-300 dark:hover:border-slate-500 transition-all group flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-pink-50 dark:bg-pink-900/30 text-pink-500 rounded-full flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm">จองคิวซักผ้า</h3>
            <p class="text-[10px] text-gray-500 mt-1">เรียกไรเดอร์เข้ารับผ้า</p>
        </a>

        <a href="{{ route('customer.orders') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-blue-300 dark:hover:border-slate-500 transition-all group flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 text-blue-500 rounded-full flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm">ออเดอร์ของฉัน</h3>
            <p class="text-[10px] text-gray-500 mt-1">ติดตามสถานะ & ชำระเงิน</p>
        </a>

        <a href="{{ route('customer.packages') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-purple-300 dark:hover:border-slate-500 transition-all group flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 text-purple-500 rounded-full flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-tags"></i>
            </div>
            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm">ราคาแพ็กเกจ</h3>
            <p class="text-[10px] text-gray-500 mt-1">ดูเรทราคาซัก อบ รีด</p>
        </a>

        <a href="{{ route('customer.profile') }}" class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:border-emerald-300 dark:hover:border-slate-500 transition-all group flex flex-col items-center text-center">
            <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 rounded-full flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm">ข้อมูลส่วนตัว</h3>
            <p class="text-[10px] text-gray-500 mt-1">แก้ไขที่อยู่จัดส่ง</p>
        </a>

    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-slate-700 mt-8">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 text-center">
            ซักผ้ากับ Washly ง่ายๆ ใน 3 ขั้นตอน
        </h2>
        
        <div class="flex flex-col md:flex-row gap-6 justify-between items-center relative">
            <div class="hidden md:block absolute top-1/2 left-[10%] right-[10%] h-0.5 bg-gray-200 dark:bg-slate-700 -z-0 transform -translate-y-1/2"></div>

            <div class="flex flex-col items-center text-center z-10 w-full md:w-1/3">
                <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full border-4 border-pink-100 dark:border-slate-700 flex items-center justify-center text-2xl text-pink-500 shadow-sm mb-3">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1">1. กดจองคิว</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">เลือกแพ็กเกจและระบุที่อยู่<br>ให้เราไปรับผ้า</p>
            </div>

            <div class="flex flex-col items-center text-center z-10 w-full md:w-1/3">
                <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full border-4 border-pink-100 dark:border-slate-700 flex items-center justify-center text-2xl text-blue-500 shadow-sm mb-3">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1">2. รับผ้า & ชำระเงิน</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">ไรเดอร์เข้ารับผ้าถึงหน้าบ้าน<br>และชำระเงินผ่านแอป</p>
            </div>

            <div class="flex flex-col items-center text-center z-10 w-full md:w-1/3">
                <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-full border-4 border-pink-100 dark:border-slate-700 flex items-center justify-center text-2xl text-emerald-500 shadow-sm mb-3">
                    <i class="fa-solid fa-shirt"></i>
                </div>
                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1">3. รอรับผ้าหอมๆ</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400">เราซัก อบ พับ อย่างทะนุถนอม<br>พร้อมส่งคืนภายใน 24 ชม.</p>
            </div>
        </div>
    </div>

</div>
@endsection