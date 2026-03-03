<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Models\Package;

// ==========================================
// 🏠 หน้าแรก (Root) - เช็กทิศทางหลังล็อกอิน
// ==========================================
Route::get('/', function () {
    if (Auth::check()) {
        // ใช้ admin.dashboard เป็นมาตรฐานหลัก
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('customer.main');
    }
    return redirect()->route('login');
});

// ==========================================
// 🚪 โซนผู้เยี่ยมชม (Guest - ยังไม่ได้ล็อกอิน)
// ==========================================
Route::middleware('guest')->group(function () {
    // 🌸 ทางเข้าลูกค้า
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // 🛠️ ทางเข้าแอดมิน (ความลับ)
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
});

// ==========================================
// 🔒 โซนสมาชิก (Auth - ล็อกอินแล้ว)
// ==========================================
Route::middleware('auth')->group(function () {

    // ออกจากระบบ
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // 👑 โซนแอดมิน (Admin)
    // ------------------------------------------
    Route::prefix('admin')->group(function () {

        // 📊 ภาพรวมระบบ (Dashboard)
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // 🔄 เผื่อโค้ดเก่าใช้ admin.main ให้เด้งกลับมาหน้า Dashboard (ป้องกันลิงก์พัง)
        Route::get('/main', function () {
            return redirect()->route('admin.dashboard');
        })->name('admin.main');

        // 📋 จัดการออเดอร์ และ สถานะ
        Route::get('/orders', [AdminController::class, 'manageOrders'])->name('admin.orders.index');
        Route::put('/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.status');

        // 💸 ตรวจสอบสลิปโอนเงิน
        Route::post('/orders/{id}/approve-payment', [AdminController::class, 'approvePayment'])->name('admin.orders.approve_payment');
        Route::post('/orders/{id}/reject-slip', [AdminController::class, 'rejectSlip'])->name('admin.orders.reject_slip');

        // 📦 ระบบจัดการแพ็กเกจ (CRUD)
        Route::get('/packages', [AdminController::class, 'packages'])->name('admin.packages.index');
        Route::post('/packages', [AdminController::class, 'storePackage'])->name('admin.packages.store');
        Route::put('/packages/{id}', [AdminController::class, 'updatePackage'])->name('admin.packages.update');
        Route::delete('/packages/{id}', [AdminController::class, 'destroyPackage'])->name('admin.packages.destroy');
    });

    Route::prefix('customer')->group(function () {

        // รองรับลิงก์เก่า customer.dashboard
        Route::get('/dashboard', function () {
            return redirect()->route('customer.main');
        })->name('customer.dashboard');

        // 🏠 หน้าแรก & โปรไฟล์ (ใช้ Route::view ทำให้โค้ดสั้นลงมาก)
        Route::view('/main', 'customer.main')->name('customer.main');
        Route::view('/profile', 'customer.profile')->name('customer.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('customer.profile.update');

        // 🧺 จองคิวซักผ้า
        Route::get('/book', [BookingController::class, 'showBookingForm'])->name('customer.book');
        Route::post('/book', [BookingController::class, 'store'])->name('customer.book.store');

        // 🛵 ประวัติออเดอร์ & อัปโหลดสลิป
        Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders');
        Route::get('/orders/{id}/pay', [OrderController::class, 'paymentForm'])->name('customer.orders.pay');
        Route::post('/orders/{id}/pay', [OrderController::class, 'uploadSlip'])->name('customer.orders.upload_slip');

        // 📦 ดูแพ็กเกจ
        Route::get('/packages', function () {
            return view('customer.packages', ['packages' => Package::orderBy('price')->get()]);
        })->name('customer.packages');
    });
});