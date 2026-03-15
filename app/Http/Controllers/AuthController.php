<?php

namespace App\Http\Controllers;

use App\Services\DeliveryDistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $request->session()->regenerateToken();
        return view('login'); // หน้าสีชมพู
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['username' => ['required'], 'password' => ['required']]);

        if (Auth::attempt($credentials)) {
            // ล็อกอินผ่านแล้ว เช็กสิทธิ์ว่าเป็นลูกค้าจริงไหม
            if (Auth::user()->role === 'customer') {
                $request->session()->regenerate();
                return redirect()->route('customer.main')->with('success', 'เข้าสู่ระบบสำเร็จ ยินดีต้อนรับกลับมาครับ');
            } else {
                // ถ้าเป็นแอดมินแอบมาเข้าประตูนี้ เตะออก!
                Auth::logout();
                return back()->withErrors(['username' => 'บัญชีนี้เป็นของแอดมิน กรุณาเข้าสู่ระบบที่หน้า Admin จ้า 😅'])->onlyInput('username');
            }
        }
        return back()->withErrors(['username' => 'ชื่อผู้ใช้งาน หรือ รหัสผ่าน ไม่ถูกต้องนะ! 🥺'])->onlyInput('username');
    }

    public function showRegister(Request $request)
    {
        $request->session()->regenerateToken();
        return view('register');
    }

    public function register(Request $request, DeliveryDistanceService $deliveryDistanceService)
    {
        // 1. ตรวจสอบข้อมูล (Validation) ตามชื่อฟิลด์ในฟอร์ม HTML
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username', // ห้ามซ้ำกับคนอื่น
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'password' => 'required|string|min:8|confirmed', // ต้องมี password_confirmation ส่งมาด้วย
        ], [
            // ข้อความแจ้งเตือนภาษาไทย (จะไปแสดงในกล่องแดงๆ หน้าเว็บ)
            'fullname.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'username.required' => 'กรุณากรอกชื่อผู้ใช้งาน',
            'username.unique' => 'ชื่อผู้ใช้งานนี้มีคนใช้ไปแล้วครับ',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'address.required' => 'กรุณากรอกที่อยู่หรือปักหมุด GPS',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        $latitude = $deliveryDistanceService->normalizeCoordinate($validated['latitude'] ?? null, -90, 90);
        $longitude = $deliveryDistanceService->normalizeCoordinate($validated['longitude'] ?? null, -180, 180);

        // 2. บันทึกข้อมูลลงฐานข้อมูล
        $user = User::create([
            'fullname' => $validated['fullname'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'map_link' => $deliveryDistanceService->makeMapLink($latitude, $longitude),
            'password' => Hash::make($validated['password']), // เข้ารหัสผ่านเสมอ
        ]);

        // 3. ล็อกอินให้ผู้ใช้ทันทีหลังสมัครเสร็จ
        Auth::login($user);

        // 4. พากลับไปหน้าหลัก (หรือหน้า Dashboard ที่เตรียมไว้)
        return redirect('/')->with('success', 'สมัครสมาชิกสำเร็จ! ยินดีต้อนรับครับ');
    }

    public function showAdminLogin(Request $request)
    {
        // ไม่ต้อง regenerateToken ที่นี่ เพราะจะทำให้ token เก่าใช้ไม่ได้
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (in_array($user->role, ['admin', 'staff'], true)) {
                // Regenerate session เพื่อความปลอดภัย
                $request->session()->regenerate();
                
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'เข้าสู่ระบบหลังบ้านสำเร็จ');
            } elseif ($user->role === 'driver') {
                $request->session()->regenerate();
                
                return redirect()->intended(route('driver.dashboard'))
                    ->with('success', 'เข้าสู่ระบบคนขับสำเร็จ พร้อมลุยงาน!');
            }
            
            // ถ้าเป็นลูกค้าหรือใครที่ไม่ใช่แอดมิน แอบมาเข้าประตูนี้ เตะออก!
            Auth::logout();

            return back()->withErrors([
                'username' => 'บัญชีนี้เป็นของลูกค้า กรุณาเข้าสู่ระบบที่หน้าลูกค้าครับ',
            ])->onlyInput('username');
        }

        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้งาน หรือ รหัสผ่านของแอดมินไม่ถูกต้อง',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }

    // 1. ฟังก์ชันพาลูกค้าไปหน้ายืนยันตัวตนของ LINE
    public function redirectToLine()
    {
        return Socialite::driver('line')->redirect();
    }

    // 2. ฟังก์ชันรับข้อมูลกลับมาจาก LINE
    public function handleLineCallback()
    {
        try {
            // รับข้อมูลส่วนตัวลูกค้าจาก LINE
            $lineUser = Socialite::driver('line')->user();

            // เช็คว่าเคยมี LINE ID นี้ในระบบ Washly หรือยัง?
            $existingUser = User::where('line_id', $lineUser->getId())->first();

            if ($existingUser) {
                // กรณีที่ 1: ลูกค้าเก่า (ล็อกอินให้เลย แล้วพาไปหน้าหลัก)
                Auth::login($existingUser);
                return redirect('/')->with('success', 'ยินดีต้อนรับกลับมาครับ!');
            } else {
                // กรณีที่ 2: ลูกค้าใหม่ (ยังไม่มีในระบบ)
                // เราจะเก็บข้อมูลจาก LINE ไว้ใน Session ชั่วคราว ก่อนพาไปหน้ากรอกเบอร์/ที่อยู่
                session([
                    'line_id' => $lineUser->getId(),
                    'line_name' => $lineUser->getName(),
                    'line_avatar' => $lineUser->getAvatar(),
                ]);

                // พาไปหน้าฟอร์มสมัครสมาชิก (หน้าที่น้องซีทำไว้) เพื่อกรอกข้อมูลที่เหลือ
                return redirect('/register')->with('info', 'ผูกบัญชี LINE สำเร็จ! กรุณากรอกที่อยู่และเบอร์โทรเพื่อเริ่มใช้งานครับ');
            }

        } catch (\Exception $e) {
            // ถ้าลูกค้ากดยกเลิก หรือระบบพัง ให้พากลับไปหน้าล็อกอิน
            return redirect('/login')->withErrors(['error' => 'การเข้าสู่ระบบด้วย LINE ขัดข้อง กรุณาลองใหม่ครับ']);
        }
    }
}