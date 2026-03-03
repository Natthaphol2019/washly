<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - บริการรับส่งซักผ้า</title>
    
    @vite(['resources/css/app.css'])
</head>
<body class="bg-cute-bg text-cute-text min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-[30px] shadow-[0_10px_40px_-10px_rgba(255,182,193,0.6)] w-full max-w-md border-2 border-pink-50">

        <div class="text-center mb-8">
            <div class="bg-cute-bg w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-4xl">
                🛵🫧
            </div>
            <h2 class="text-2xl font-medium text-cute-hover">ยินดีต้อนรับกลับมา!</h2>
            <p class="text-sm text-gray-500 mt-2">ล็อกอินเพื่อจองคิวรับ-ส่งผ้ากันเถอะ 🌸</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf @if($errors->any())
                <div class="bg-red-50 text-red-500 text-sm p-3 rounded-2xl border border-red-200 text-center animate-pulse">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium mb-2 pl-2">ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="ใส่ชื่อผู้ใช้งานของคุณ" required
                       class="w-full px-5 py-3 rounded-full border border-pink-200 bg-pink-50/30 focus:outline-none focus:ring-2 focus:ring-cute-primary focus:border-transparent transition-all">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 pl-2">รหัสผ่าน (Password)</label>
                <input type="password" name="password" placeholder="••••••••" required
                       class="w-full px-5 py-3 rounded-full border border-pink-200 bg-pink-50/30 focus:outline-none focus:ring-2 focus:ring-cute-primary focus:border-transparent transition-all">
            </div>

            <button type="submit"
                    class="w-full bg-cute-primary hover:bg-cute-hover text-white font-medium py-3 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 duration-200 mt-2">
                เข้าสู่ระบบ 🧼
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            ยังไม่มีบัญชีใช่ไหม?
            <a href="/register" class="text-cute-hover font-medium hover:underline transition-all">สมัครสมาชิกที่นี่เลย</a>
        </p>

    </div>
</body>
</html>