<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DriverController;
use App\Models\Package;

// ==========================================
// 🏠 หน้าแรก (Root) - เช็กทิศทางหลังล็อกอิน
// ==========================================
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if (in_array($role, ['admin', 'staff'], true)) {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'driver') {
            return redirect()->route('driver.dashboard');
        }
        return redirect()->route('customer.main');
    }
    return redirect()->route('login');
});

// ==========================================
// 🚪 โซนผู้เยี่ยมชม (Guest - ยังไม่ได้ล็อกอิน)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);

    Route::get('/auth/line', [AuthController::class, 'redirectToLine'])->name('line.login');
    Route::get('/auth/line/callback', [AuthController::class, 'handleLineCallback']);
});

// ==========================================
// 🔒 โซนสมาชิก (Auth - ล็อกอินแล้ว)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications');
    Route::post('/api/notifications/read-all', [NotificationController::class, 'readAll'])->name('api.notifications.read_all');
    Route::post('/api/notifications/{id}/read', [NotificationController::class, 'readOne'])->name('api.notifications.read_one');
    Route::get('/notifications', [NotificationController::class, 'page'])->name('notifications.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // 👑 โซนแอดมิน (Admin & Staff)
    // ------------------------------------------
    Route::prefix('admin')->middleware('role.admin_or_staff')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings.index');
        Route::post('/settings/delivery', [AdminController::class, 'updateDeliverySettings'])->name('admin.settings.delivery.update');
        Route::post('/settings/delivery/preview', [AdminController::class, 'previewDeliveryQuote'])->name('admin.settings.delivery.preview');
        Route::post('/settings/delivery/backfill', [AdminController::class, 'backfillOrderDistances'])->name('admin.settings.delivery.backfill');
        Route::get('/main', function () { return redirect()->route('admin.dashboard'); })->name('admin.main');

        // จัดการออเดอร์
        Route::get('/orders', [AdminController::class, 'manageOrders'])->name('admin.orders.index');
        Route::put('/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.status');
        Route::post('/orders/{id}/approve-payment', [AdminController::class, 'approvePayment'])->name('admin.orders.approve_payment');
        Route::post('/orders/{id}/confirm-cash', [AdminController::class, 'confirmCashPayment'])->name('admin.orders.confirm_cash');
        Route::post('/orders/{id}/reject-slip', [AdminController::class, 'rejectSlip'])->name('admin.orders.reject_slip');
        Route::post('/orders/{id}/cancel', [AdminController::class, 'cancelOrder'])->name('admin.orders.cancel');
        Route::post('/orders/{id}/assign-driver', [AdminController::class, 'assignDriver'])->name('admin.orders.assign_driver');

        // แพ็กเกจ & Addon
        Route::get('/packages', [AdminController::class, 'packages'])->name('admin.packages.index');
        Route::post('/packages', [AdminController::class, 'storePackage'])->name('admin.packages.store');
        Route::put('/packages/{id}', [AdminController::class, 'updatePackage'])->name('admin.packages.update');
        Route::delete('/packages/{id}', [AdminController::class, 'destroyPackage'])->name('admin.packages.destroy');
        Route::post('/addons', [AdminController::class, 'storeAddon'])->name('admin.addons.store');
        Route::put('/addons/{id}', [AdminController::class, 'updateAddon'])->name('admin.addons.update');
        Route::delete('/addons/{id}', [AdminController::class, 'destroyAddon'])->name('admin.addons.destroy');
        
        // จัดการผู้ใช้งาน
        Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers.index');
        Route::post('/customers', [AdminController::class, 'storeCustomer'])->name('admin.customers.store');
        Route::put('/customers/{id}', [AdminController::class, 'updateCustomer'])->name('admin.customers.update');
        Route::delete('/customers/{id}', [AdminController::class, 'destroyCustomer'])->name('admin.customers.destroy');
        
        Route::get('/staff', [AdminController::class, 'staff'])->name('admin.staff.index');
        Route::post('/staff', [AdminController::class, 'storeStaff'])->name('admin.staff.store');
        Route::put('/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.staff.update');
        Route::delete('/staff/{id}', [AdminController::class, 'destroyStaff'])->name('admin.staff.destroy');

        Route::get('/drivers', [AdminController::class, 'drivers'])->name('admin.drivers.index');
        Route::post('/drivers', [AdminController::class, 'storeDriver'])->name('admin.drivers.store');
        Route::put('/drivers/{id}', [AdminController::class, 'updateDriver'])->name('admin.drivers.update');
        Route::delete('/drivers/{id}', [AdminController::class, 'destroyDriver'])->name('admin.drivers.destroy');
    });

    // ------------------------------------------
    // 🛵 โซนคนขับรถ (Driver)
    // ------------------------------------------
    Route::prefix('driver')->middleware('role.driver')->group(function () {
        Route::get('/dashboard', [DriverController::class, 'index'])->name('driver.dashboard');

        // ออเดอร์
        Route::post('/orders/{id}/accept', [DriverController::class, 'acceptJob'])->name('driver.orders.accept');
        Route::put('/orders/{id}/status', [DriverController::class, 'updateStatus'])->name('driver.orders.status');
        Route::post('/orders/{id}/cancel', [DriverController::class, 'cancelOrder'])->name('driver.orders.cancel');
        
        // การชำระเงิน (Driver สามารถอนุมัติ/ปฏิเสธสลิปได้)
        Route::post('/orders/{id}/approve-payment', [DriverController::class, 'approvePayment'])->name('driver.orders.approve_payment');
        Route::post('/orders/{id}/confirm-cash', [DriverController::class, 'confirmCashPayment'])->name('driver.orders.confirm_cash');
        Route::post('/orders/{id}/reject-slip', [DriverController::class, 'rejectSlip'])->name('driver.orders.reject_slip');

        Route::get('/history', [DriverController::class, 'history'])->name('driver.history');
        Route::get('/profile', [DriverController::class, 'profile'])->name('driver.profile');
        Route::put('/profile', [DriverController::class, 'updateProfile'])->name('driver.profile.update');
    });

    // ------------------------------------------
    // 👤 โซนลูกค้า (Customer)
    // ------------------------------------------
    Route::prefix('customer')->middleware('role.customer')->group(function () {
        Route::get('/dashboard', function () { return redirect()->route('customer.main'); })->name('customer.dashboard');
        Route::get('/main', [ProfileController::class, 'mainDashboard'])->name('customer.main');
        Route::view('/profile', 'customer.profile')->name('customer.profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('customer.profile.update');

        Route::get('/book', [BookingController::class, 'showBookingForm'])->name('customer.book');
        Route::get('/book/delivery-quote', [BookingController::class, 'deliveryQuote'])->name('customer.book.delivery_quote');
        Route::post('/book', [BookingController::class, 'store'])->name('customer.book.store');

        Route::get('/orders', [OrderController::class, 'index'])->name('customer.orders');
        Route::get('/orders/{id}/pay', [OrderController::class, 'paymentForm'])->name('customer.orders.pay');
        Route::post('/orders/{id}/pay', [OrderController::class, 'uploadSlip'])->name('customer.orders.upload_slip');
        Route::post('/orders/{id}/payment-method/cash', [OrderController::class, 'switchToCash'])->name('customer.orders.switch_to_cash');

        Route::get('/packages', function () { return view('customer.packages', ['packages' => Package::orderBy('price')->get()]); })->name('customer.packages');
    });
});