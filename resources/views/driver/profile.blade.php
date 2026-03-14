@extends('layouts.driver')
@section('title', 'โปรไฟล์ของฉัน - ไรเดอร์')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:py-8 px-3 sm:px-6 space-y-6 sm:space-y-8 pb-24 sm:pb-20">
    
    {{-- 🌟 หัวข้อหลัก (Header Banner) สไตล์คลีน --}}
    <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl bg-white dark:bg-slate-800 p-5 sm:p-8 shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-500 flex justify-between items-center">
        <div class="relative z-10">
            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1 flex items-center gap-2 text-xs sm:text-base transition-colors duration-500">
                <i class="fa-solid fa-id-card text-indigo-500 dark:text-indigo-400"></i> ข้อมูลส่วนตัว
            </p>
            <h1 class="text-2xl sm:text-4xl font-black tracking-tight text-gray-800 dark:text-gray-100 transition-colors duration-500">
                โปรไฟล์ของฉัน
            </h1>
        </div>
        <div class="hidden sm:flex relative z-10 w-16 sm:w-20 h-16 sm:h-20 bg-indigo-50 dark:bg-slate-700/50 rounded-full items-center justify-center border border-indigo-100 dark:border-slate-600 transition-colors duration-500">
            <i class="fa-solid fa-user-astronaut text-3xl sm:text-4xl text-indigo-500 dark:text-indigo-400"></i>
        </div>
        <div class="absolute -right-10 -top-10 w-32 sm:w-40 h-32 sm:h-40 bg-indigo-100 dark:bg-indigo-900/20 opacity-60 rounded-full blur-2xl"></div>
        <div class="absolute right-20 -bottom-10 w-24 sm:w-32 h-24 sm:h-32 bg-purple-100 dark:bg-purple-900/20 opacity-60 rounded-full blur-xl"></div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg sm:text-xl text-emerald-500"></i>
            <span class="text-sm sm:text-base font-bold text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm">
            <ul class="list-disc list-inside text-xs sm:text-sm font-medium text-rose-700 dark:text-rose-400">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 📝 ฟอร์มแก้ไขข้อมูล --}}
    <div class="bg-white dark:bg-slate-800 border border-indigo-100 dark:border-slate-700 p-5 sm:p-10 rounded-2xl sm:rounded-3xl shadow-sm relative overflow-hidden transition-colors duration-500">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 sm:w-2 bg-indigo-400"></div>

        <form action="{{ route('driver.profile.update') }}" method="POST" class="space-y-5 sm:space-y-6" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = '<i class=\'fa-solid fa-spinner fa-spin\'></i> กำลังบันทึกข้อมูล...';">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 pl-2 sm:pl-0">
                {{-- Username (Disabled) --}}
                <div class="md:col-span-2">
                    <label class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                        ไอดีเข้าระบบ <span class="text-[10px] sm:text-xs text-gray-400 font-normal">(ไม่สามารถเปลี่ยนได้)</span>
                    </label>
                    <input type="text" value="{{ Auth::user()->username }}" disabled class="w-full px-4 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50 text-gray-500 text-sm sm:text-base font-bold cursor-not-allowed shadow-inner transition-colors duration-500">
                </div>

                {{-- Fullname --}}
                <div class="md:col-span-2">
                    <label class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname', Auth::user()->fullname) }}" required class="w-full px-4 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-white text-sm sm:text-base focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-bold">
                </div>

                {{-- Phone --}}
                <div class="md:col-span-2">
                    <label class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">เบอร์โทรศัพท์ติดต่อ</label>
                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full px-4 py-3 sm:py-3.5 rounded-xl sm:rounded-2xl border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-white text-sm sm:text-base focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-bold">
                </div>
            </div>

            {{-- โซนเปลี่ยนรหัสผ่าน --}}
            <div class="bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 rounded-xl sm:rounded-2xl p-4 sm:p-6 mt-5 sm:mt-6 ml-2 sm:ml-0 transition-colors duration-500">
                <label class="block text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5 sm:mb-2">
                    ตั้งรหัสผ่านใหม่ <span class="text-[10px] sm:text-xs text-gray-400 font-normal">(ปล่อยว่างไว้หากไม่ต้องการเปลี่ยน)</span>
                </label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 sm:py-3.5 rounded-lg sm:rounded-xl border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-white text-sm sm:text-base focus:ring-4 focus:ring-rose-500/20 focus:border-rose-400 outline-none transition-all font-bold">
                
                <p class="text-[10px] sm:text-[11px] text-gray-500 mt-2 flex items-start gap-1">
                    <i class="fa-solid fa-circle-info mt-0.5"></i> 
                    <span>ถ้าระบุรหัสผ่าน ระบบจะทำการเปลี่ยนรหัสผ่านในการเข้าระบบครั้งต่อไปทันที</span>
                </p>
            </div>

            <div class="pl-2 sm:pl-0 pt-2 sm:pt-4">
                <button type="submit" class="w-full py-3.5 sm:py-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl sm:rounded-2xl font-bold text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center gap-2 text-sm sm:text-base">
                    <i class="fa-solid fa-floppy-disk text-white"></i> บันทึกข้อมูลโปรไฟล์
                </button>
            </div>
        </form>
    </div>
</div>
@endsection