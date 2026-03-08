<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ / สมัครสมาชิก - Washly</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css'])

    <style>
        /* CSS เสริมสำหรับลูกเล่นสลับหน้าและการตกแต่ง */
        .washly-spectrum-bg {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }
        .form-section {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hidden-form {
            opacity: 0;
            transform: translateX(20px);
            pointer-events: none;
            position: absolute;
            visibility: hidden;
        }
        .active-form {
            opacity: 1;
            transform: translateX(0);
            pointer-events: auto;
            position: relative;
            visibility: visible;
        }
    </style>
</head>
<body class="washly-spectrum-bg min-h-screen flex items-center justify-center p-4 overflow-x-hidden">

    @php
        $showRegister = session('line_id') || $errors->has('fullname') || $errors->has('phone') || $errors->has('address');
    @endphp

    <div class="bg-white/95 backdrop-blur-md p-8 rounded-[30px] w-full max-w-lg border border-white/50 shadow-2xl relative overflow-hidden">
        
        <div id="login-section" class="form-section w-full {{ $showRegister ? 'hidden-form' : 'active-form' }}">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-sky-100 text-sky-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner text-4xl">
                    🛵🫧
                </div>
                <h2 class="text-3xl font-bold text-sky-600">ยินดีต้อนรับกลับมา!</h2>
                <p class="text-sm text-gray-500 mt-2">ล็อกอินเพื่อจองคิวรับ-ส่งผ้ากันเถอะ 🌸</p>
            </div>

            <div class="mb-6">
                <a href="{{ route('line.login') }}" class="w-full bg-[#00B900] hover:bg-[#009900] text-white font-medium py-3.5 rounded-full shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-3 text-lg">
                    <i class="fa-brands fa-line text-2xl"></i> 
                    เข้าสู่ระบบด้วย LINE
                </a>
            </div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-gray-400 rounded-full">หรือเข้าสู่ระบบด้วยบัญชีทั่วไป</span>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf 
                @if($errors->any() && !$showRegister)
                    <div class="bg-red-50 text-red-500 text-sm p-3 rounded-2xl border border-red-200 text-center animate-pulse">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium mb-2 pl-2 text-gray-700">ชื่อผู้ใช้งาน (Username)</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="ใส่ชื่อผู้ใช้งานของคุณ" required
                           class="w-full px-5 py-3 rounded-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-2 focus:ring-sky-200 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2 pl-2 text-gray-700">รหัสผ่าน (Password)</label>
                    <input type="password" name="password" placeholder="••••••••" required
                           class="w-full px-5 py-3 rounded-full bg-gray-50 border-gray-200 focus:bg-white focus:ring-2 focus:ring-sky-200 outline-none transition-all">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-sky-400 to-blue-500 text-white font-medium py-3.5 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 duration-200 mt-4 text-lg">
                    เข้าสู่ระบบ 🧼
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-8">
                ยังไม่มีบัญชีใช่ไหม?
                <button type="button" onclick="toggleForms('register')" class="text-pink-500 font-bold hover:underline transition-all">สมัครสมาชิกที่นี่เลย ✨</button>
            </p>
        </div>

        <div id="register-section" class="form-section w-full {{ $showRegister ? 'active-form' : 'hidden-form' }}">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-pink-500">มาเป็นครอบครัวเดียวกัน! 🌸</h2>
                <p class="text-sm text-gray-500 mt-2">กรอกข้อมูลนิดเดียว ก็พร้อมเรียกไรเดอร์มารับผ้าแล้ว</p>
            </div>

            @if(session('line_id'))
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center gap-4 shadow-sm">
                    <img src="{{ session('line_avatar') }}" alt="LINE Profile" class="w-14 h-14 rounded-full shadow-md border-2 border-white">
                    <div>
                        <p class="text-xs text-green-600 font-medium mb-0.5">กำลังเชื่อมต่อกับบัญชี LINE</p>
                        <p class="text-green-800 font-bold text-lg">{{ session('line_name') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                @if($errors->any() && $showRegister)
                    <div class="bg-red-50 text-red-500 text-sm p-3 rounded-2xl border border-red-200">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium mb-1 pl-2 text-gray-700">ชื่อ-นามสกุล</label>
                    <input type="text" name="fullname" value="{{ old('fullname', session('line_name')) }}" required class="w-full px-5 py-3 rounded-full bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 pl-2 text-gray-700">ชื่อผู้ใช้งาน (Username)</label>
                        <input type="text" name="username" value="{{ old('username') }}" {{ session('line_id') ? '' : 'required' }} placeholder="{{ session('line_id') ? 'ตั้งหรือไม่ตั้งก็ได้' : '' }}" class="w-full px-5 py-3 rounded-full bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 pl-2 text-gray-700">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-5 py-3 rounded-full bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1 pl-2 pr-1">
                        <label class="block text-sm font-medium text-gray-700">ที่อยู่หลัก / หอพัก / เลขห้อง</label>
                        <button type="button" onclick="getLocation()" id="gps-btn" class="text-xs bg-sky-100 text-sky-700 hover:bg-sky-200 px-3 py-1.5 rounded-full font-medium transition-colors flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุด GPS</span>
                        </button>
                    </div>
                    
                    <textarea id="address" name="address" rows="2" required placeholder="กรอกที่อยู่ หรือกดปักหมุด GPS ให้ระบบช่วยดึงที่อยู่..." class="w-full px-5 py-3 rounded-2xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none transition-all resize-none">{{ old('address') }}</textarea>
                    
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    <input type="hidden" id="map_link" name="map_link" value="{{ old('map_link') }}">
                </div>

                @if(!session('line_id'))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                    <div>
                        <label class="block text-sm font-medium mb-1 pl-2 text-gray-700">รหัสผ่าน</label>
                        <input type="password" name="password" required class="w-full px-5 py-3 rounded-full bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 pl-2 text-gray-700">ยืนยันรหัสผ่าน</label>
                        <input type="password" name="password_confirmation" required class="w-full px-5 py-3 rounded-full bg-gray-50 focus:bg-white focus:ring-2 focus:ring-pink-200 outline-none transition-all">
                    </div>
                </div>
                @endif

                <button type="submit" class="w-full bg-gradient-to-r from-pink-400 to-rose-400 text-white font-medium py-3.5 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 mt-4 text-lg">
                    สมัครสมาชิกเลย! ✨
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                มีบัญชีอยู่แล้วใช่ไหม? 
                <button type="button" onclick="toggleForms('login')" class="text-sky-600 font-bold hover:underline transition-all">เข้าสู่ระบบที่นี่</button>
            </p>
        </div>

    </div>

    <script>
        // ฟังก์ชันสลับการ์ด Login <-> Register
        function toggleForms(target) {
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');

            if (target === 'register') {
                loginSection.classList.replace('active-form', 'hidden-form');
                setTimeout(() => {
                    registerSection.classList.replace('hidden-form', 'active-form');
                }, 100); // ดีเลย์นิดนึงให้ตัวเก่าหายไปก่อน
            } else {
                registerSection.classList.replace('active-form', 'hidden-form');
                setTimeout(() => {
                    loginSection.classList.replace('hidden-form', 'active-form');
                }, 100);
            }
        }

        // ฟังก์ชันดึง GPS เดิมของคุณซี
        function getLocation() {
            const gpsBtn = document.getElementById('gps-btn');
            const gpsText = document.getElementById('gps-text');
            const addressInput = document.getElementById('address');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const mapLinkInput = document.getElementById('map_link');

            if (navigator.geolocation) {
                gpsText.innerText = "กำลังหา...";
                gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span id="gps-text">กำลังหา...</span>';
                gpsBtn.classList.add('opacity-75', 'cursor-not-allowed');

                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const mapLink = `https://www.google.com/maps?q=$${lat},${lng}`;
                        
                        latInput.value = lat;
                        lngInput.value = lng;
                        mapLinkInput.value = mapLink;

                        try {
                            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=th`);
                            const data = await response.json();
                            if (data && data.display_name) {
                                addressInput.value = data.display_name;
                            } else {
                                addressInput.value = `พิกัด GPS: ${mapLink}`; 
                            }
                        } catch (error) {
                            addressInput.value = `พิกัด GPS: ${mapLink}`;
                        }

                        gpsBtn.innerHTML = '<i class="fa-solid fa-check text-green-600"></i> <span id="gps-text">สำเร็จ!</span>';
                        gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-sky-100', 'text-sky-700');
                        gpsBtn.classList.add('bg-green-100', 'text-green-600');
                    }, 
                    function(error) {
                        alert('ไม่สามารถดึงตำแหน่งได้ กรุณาอนุญาตให้เบราว์เซอร์เข้าถึง Location ของคุณครับ');
                        gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุด GPS</span>';
                        gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    }
                );
            } else {
                alert("เบราว์เซอร์ของคุณไม่รองรับการปักหมุด GPS ครับ");
            }
        }
    </script>
</body>
</html>