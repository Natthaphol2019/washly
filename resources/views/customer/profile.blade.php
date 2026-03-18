@extends('layouts.customer')

@section('title', 'โปรไฟล์ของฉัน - Washly')

@section('content')
    <div class="max-w-2xl mx-auto pb-10">
        
        <div class="text-center mb-8 pt-4">
            <div class="inline-block bg-blue-100 dark:bg-slate-700 text-blue-500 dark:text-blue-400 p-4 rounded-full mb-4 shadow-sm">
                <i class="fa-solid fa-user-pen text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">โปรไฟล์ของฉัน</h2>
            <p class="text-gray-500 dark:text-gray-400">ตั้งค่าที่อยู่หลัก เพื่อความรวดเร็วในการจองคิวครั้งต่อไป</p>
        </div>
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 p-4 rounded-xl mb-6 shadow-sm flex items-center gap-3 transition-colors">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-[30px] p-6 md:p-10 shadow-lg border border-pink-50 dark:border-slate-700 transition-colors mb-6">
            
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" value="{{ Auth::user()->fullname }}" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-blue-400 focus:ring-0 outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="tel" name="phone" value="{{ Auth::user()->phone }}" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-blue-400 focus:ring-0 outline-none transition-colors">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ที่อยู่หลักสำหรับรับ-ส่งผ้า
                        </label>
                        <button type="button" onclick="getLocation()" id="gps-btn" class="shrink-0 bg-blue-100 dark:bg-slate-700 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-slate-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2 shadow-sm w-full sm:w-auto">
                            <i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุดตำแหน่งปัจจุบัน</span>
                        </button>
                    </div>

                    <textarea id="pickup_address" name="address" rows="4" required
                              placeholder="บ้านเลขที่, ซอย, ถนน, ตำบล..."
                              class="w-full p-4 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-blue-400 focus:ring-0 outline-none transition-colors resize-none">{{ Auth::user()->address }}</textarea>

                    <input type="hidden" id="latitude" name="latitude" value="{{ Auth::user()->latitude }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ Auth::user()->longitude }}">
                    <input type="hidden" id="map_link" name="map_link" value="{{ Auth::user()->map_link }}">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-lg font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> บันทึกข้อมูลโปรไฟล์
                    </button>
                </div>
            </form>
        </div>

        {{-- 🔐 ส่วนเปลี่ยนรหัสผ่าน --}}
        <div class="bg-white dark:bg-slate-800 rounded-[30px] p-6 md:p-10 shadow-lg border border-pink-50 dark:border-slate-700 transition-colors">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-md">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">เปลี่ยนรหัสผ่าน</h3>
            </div>

            <form action="{{ route('customer.profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">รหัสผ่านปัจจุบัน</label>
                    <input type="password" name="current_password" required
                           placeholder="กรอกรหัสผ่านปัจจุบัน"
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-emerald-400 focus:ring-0 outline-none transition-colors">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">รหัสผ่านใหม่</label>
                        <input type="password" name="new_password" id="new_password" required minlength="8"
                               placeholder="อย่างน้อย 8 ตัวอักษร"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-emerald-400 focus:ring-0 outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required minlength="8"
                               placeholder="กรอกอีกครั้งเพื่อยืนยัน"
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-emerald-400 focus:ring-0 outline-none transition-colors">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-lg font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <i class="fa-solid fa-key mr-2"></i> เปลี่ยนรหัสผ่าน
                    </button>
                </div>
            </form>
        </div>

        <div class="md:hidden mt-8 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-500 hover:text-red-700 font-medium py-3 px-6 rounded-full border border-red-200 bg-red-50 transition-colors w-full">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> ออกจากระบบ (Logout)
                </button>
            </form>
        </div>

    </div>

    <script>
        function getLocation() {
            const gpsBtn = document.getElementById('gps-btn');
            const gpsText = document.getElementById('gps-text');
            const addressInput = document.getElementById('pickup_address');
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const mapLinkInput = document.getElementById('map_link');

            if (navigator.geolocation) {
                gpsText.innerText = "กำลังค้นหาพิกัด...";
                gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span id="gps-text">กำลังค้นหาพิกัด...</span>';
                gpsBtn.classList.add('opacity-75', 'cursor-not-allowed');

                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const mapLink = `https://maps.google.com/?q=${lat},${lng}`;
                        
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

                        gpsBtn.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> <span id="gps-text" class="text-green-600 dark:text-green-400">ปักหมุดสำเร็จ!</span>';
                        gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        gpsBtn.classList.replace('bg-blue-100', 'bg-green-100');
                        gpsBtn.classList.replace('text-blue-600', 'text-green-600');
                    }, 
                    function(error) {
                        alert('ไม่สามารถดึงตำแหน่งได้ กรุณาอนุญาตให้เบราว์เซอร์เข้าถึง Location ของคุณครับ');
                        gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุดตำแหน่งปัจจุบัน</span>';
                        gpsBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    }
                );
            } else {
                alert("เบราว์เซอร์ของคุณไม่รองรับการปักหมุด GPS ครับ");
            }
        }

        document.getElementById('pickup_address')?.addEventListener('input', function () {
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('map_link').value = '';
        });
    </script>
@endsection