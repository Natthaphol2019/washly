<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                return redirect()->route('customer.main');
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

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
        ]);

        Auth::login($user);
        return redirect()->route('customer.main');
    }
    // ==========================================
    public function showAdminLogin()
    {
        return view('admin.login'); // หน้าเรียบๆ สีเทา
    }
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate(['username' => ['required'], 'password' => ['required']]);

        if (Auth::attempt($credentials)) {
            // เช็กว่าเป็นแอดมินจริงไหม
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } else {
                // ถ้าลูกค้าแอบมาเข้าประตูแอดมิน เตะออก!
                Auth::logout();
                return back()->withErrors(['username' => 'คุณไม่มีสิทธิ์เข้าใช้งานระบบหลังบ้านครับ! 🚫'])->onlyInput('username');
            }
        }
        return back()->withErrors(['username' => 'ชื่อผู้ใช้งาน หรือ รหัสผ่านแอดมิน ไม่ถูกต้อง'])->onlyInput('username');
    }
    public function logout(Request $request)
    {
        $role = Auth::user()->role; // จำไว้ก่อนว่าใครกดออก
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ตอนออก ถ้าเป็นแอดมินให้เด้งไปหน้าล็อกอินแอดมิน ถ้าเป็นลูกค้าไปหน้าลูกค้า
        return $role === 'admin' ? redirect('/admin/login') : redirect('/login');
    }
}