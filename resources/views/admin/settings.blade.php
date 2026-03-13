@extends('layouts.admin')

@section('title', 'ตั้งค่าระบบจัดส่ง - Washly Admin')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="max-w-5xl mx-auto w-full flex flex-col gap-6 pb-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-sliders text-sky-500 mr-2"></i> ตั้งค่าระบบจัดส่ง
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">ปรับเงื่อนไขค่าส่งและจัดการ backfill ระยะทางของออเดอร์เก่า</p>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-2xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-2xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">กติกาคำนวณค่าส่ง</h2>

                <form action="{{ route('admin.settings.delivery.update') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="mb-5 bg-slate-50 dark:bg-slate-800/80 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                        <label class="block text-sm font-bold text-sky-600 dark:text-sky-400 mb-2">
                            <i class="fa-solid fa-map-location-dot"></i> ค้นหาพิกัดด่วน (วางลิงก์แผนที่ หรือ Lat, Lng)
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" id="quick_map_input" placeholder="เช่น 14.0366133, 100.6558354 หรือ วางลิงก์ Google Maps ฉบับเต็ม"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-2 outline-none focus:border-sky-400 text-sm text-gray-700 dark:text-gray-200">
                            <button type="button" onclick="extractCoordinates()"
                                class="shrink-0 bg-slate-800 hover:bg-slate-900 dark:bg-slate-600 dark:hover:bg-slate-500 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                                ดึงพิกัด
                            </button>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5">* รองรับตัวเลขพิกัดตรงๆ หรือก๊อปปี้ URL ของ Google Maps (ที่มี /@14.xx,100.xx) มาวางได้เลย</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดร้าน Latitude</label>
                            <input type="number" step="any" min="-90" max="90" name="shop_latitude" value="{{ old('shop_latitude', $deliverySettings['shop_latitude']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-700 dark:text-gray-200">
                            @error('shop_latitude')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดร้าน Longitude</label>
                            <input type="number" step="any" min="-180" max="180" name="shop_longitude" value="{{ old('shop_longitude', $deliverySettings['shop_longitude']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-700 dark:text-gray-200">
                            @error('shop_longitude')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ฟรีระยะทางแรก (กม.)</label>
                            <input type="number" step="0.1" min="0" name="free_radius_km" value="{{ old('free_radius_km', $deliverySettings['free_radius_km']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-700 dark:text-gray-200">
                            @error('free_radius_km')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ค่าบริการต่อกม. (บาท)</label>
                            <input type="number" step="0.01" min="0" name="rate_per_km" value="{{ old('rate_per_km', $deliverySettings['rate_per_km']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-700 dark:text-gray-200">
                            @error('rate_per_km')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-2xl bg-sky-50 dark:bg-slate-900/40 border border-sky-100 dark:border-slate-700 p-4 text-sm text-sky-900 dark:text-sky-100">
                        <p class="font-semibold mb-2">พิกัดร้านจากระบบ</p>
                        <p>Latitude: {{ $shopCoordinates['latitude'] ?? 'ยังไม่ได้ตั้งค่า' }}</p>
                        <p>Longitude: {{ $shopCoordinates['longitude'] ?? 'ยังไม่ได้ตั้งค่า' }}</p>
                        <p class="mt-2 text-sky-700 dark:text-sky-300">ตอนนี้ทั้งพิกัดร้านและกติกาค่าส่งแก้ได้จากหน้านี้แล้ว โดยระบบจะใช้ค่าจากฐานข้อมูลก่อนค่าใน .env</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-3 rounded-xl shadow-sm transition-colors">
                            <i class="fa-solid fa-floppy-disk"></i> บันทึกกติกาค่าส่ง
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 space-y-5">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">Backfill ออเดอร์เก่า</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">เติมค่า distance ให้รายการเก่าที่มี GPS แล้วแต่ยังไม่เคยบันทึกระยะทางขับรถ</p>
                </div>

                <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 p-4">
                    <p class="text-sm text-amber-900 dark:text-amber-200">ออเดอร์ที่ยังรอ backfill</p>
                    <p class="text-3xl font-black text-amber-600 dark:text-amber-300 mt-1">{{ number_format($missingDistanceOrders) }}</p>
                </div>

                <form action="{{ route('admin.settings.delivery.backfill') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-3 rounded-xl shadow-sm transition-colors">
                        <i class="fa-solid fa-route"></i> รัน Backfill ระยะทาง
                    </button>
                </form>

                <div class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                    ระบบจะอัปเดตเฉพาะออเดอร์ที่มีพิกัดรับผ้าอยู่แล้วและยังไม่มีค่า distance เท่านั้น โดยจะไม่แก้ยอดเงินย้อนหลัง
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 space-y-5">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">แผนที่ลูกค้า</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">กรอกพิกัดลูกค้าเพื่อดูตำแหน่งบนแผนที่</p>
            </div>

            <form id="delivery-preview-form" class="space-y-5">
                @csrf
                
                <div class="bg-slate-50 dark:bg-slate-800/80 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                    <label class="block text-sm font-bold text-pink-600 dark:text-pink-400 mb-2">
                        <i class="fa-solid fa-map-pin"></i> ค้นหาพิกัดลูกค้าด่วน (วางลิงก์แผนที่ หรือ Lat, Lng)
                    </label>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" id="quick_test_map_input" placeholder="เช่น 14.040000, 100.660000 หรือ วางลิงก์ Google Maps"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-2 outline-none focus:border-pink-400 text-sm text-gray-700 dark:text-gray-200">
                        <button type="button" onclick="extractTestCoordinates()"
                            class="shrink-0 bg-slate-800 hover:bg-slate-900 dark:bg-slate-600 dark:hover:bg-slate-500 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                            ดึงพิกัดลงช่องด้านล่าง
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดลูกค้า Latitude</label>
                        <input type="number" step="any" min="-90" max="90" name="latitude" value="14.040000"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-700 dark:text-gray-200">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดลูกค้า Longitude</label>
                        <input type="number" step="any" min="-180" max="180" name="longitude" value="100.660000"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-700 dark:text-gray-200">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" id="update-map-btn" class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white font-semibold px-5 py-3 rounded-xl shadow-sm transition-colors">
                        <i class="fa-solid fa-map"></i> อัปเดตแผนที่
                    </button>
                    <p id="map-status" class="text-sm text-gray-500 dark:text-gray-400">พร้อมใช้งาน</p>
                </div>
            </form>

            <div class="rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden bg-gray-50 dark:bg-slate-900/40">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">แผนที่ตำแหน่งลูกค้า</p>
                    <a id="preview-route-link" href="#" target="_blank" class="text-sm text-sky-600 dark:text-sky-300 hover:underline">
                        เปิดใน Google Maps
                    </a>
                </div>
                <div id="delivery-preview-map" class="w-full h-[360px] bg-slate-100 dark:bg-slate-800"></div>
                <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-700 text-xs text-gray-500 dark:text-gray-400">
                    <span class="font-semibold text-sky-600 dark:text-sky-300">ร้าน</span> และ
                    <span class="font-semibold text-pink-600 dark:text-pink-300">ลูกค้า</span> จะแสดงด้วย marker แยกกันบนแผนที่
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // 🚀 ฟังก์ชันดึงพิกัดจากลิงก์สำหรับร้านค้า
        window.extractCoordinates = function() {
            const input = document.getElementById('quick_map_input').value.trim();
            const shopLatInput = document.querySelector('input[name="shop_latitude"]');
            const shopLngInput = document.querySelector('input[name="shop_longitude"]');
            
            let lat, lng;
            const regexAt = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
            const regexComma = /(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/;

            if (regexAt.test(input)) {
                const match = input.match(regexAt);
                lat = match[1];
                lng = match[2];
            } else if (regexComma.test(input)) {
                const match = input.match(regexComma);
                lat = match[1];
                lng = match[2];
            }

            if (lat && lng) {
                shopLatInput.value = lat;
                shopLngInput.value = lng;
                alert('✅ ดึงพิกัดสำเร็จ!\nLatitude: ' + lat + '\nLongitude: ' + lng);
                document.getElementById('quick_map_input').value = '';
            } else {
                alert('❌ ไม่พบพิกัด\nกรุณาวางตัวเลขพิกัด (Lat, Lng) หรือลิงก์ Google Maps ฉบับเต็มที่มีเครื่องหมาย /@พิกัด อยู่ในลิงก์');
            }
        };

        // 🚀 ฟังก์ชันดึงพิกัดจากลิงก์สำหรับทดสอบลูกค้า
        window.extractTestCoordinates = function() {
            const input = document.getElementById('quick_test_map_input').value.trim();
            const form = document.getElementById('delivery-preview-form');
            const testLatInput = form.querySelector('input[name="latitude"]');
            const testLngInput = form.querySelector('input[name="longitude"]');
            
            let lat, lng;
            const regexAt = /@(-?\d+\.\d+),(-?\d+\.\d+)/;
            const regexComma = /(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/;

            if (regexAt.test(input)) {
                const match = input.match(regexAt);
                lat = match[1];
                lng = match[2];
            } else if (regexComma.test(input)) {
                const match = input.match(regexComma);
                lat = match[1];
                lng = match[2];
            }

            if (lat && lng) {
                testLatInput.value = lat;
                testLngInput.value = lng;
                testLatInput.dispatchEvent(new Event('input'));
                testLngInput.dispatchEvent(new Event('input'));
                alert('✅ ดึงพิกัดลูกค้าสำหรับทดสอบสำเร็จ!');
                document.getElementById('quick_test_map_input').value = '';
            } else {
                alert('❌ ไม่พบพิกัด\nกรุณาวางตัวเลขพิกัด (Lat, Lng) หรือลิงก์ Google Maps ฉบับเต็ม');
            }
        };

        (function () {
            const form = document.getElementById('delivery-preview-form');
            const updateMapBtn = document.getElementById('update-map-btn');
            const statusEl = document.getElementById('map-status');
            const mapContainer = document.getElementById('delivery-preview-map');
            const routeLink = document.getElementById('preview-route-link');
            const latitudeInput = form?.querySelector('input[name="latitude"]');
            const longitudeInput = form?.querySelector('input[name="longitude"]');
            const shopLatitude = parseFloat(@json($shopCoordinates['latitude']));
            const shopLongitude = parseFloat(@json($shopCoordinates['longitude']));
            
            let map = null;
            let shopMarker = null;
            let customerMarker = null;

            if (!form || !updateMapBtn || !latitudeInput || !longitudeInput) {
                return;
            }

            if (mapContainer && window.L) {
                const initialLat = Number(shopLatitude || 14.0366133);
                const initialLng = Number(shopLongitude || 100.6558354);
                map = L.map(mapContainer).setView([initialLat, initialLng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
            }

            const shopIcon = window.L ? L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background:#0ea5e9;color:#fff;width:34px;height:34px;border-radius:9999px;display:flex;align-items:center;justify-content:center;border:3px solid #fff;box-shadow:0 8px 20px rgba(14,165,233,.3);"><i class="fa-solid fa-store"></i></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 17],
            }) : null;

            const customerIcon = window.L ? L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="background:#ec4899;color:#fff;width:34px;height:34px;border-radius:9999px;display:flex;align-items:center;justify-content:center;border:3px solid #fff;box-shadow:0 8px 20px rgba(236,72,153,.3);"><i class="fa-solid fa-user"></i></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 17],
            }) : null;

            function updateMapPreview(customerLatitude, customerLongitude, customerLabel = 'จุดลูกค้า') {
                if (!routeLink || !map) {
                    return;
                }

                // Clear existing markers
                if (shopMarker) {
                    map.removeLayer(shopMarker);
                }
                if (customerMarker) {
                    map.removeLayer(customerMarker);
                }

                // Add shop marker
                if (shopLatitude && shopLongitude) {
                    shopMarker = L.marker([shopLatitude, shopLongitude], { icon: shopIcon || undefined })
                        .addTo(map)
                        .bindPopup('ร้าน Washly');
                }

                // Add customer marker
                if (customerLatitude && customerLongitude) {
                    customerMarker = L.marker([customerLatitude, customerLongitude], { icon: customerIcon || undefined })
                        .addTo(map)
                        .bindPopup(customerLabel);

                    // Generate Google Maps link
                    const routeUrl = `https://www.google.com/maps/search/?api=1&query=${customerLatitude},${customerLongitude}`;
                    routeLink.href = routeUrl;

                    // Fit map to show both markers
                    if (shopLatitude && shopLongitude) {
                        const bounds = L.latLngBounds([
                            [shopLatitude, shopLongitude],
                            [customerLatitude, customerLongitude]
                        ]);
                        map.fitBounds(bounds, { padding: [30, 30] });
                    } else {
                        map.setView([customerLatitude, customerLongitude], 14);
                    }

                    statusEl.textContent = 'อัปเดตแผนที่แล้ว';
                } else {
                    routeLink.href = '#';
                    if (shopLatitude && shopLongitude) {
                        map.setView([shopLatitude, shopLongitude], 14);
                    }
                    statusEl.textContent = 'กรุณาใส่พิกัดลูกค้า';
                }
            }

            // Event listeners
            updateMapBtn.addEventListener('click', function () {
                const lat = latitudeInput.value;
                const lng = longitudeInput.value;
                updateMapPreview(lat, lng);
            });

            latitudeInput.addEventListener('input', () => {
                const lat = latitudeInput.value;
                const lng = longitudeInput.value;
                updateMapPreview(lat, lng);
            });

            longitudeInput.addEventListener('input', () => {
                const lat = latitudeInput.value;
                const lng = longitudeInput.value;
                updateMapPreview(lat, lng);
            });

            // Initial load
            updateMapPreview(latitudeInput.value, longitudeInput.value);
        })();
    </script>
@endsection