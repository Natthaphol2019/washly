<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ระบบจัดการหลังบ้าน</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="washly-spectrum-bg washly-shell flex items-center justify-center min-h-screen font-sans p-4">
    
    <div class="washly-card p-8 rounded-2xl w-full max-w-md border">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold washly-brand-text">🛠️ Admin Portal</h2>
            <p class="text-sm text-gray-500 mt-2">ระบบจัดการบริการรับ-ส่งซักผ้า</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-50 text-red-500 text-sm p-3 rounded-lg border border-red-200 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username (รหัสพนักงาน)</label>
                <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-4 py-3 rounded-lg washly-input transition-all" autocomplete="off">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password (รหัสผ่าน)</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg washly-input transition-all">
            </div>

            <button type="submit" class="w-full washly-brand-btn text-white font-medium py-3 rounded-lg shadow-md hover:shadow-lg transition-all mt-4">
                เข้าสู่ระบบหลังบ้าน
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="/login" class="text-sm text-gray-500 hover:text-sky-700 hover:underline transition-all">
                ← กลับไปหน้าเข้าสู่ระบบลูกค้า
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertBase = {
                confirmButtonColor: '#1980d5',
                confirmButtonText: 'รับทราบ',
                customClass: { popup: 'rounded-3xl' }
            };

            @if(session('success'))
                Swal.fire({
                    ...alertBase,
                    title: 'สำเร็จ',
                    text: @json(session('success')),
                    icon: 'success'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    ...alertBase,
                    title: 'เข้าสู่ระบบไม่สำเร็จ',
                    text: @json($errors->first()),
                    icon: 'error'
                });
            @endif
        });
    </script>
</body>
</html>