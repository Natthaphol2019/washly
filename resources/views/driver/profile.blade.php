@extends('layouts.driver')
@section('content')
<div class="py-2 sm:py-4">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center">
            <i class="fas fa-user-circle text-2xl washly-shell"></i>
        </div>
        <h1 class="text-2xl font-bold washly-shell">โปรไฟล์ของฉัน</h1>
    </div>

    @if(session('success'))
        <div class="washly-card p-4 rounded-xl mb-6 border-l-4 border-l-emerald-400 flex gap-3">
            <i class="fas fa-check-circle text-lg text-emerald-500"></i>
            <span class="font-medium washly-shell">{{ session('success') }}</span>
        </div>
    @endif

    <div class="washly-card p-6 sm:p-8 rounded-3xl">
        <form action="{{ route('driver.profile.update') }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold washly-shell mb-1">ไอดีเข้าระบบ</label>
                <input type="text" value="{{ Auth::user()->username }}" disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 washly-shell opacity-60 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-semibold washly-shell mb-1">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" value="{{ Auth::user()->fullname }}" required class="washly-input w-full px-4 py-3 rounded-xl washly-shell">
            </div>

            <div>
                <label class="block text-sm font-semibold washly-shell mb-1">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="washly-input w-full px-4 py-3 rounded-xl washly-shell">
            </div>

            <hr class="border-brand-border my-6">

            <div>
                <label class="block text-sm font-semibold washly-shell mb-1">รหัสผ่านใหม่ (ปล่อยว่างถ้าไม่เปลี่ยน)</label>
                <input type="password" name="password" placeholder="••••••••" class="washly-input w-full px-4 py-3 rounded-xl washly-shell">
            </div>

            <button type="submit" class="w-full py-4 washly-brand-btn rounded-xl font-bold text-white shadow-lg mt-4">
                <i class="fas fa-save mr-2 text-white"></i> บันทึกข้อมูล
            </button>
        </form>
    </div>
</div>
@endsection