<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // 1. ตรวจสอบข้อมูลก่อนบันทึก (Validation)
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // 2. ดึงข้อมูล User ปัจจุบันที่ล็อกอินอยู่
        $user = Auth::user();

        // 3. อัปเดตข้อมูล
        $user->fullname = $request->fullname;
        $user->phone = $request->phone;
        $user->address = $request->address;
        
        // ถ้ามีการส่งพิกัดมา ก็อัปเดตด้วย
        if ($request->has('latitude') && $request->latitude != null) {
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->map_link = $request->map_link;
        }

        // 4. เซฟลงฐานข้อมูลตาราง users
        $user->save();

        // 5. เด้งกลับไปหน้าเดิม พร้อมส่งข้อความแจ้งเตือนว่าสำเร็จ
        return redirect()->back()->with('success', 'บันทึกข้อมูลโปรไฟล์เรียบร้อยแล้ว! 🌸');
    }
}