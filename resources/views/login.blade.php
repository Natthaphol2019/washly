<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ / สมัครสมาชิก - Washly</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])

    <style>
        /* CSS เสริมสำหรับลูกเล่นสลับหน้าและการตกแต่ง */
        .washly-spectrum-bg {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            position: relative;
            overflow: hidden;
        }

        .washly-spectrum-bg::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            animation: bubbleFloat 20s infinite linear;
        }

        @keyframes bubbleFloat {
            0% {
                transform: translate(-10%, -10%) rotate(0deg);
            }

            100% {
                transform: translate(10%, 10%) rotate(10deg);
            }
        }

        .form-container {
            perspective: 1200px;
            position: relative;
            transition: height 0.55s cubic-bezier(0.22, 1, 0.36, 1);
            --hidden-shift-x: 0px;
            --hidden-shift-y: 42px;
            --exit-prev-x: 0px;
            --exit-prev-y: -54px;
            --exit-next-x: 0px;
            --exit-next-y: 54px;
            --enter-next-x: 0px;
            --enter-next-y: 68px;
            --enter-prev-x: 0px;
            --enter-prev-y: -68px;
            --content-enter-x: 0px;
            --content-enter-y: 14px;
        }

        .form-stage {
            position: relative;
            min-height: 1px;
        }

        .form-section {
            position: absolute;
            inset: 0;
            width: 100%;
            transform-origin: center center;
            backface-visibility: hidden;
            transition:
                opacity 0.5s ease,
                transform 0.55s cubic-bezier(0.22, 1, 0.36, 1),
                filter 0.45s ease;
            will-change: transform, opacity;
        }

        .hidden-form {
            opacity: 0;
            transform: translate3d(var(--hidden-shift-x), var(--hidden-shift-y), 0) scale(0.96);
            filter: blur(8px);
            pointer-events: none;
            visibility: hidden;
        }

        .active-form {
            opacity: 1;
            transform: translateX(0) scale(1);
            filter: blur(0);
            pointer-events: auto;
            visibility: visible;
            position: relative;
            z-index: 2;
        }

        .exiting-to-left {
            opacity: 0;
            transform: translate3d(var(--exit-prev-x), var(--exit-prev-y), 0) scale(0.96);
            filter: blur(8px);
            pointer-events: none;
            z-index: 1;
        }

        .exiting-to-right {
            opacity: 0;
            transform: translate3d(var(--exit-next-x), var(--exit-next-y), 0) scale(0.96);
            filter: blur(8px);
            pointer-events: none;
            z-index: 1;
        }

        .enter-from-right {
            opacity: 0;
            transform: translate3d(var(--enter-next-x), var(--enter-next-y), 0) scale(0.97);
            filter: blur(10px);
            visibility: visible;
            pointer-events: none;
        }

        .enter-from-left {
            opacity: 0;
            transform: translate3d(var(--enter-prev-x), var(--enter-prev-y), 0) scale(0.97);
            filter: blur(10px);
            visibility: visible;
            pointer-events: none;
        }

        @keyframes slideInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section.active-form .form-content {
            animation: fadeInContent 0.8s ease-out;
        }

        @keyframes fadeInContent {
            0% {
                opacity: 0;
                transform: translate3d(var(--content-enter-x), var(--content-enter-y), 0);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (min-width: 768px) {
            .form-container {
                --hidden-shift-x: 56px;
                --hidden-shift-y: 0px;
                --exit-prev-x: -76px;
                --exit-prev-y: 0px;
                --exit-next-x: 76px;
                --exit-next-y: 0px;
                --enter-next-x: 96px;
                --enter-next-y: 0px;
                --enter-prev-x: -96px;
                --enter-prev-y: 0px;
                --content-enter-x: -18px;
                --content-enter-y: 0px;
            }
        }

        /* เพิ่มเอฟเฟกต์ hover ให้ปุ่ม */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* เพิ่ม animation ให้ icon */
        .float-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        /* เพิ่ม shimmer effect */
        .shimmer-effect {
            position: relative;
            overflow: hidden;
        }

        .shimmer-effect::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) rotate(30deg);
            }

            100% {
                transform: translateX(100%) rotate(30deg);
            }
        }

        /* เพิ่ม pulse animation สำหรับปุ่ม GPS */
        .pulse-effect {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(56, 189, 248, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(56, 189, 248, 0);
            }
        }

        .form-swap-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            border-radius: inherit;
            z-index: 0;
        }

        .form-swap-glow::before {
            content: '';
            position: absolute;
            inset: 18% 22%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0) 72%);
            opacity: 0;
            transform: scale(0.75);
            transition: opacity 0.45s ease, transform 0.55s ease;
        }

        .form-container.is-switching .form-swap-glow::before {
            opacity: 1;
            transform: scale(1.25);
        }

        .ripple-burst {
            position: absolute;
            width: 24px;
            height: 24px;
            left: 50%;
            top: 50%;
            border-radius: 9999px;
            transform: translate(-50%, -50%) scale(0.2);
            background: radial-gradient(circle, rgba(255, 255, 255, 0.65) 0%, rgba(255, 255, 255, 0.18) 45%, rgba(255, 255, 255, 0) 72%);
            animation: rippleBurst 0.75s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            pointer-events: none;
        }

        @keyframes rippleBurst {
            0% {
                opacity: 0.9;
                transform: translate(-50%, -50%) scale(0.2);
            }

            100% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(18);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .washly-spectrum-bg::before,
            .float-icon,
            .shimmer-effect::after,
            .pulse-effect,
            .form-section,
            .form-container,
            .form-swap-glow::before,
            .ripple-burst {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body class="washly-spectrum-bg min-h-screen flex items-center justify-center p-4 overflow-x-hidden">

    @php
        $showRegister =
            session('line_id') || $errors->has('fullname') || $errors->has('phone') || $errors->has('address');
    @endphp

    <div id="auth-card"
        class="form-container bg-white/95 backdrop-blur-md p-8 rounded-[30px] w-full max-w-lg border border-white/50 shadow-2xl relative overflow-hidden">
        <div class="form-swap-glow"></div>

        <!-- วงกลมตกแต่ง -->
        <div
            class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-sky-200 to-pink-200 rounded-full opacity-30 blur-3xl">
        </div>
        <div
            class="absolute -bottom-20 -left-20 w-40 h-40 bg-gradient-to-tr from-blue-200 to-purple-200 rounded-full opacity-30 blur-3xl">
        </div>

        <div class="form-stage">
            <div id="login-section" class="form-section w-full {{ $showRegister ? 'hidden-form' : 'active-form' }}">
                <div class="form-content">
                    <div class="text-center mb-8">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-sky-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner float-icon p-2">
                            <img src="{{ asset('logowashly.png') }}" alt="Washly Logo"
                                class="w-full h-full object-contain">
                        </div>
                        <h2
                            class="text-3xl font-bold bg-gradient-to-r from-sky-600 to-blue-600 bg-clip-text text-transparent">
                            ยินดีต้อนรับกลับมา!</h2>
                        <p class="text-sm text-gray-500 mt-2">ล็อกอินเพื่อจองคิวรับ-ส่งผ้ากันเถอะ</p>
                    </div>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white text-gray-400 rounded-full">เข้าสู่ระบบด้วยบัญชีทั่วไป</span>
                        </div>
                    </div>

                    <form action="{{ route('login') }}" method="POST" class="space-y-5">
                        @csrf
                        @if ($errors->any() && !$showRegister)
                            <div
                                class="bg-red-50 text-red-500 text-sm p-3 rounded-2xl border border-red-200 text-center animate-pulse">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="group">
                            <label
                                class="block text-sm font-medium mb-2 pl-2 text-gray-700 group-focus-within:text-sky-600 transition-colors">ชื่อผู้ใช้งาน
                                (Username)</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                placeholder="ใส่ชื่อผู้ใช้งานของคุณ" required autocomplete="off"
                                class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-sky-300 focus:ring-4 focus:ring-sky-100 outline-none transition-all">
                        </div>

                        <div class="group">
                            <label
                                class="block text-sm font-medium mb-2 pl-2 text-gray-700 group-focus-within:text-sky-600 transition-colors">รหัสผ่าน
                                (Password)</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-sky-300 focus:ring-4 focus:ring-sky-100 outline-none transition-all">
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-sky-400 to-blue-500 text-white font-medium py-3.5 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 duration-200 mt-4 text-lg hover-lift shimmer-effect">
                            เข้าสู่ระบบ
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-8">
                        ยังไม่มีบัญชีใช่ไหม?
                        <button type="button" onclick="toggleForms('register')"
                            class="text-pink-500 font-bold hover:underline transition-all hover:text-pink-600 transform hover:scale-105 inline-block">
                            สมัครสมาชิกที่นี่เลย
                        </button>
                    </p>
                </div>
            </div>

            <div id="register-section" class="form-section w-full {{ $showRegister ? 'active-form' : 'hidden-form' }}">
                <div class="form-content">
                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-pink-100 to-rose-100 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl float-icon">
                            
                        </div>
                        <h2
                            class="text-3xl font-bold bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text text-transparent">
                            มาเป็นครอบครัวเดียวกัน!</h2>
                        <p class="text-sm text-gray-500 mt-2">กรอกข้อมูลนิดเดียว ก็พร้อมเรียกไรเดอร์มารับผ้าแล้ว</p>
                    </div>

                    @if (session('line_id'))
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center gap-4 shadow-sm hover-lift">
                            <img src="{{ session('line_avatar') }}" alt="LINE Profile"
                                class="w-14 h-14 rounded-full shadow-md border-2 border-white">
                            <div>
                                <p class="text-xs text-green-600 font-medium mb-0.5">กำลังเชื่อมต่อกับบัญชี LINE</p>
                                <p class="text-green-800 font-bold text-lg">{{ session('line_name') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="space-y-4">
                        @csrf

                        @if ($errors->any() && $showRegister)
                            <div
                                class="bg-red-50 text-red-500 text-sm p-3 rounded-2xl border border-red-200 animate-pulse">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="group">
                            <label
                                class="block text-sm font-medium mb-1 pl-2 text-gray-700 group-focus-within:text-pink-600 transition-colors">ชื่อ-นามสกุล</label>
                            <input type="text" name="fullname" value="{{ old('fullname', session('line_name')) }}"
                                required
                                class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-pink-300 focus:ring-4 focus:ring-pink-100 outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="group">
                                <label
                                    class="block text-sm font-medium mb-1 pl-2 text-gray-700 group-focus-within:text-pink-600 transition-colors">ชื่อผู้ใช้งาน</label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    {{ session('line_id') ? '' : 'required' }}
                                    placeholder="{{ session('line_id') ? 'ตั้งหรือไม่ตั้งก็ได้' : '' }}"
                                    class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-pink-300 focus:ring-4 focus:ring-pink-100 outline-none transition-all">
                            </div>
                            <div class="group">
                                <label
                                    class="block text-sm font-medium mb-1 pl-2 text-gray-700 group-focus-within:text-pink-600 transition-colors">เบอร์โทรศัพท์</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-pink-300 focus:ring-4 focus:ring-pink-100 outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1 pl-2 pr-1">
                                <label class="block text-sm font-medium text-gray-700">ที่อยู่หลัก / หอพัก /
                                    เลขห้อง</label>
                                <button type="button" onclick="getLocation()" id="gps-btn"
                                    class="text-xs bg-sky-100 text-sky-700 hover:bg-sky-200 px-3 py-1.5 rounded-full font-medium transition-all transform hover:scale-105 flex items-center gap-1 shadow-sm pulse-effect">
                                    <i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุด
                                        GPS</span>
                                </button>
                            </div>

                            <textarea id="address" name="address" rows="2" required
                                placeholder="กรอกที่อยู่ หรือกดปักหมุด GPS ให้ระบบช่วยดึงที่อยู่..."
                                class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:bg-white focus:border-pink-300 focus:ring-4 focus:ring-pink-100 outline-none transition-all resize-none">{{ old('address') }}</textarea>

                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                            <input type="hidden" id="map_link" name="map_link" value="{{ old('map_link') }}">
                        </div>

                        @if (!session('line_id'))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                                <div class="group">
                                    <label
                                        class="block text-sm font-medium mb-1 pl-2 text-gray-700 group-focus-within:text-pink-600 transition-colors">รหัสผ่าน</label>
                                    <input type="password" name="password" required
                                        class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-pink-300 focus:ring-4 focus:ring-pink-100 outline-none transition-all">
                                </div>
                                <div class="group">
                                    <label
                                        class="block text-sm font-medium mb-1 pl-2 text-gray-700 group-focus-within:text-pink-600 transition-colors">ยืนยันรหัสผ่าน</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full px-5 py-3 rounded-full bg-gray-50 border-2 border-transparent focus:bg-white focus:border-pink-300 focus:ring-4 focus:ring-pink-100 outline-none transition-all">
                                </div>
                            </div>
                        @endif

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-pink-400 to-rose-400 text-white font-medium py-3.5 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 mt-4 text-lg hover-lift shimmer-effect">
                            สมัครสมาชิกเลย! ✨
                        </button>
                    </form>

                    <p class="text-center text-sm text-gray-500 mt-6">
                        มีบัญชีอยู่แล้วใช่ไหม?
                        <button type="button" onclick="toggleForms('login')"
                            class="text-sky-600 font-bold hover:underline transition-all hover:text-sky-700 transform hover:scale-105 inline-block">
                            เข้าสู่ระบบที่นี่
                        </button>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>
        const TRANSITION_DURATION = 550;

        function syncContainerHeight() {
            const authCard = document.getElementById('auth-card');
            const activeSection = document.querySelector('.form-section.active-form');

            if (!authCard || !activeSection) {
                return;
            }

            const nextHeight = activeSection.offsetHeight + 64;
            authCard.style.height = `${nextHeight}px`;
        }

        function setSectionState(section, classesToAdd, classesToRemove = []) {
            section.classList.remove(...classesToRemove);
            section.classList.add(...classesToAdd);
        }

        // ฟังก์ชันสลับการ์ด Login <-> Register
        function toggleForms(target) {
            const authCard = document.getElementById('auth-card');
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');
            const targetSection = target === 'register' ? registerSection : loginSection;
            const currentSection = target === 'register' ? loginSection : registerSection;
            const enterClass = target === 'register' ? 'enter-from-right' : 'enter-from-left';
            const exitClass = target === 'register' ? 'exiting-to-left' : 'exiting-to-right';

            if (!authCard || currentSection === targetSection || targetSection.classList.contains('active-form')) {
                return;
            }

            authCard.classList.add('is-switching');

            targetSection.classList.remove('hidden-form', 'active-form', 'exiting-to-left', 'exiting-to-right');
            targetSection.classList.add(enterClass);

            requestAnimationFrame(() => {
                syncContainerHeight();
                createRippleEffect(authCard);

                requestAnimationFrame(() => {
                    setSectionState(currentSection, [exitClass], ['active-form', 'enter-from-left',
                        'enter-from-right'
                    ]);
                    setSectionState(targetSection, ['active-form'], [enterClass]);
                    syncContainerHeight();
                });
            });

            window.setTimeout(() => {
                currentSection.classList.remove('exiting-to-left', 'exiting-to-right', 'active-form');
                currentSection.classList.add('hidden-form');
                targetSection.classList.remove('enter-from-left', 'enter-from-right');
                authCard.classList.remove('is-switching');
                syncContainerHeight();
            }, TRANSITION_DURATION);
        }

        // ฟังก์ชันสร้างเอฟเฟกต์ ripple
        function createRippleEffect(element) {
            const ripple = document.createElement('div');
            ripple.className = 'ripple-burst';

            element.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 750);
        }

        window.addEventListener('load', syncContainerHeight);
        window.addEventListener('resize', syncContainerHeight);

        function showAuthAlert(options) {
            Swal.fire({
                confirmButtonColor: '#1980d5',
                confirmButtonText: 'รับทราบ',
                customClass: {
                    popup: 'rounded-3xl'
                },
                ...options,
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const showingRegister = @json((bool) $showRegister);

            @if (session('success'))
                showAuthAlert({
                    title: 'สำเร็จ',
                    text: @json(session('success')),
                    icon: 'success'
                });
            @endif

            @if (session('info'))
                showAuthAlert({
                    title: 'แจ้งให้ทราบ',
                    text: @json(session('info')),
                    icon: 'info'
                });
            @endif

            @if ($errors->any())
                showAuthAlert({
                    title: showingRegister ? 'สมัครสมาชิกไม่สำเร็จ' : 'เข้าสู่ระบบไม่สำเร็จ',
                    text: @json($errors->first()),
                    icon: 'error'
                });
            @endif
        });

        // ฟังก์ชันดึง GPS (เหมือนเดิม)
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
                gpsBtn.classList.remove('pulse-effect');

                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const mapLink = `https://www.google.com/maps?q=${lat},${lng}`;

                            latInput.value = lat;
                            lngInput.value = lng;
                            mapLinkInput.value = mapLink;

                            try {
                                const response = await fetch(
                                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=th`
                                    );
                                const data = await response.json();
                                if (data && data.display_name) {
                                    addressInput.value = data.display_name;
                                    addressInput.classList.add('bg-green-50');
                                } else {
                                    addressInput.value = `พิกัด GPS: ${mapLink}`;
                                }
                            } catch (error) {
                                addressInput.value = `พิกัด GPS: ${mapLink}`;
                            }

                            gpsBtn.innerHTML =
                                '<i class="fa-solid fa-check text-green-600"></i> <span id="gps-text">สำเร็จ!</span>';
                            gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-sky-100', 'text-sky-700');
                            gpsBtn.classList.add('bg-green-100', 'text-green-600', 'hover:bg-green-200');

                            setTimeout(() => {
                                gpsBtn.innerHTML =
                                    '<i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุด GPS</span>';
                                gpsBtn.classList.remove('bg-green-100', 'text-green-600', 'hover:bg-green-200');
                                gpsBtn.classList.add('bg-sky-100', 'text-sky-700', 'hover:bg-sky-200',
                                    'pulse-effect');
                                addressInput.classList.remove('bg-green-50');
                            }, 3000);
                        },
                        function(error) {
                            alert('ไม่สามารถดึงตำแหน่งได้ กรุณาอนุญาตให้เบราว์เซอร์เข้าถึง Location ของคุณครับ');
                            gpsBtn.innerHTML =
                                '<i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุด GPS</span>';
                            gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                            gpsBtn.classList.add('pulse-effect');
                        }
                );
            } else {
                alert("เบราว์เซอร์ของคุณไม่รองรับการปักหมุด GPS ครับ");
            }
        }

        document.getElementById('address')?.addEventListener('input', function() {
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('map_link').value = '';
        });
    </script>
</body>

</html>
