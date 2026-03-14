@extends('layouts.driver')
@section('content')
<div class="py-2 sm:py-4 space-y-6">
    
    <div class="flex items-center gap-3 mb-4">
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center border border-indigo-100 dark:border-indigo-800">
            <i class="fas fa-user-circle text-2xl text-indigo-600 dark:text-indigo-400"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">โปรไฟล์ของฉัน</h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl flex items-center gap-3 shadow-sm mb-4">
            <i class="fas fa-check-circle text-lg text-emerald-500"></i>
            <span class="font-medium text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 sm:p-8 rounded-3xl shadow-sm">
        <form action="{{ route('driver.profile.update') }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ไอดีเข้าระบบ</label>
                <input type="text" value="{{ Auth::user()->username }}" disabled class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 text-slate-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" value="{{ Auth::user()->fullname }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 my-6 pt-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">รหัสผ่านใหม่ <span class="text-xs text-slate-400 font-normal">(ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)</span></label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>

            <button type="submit" class="w-full py-4 washly-brand-btn rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all mt-4">
                <i class="fas fa-save mr-2 text-white"></i> บันทึกข้อมูล
            </button>
        </form>
    </div>
</div>
@endsection