<?php

namespace App\Http\Controllers;

use App\Services\DeliveryDistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show customer main dashboard page
     */
    public function mainDashboard()
    {
        return view('customer.main');
    }

    public function update(Request $request, DeliveryDistanceService $deliveryDistanceService)
    {
        // 1. ตรวจสอบข้อมูลก่อนบันทึก (Validation)
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // 2. ดึงข้อมูล User ปัจจุบันที่ล็อกอินอยู่
        $user = Auth::user();

        // 3. อัปเดตข้อมูล
        $user->fullname = $request->fullname;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // ถ้ามีการส่งพิกัดมา ก็อัปเดตด้วย
        $latitude = $deliveryDistanceService->normalizeCoordinate($request->input('latitude'), -90, 90);
        $longitude = $deliveryDistanceService->normalizeCoordinate($request->input('longitude'), -180, 180);

        if ($latitude !== null && $longitude !== null) {
            $user->latitude = $latitude;
            $user->longitude = $longitude;
            $user->map_link = $deliveryDistanceService->makeMapLink($latitude, $longitude);
        } else {
            $user->latitude = null;
            $user->longitude = null;
            $user->map_link = null;
        }

        // 4. เซฟลงฐานข้อมูลตาราง users
        $user->save();

        // 5. เด้งกลับไปหน้าเดิม พร้อมส่งข้อความแจ้งเตือนว่าสำเร็จ
        return redirect()->back()->with('success', 'บันทึกข้อมูลโปรไฟล์เรียบร้อยแล้ว! 🌸');
    }

    /**
     * เปลี่ยนรหัสผ่าน
     */
    public function changePassword(Request $request)
    {
        // 1. ตรวจสอบข้อมูล
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'กรุณากรอกรหัสผ่านปัจจุบัน',
            'new_password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'new_password.min' => 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร',
            'new_password.confirmed' => 'รหัสผ่านใหม่ไม่ตรงกัน',
        ]);

        $user = Auth::user();

        // 2. เช็คว่ารหัสผ่านปัจจุบันถูกต้องหรือไม่
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }

        // 3. อัปเดตรหัสผ่านใหม่
        $user->password = Hash::make($request->new_password);
        $user->save();

        // 4. เด้งกลับไปหน้าเดิม พร้อมส่งข้อความแจ้งเตือน
        return redirect()->back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว! 🔐');
    }
}