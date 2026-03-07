<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - บริการรับส่งซักผ้า</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css'])
</head>
<body class="washly-spectrum-bg washly-shell min-h-screen flex items-center justify-center p-4">

    <div class="washly-card p-8 rounded-[30px] w-full max-w-lg border my-8">

        <div class="text-center mb-6">
            <h2 class="text-2xl font-medium washly-brand-text">มาเป็นครอบครัวเดียวกัน! 🌸</h2>
            <p class="text-sm text-gray-500 mt-2">กรอกข้อมูลนิดเดียว ก็พร้อมเรียกไรเดอร์มารับผ้าแล้ว</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 text-red-500 text-sm p-3 rounded-2xl border border-red-200">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium mb-1 pl-2">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" value="{{ old('fullname') }}" required class="w-full px-5 py-3 rounded-full washly-input transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 pl-2">ชื่อผู้ใช้งาน (Username)</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-5 py-3 rounded-full washly-input transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 pl-2">เบอร์โทรศัพท์</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-5 py-3 rounded-full washly-input transition-all">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1 pl-2 pr-1">
                    <label class="block text-sm font-medium">ที่อยู่หลัก / หอพัก / เลขห้อง</label>
                    <button type="button" onclick="getLocation()" id="gps-btn" class="text-xs bg-sky-100 text-sky-700 hover:bg-sky-200 px-3 py-1.5 rounded-full font-medium transition-colors flex items-center gap-1 shadow-sm">
                        <i class="fa-solid fa-location-crosshairs"></i> <span id="gps-text">ปักหมุด GPS</span>
                    </button>
                </div>
                
                <textarea id="address" name="address" rows="3" required placeholder="กรอกที่อยู่ หรือกดปักหมุด GPS ให้ระบบช่วยดึงที่อยู่..." class="w-full px-5 py-3 rounded-2xl washly-input transition-all resize-none">{{ old('address') }}</textarea>
                
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                <input type="hidden" id="map_link" name="map_link" value="{{ old('map_link') }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-sm font-medium mb-1 pl-2">รหัสผ่าน</label>
                    <input type="password" name="password" required class="w-full px-5 py-3 rounded-full washly-input transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 pl-2">ยืนยันรหัสผ่าน</label>
                    <input type="password" name="password_confirmation" required class="w-full px-5 py-3 rounded-full washly-input transition-all">
                </div>
            </div>

            <button type="submit" class="w-full washly-brand-btn text-white font-medium py-3 rounded-full shadow-md hover:shadow-lg transition-all mt-4 text-lg">
                สมัครสมาชิกเลย! ✨
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            มีบัญชีอยู่แล้ว? <a href="/login" class="text-sky-700 font-medium hover:underline">เข้าสู่ระบบ</a>
        </p>
    </div>

    <script>
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