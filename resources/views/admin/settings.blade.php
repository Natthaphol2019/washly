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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดร้าน Latitude</label>
                            <input type="number" step="any" min="-90" max="90" name="shop_latitude" value="{{ old('shop_latitude', $deliverySettings['shop_latitude']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400">
                            @error('shop_latitude')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดร้าน Longitude</label>
                            <input type="number" step="any" min="-180" max="180" name="shop_longitude" value="{{ old('shop_longitude', $deliverySettings['shop_longitude']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400">
                            @error('shop_longitude')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ฟรีระยะทางแรก (กม.)</label>
                            <input type="number" step="0.1" min="0" name="free_radius_km" value="{{ old('free_radius_km', $deliverySettings['free_radius_km']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400">
                            @error('free_radius_km')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ค่าบริการต่อกม. (บาท)</label>
                            <input type="number" step="0.01" min="0" name="rate_per_km" value="{{ old('rate_per_km', $deliverySettings['rate_per_km']) }}"
                                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400">
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
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">ทดสอบคำนวณระยะทาง</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">กรอกพิกัดลูกค้าตัวอย่างเพื่อดูระยะทางขับรถ, ระยะเส้นตรง และค่าส่งจากกติกาปัจจุบัน</p>
            </div>

            <form id="delivery-preview-form" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดลูกค้า Latitude</label>
                        <input type="number" step="any" min="-90" max="90" name="latitude" value="14.040000"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">พิกัดลูกค้า Longitude</label>
                        <input type="number" step="any" min="-180" max="180" name="longitude" value="100.660000"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_auto] gap-3 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">เลือกออเดอร์ย้อนหลังที่มีพิกัด</label>
                        <select id="recent-order-select"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:border-sky-400 text-gray-800 dark:text-gray-100">
                            <option value="">เลือกออเดอร์เพื่อเติมพิกัดอัตโนมัติ</option>
                            @foreach($recentOrdersWithCoordinates as $recentOrder)
                                <option
                                    value="{{ $recentOrder->id }}"
                                    data-latitude="{{ $recentOrder->pickup_latitude }}"
                                    data-longitude="{{ $recentOrder->pickup_longitude }}"
                                    data-label="{{ $recentOrder->order_number }} · {{ $recentOrder->user->fullname ?? 'ลูกค้า' }}">
                                    {{ $recentOrder->order_number }} · {{ $recentOrder->user->fullname ?? 'ลูกค้า' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button
                        type="button"
                        id="use-selected-order-coordinates"
                        class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-100 font-medium px-4 py-3 rounded-xl transition-colors">
                        <i class="fa-solid fa-list"></i> ใช้ออเดอร์ที่เลือก
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @if($latestOrderWithCoordinates)
                        <button
                            type="button"
                            id="use-latest-order-coordinates"
                            data-latitude="{{ $latestOrderWithCoordinates->pickup_latitude }}"
                            data-longitude="{{ $latestOrderWithCoordinates->pickup_longitude }}"
                            class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-100 font-medium px-4 py-2 rounded-xl transition-colors">
                            <i class="fa-solid fa-clock-rotate-left"></i> ใช้พิกัดจากออเดอร์ล่าสุด
                        </button>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            ล่าสุด: {{ $latestOrderWithCoordinates->order_number }}
                            โดย {{ $latestOrderWithCoordinates->user->fullname ?? 'ลูกค้า' }}
                        </p>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400">ยังไม่มีออเดอร์ที่มีพิกัดให้ดึงมาใช้ทดสอบ</p>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" id="delivery-preview-submit" class="inline-flex items-center gap-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold px-5 py-3 rounded-xl shadow-sm transition-colors">
                        <i class="fa-solid fa-calculator"></i> ทดสอบคำนวณระยะทาง
                    </button>
                    <p id="delivery-preview-status" class="text-sm text-gray-500 dark:text-gray-400"></p>
                </div>
            </form>

            <div id="delivery-preview-result" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="rounded-2xl border border-blue-100 dark:border-slate-700 bg-blue-50 dark:bg-slate-900/40 p-4">
                    <p class="text-xs uppercase tracking-wider text-blue-600 dark:text-blue-300 font-semibold">ระยะขับรถ</p>
                    <p id="preview-driving-distance" class="mt-2 text-2xl font-black text-gray-800 dark:text-gray-100">-</p>
                </div>
                <div class="rounded-2xl border border-sky-100 dark:border-slate-700 bg-sky-50 dark:bg-slate-900/40 p-4">
                    <p class="text-xs uppercase tracking-wider text-sky-600 dark:text-sky-300 font-semibold">ระยะเส้นตรง</p>
                    <p id="preview-straight-distance" class="mt-2 text-2xl font-black text-gray-800 dark:text-gray-100">-</p>
                </div>
                <div class="rounded-2xl border border-pink-100 dark:border-slate-700 bg-pink-50 dark:bg-slate-900/40 p-4">
                    <p class="text-xs uppercase tracking-wider text-pink-600 dark:text-pink-300 font-semibold">ค่าส่ง</p>
                    <p id="preview-fee" class="mt-2 text-2xl font-black text-gray-800 dark:text-gray-100">-</p>
                </div>
                <div class="rounded-2xl border border-amber-100 dark:border-slate-700 bg-amber-50 dark:bg-slate-900/40 p-4">
                    <p class="text-xs uppercase tracking-wider text-amber-600 dark:text-amber-300 font-semibold">แหล่งข้อมูล</p>
                    <p id="preview-source" class="mt-2 text-xl font-black text-gray-800 dark:text-gray-100">-</p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden bg-gray-50 dark:bg-slate-900/40">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">แผนที่ Preview</p>
                    <a id="preview-route-link" href="https://www.google.com/maps" target="_blank" class="text-sm text-sky-600 dark:text-sky-300 hover:underline">
                        เปิดเส้นทางใน Google Maps
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
        (function () {
            const form = document.getElementById('delivery-preview-form');
            const submitButton = document.getElementById('delivery-preview-submit');
            const statusEl = document.getElementById('delivery-preview-status');
            const resultEl = document.getElementById('delivery-preview-result');
            const latestOrderButton = document.getElementById('use-latest-order-coordinates');
            const selectedOrderButton = document.getElementById('use-selected-order-coordinates');
            const recentOrderSelect = document.getElementById('recent-order-select');
            const mapContainer = document.getElementById('delivery-preview-map');
            const routeLink = document.getElementById('preview-route-link');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const latitudeInput = form?.querySelector('input[name="latitude"]');
            const longitudeInput = form?.querySelector('input[name="longitude"]');
            const shopLatitude = @json($shopCoordinates['latitude']);
            const shopLongitude = @json($shopCoordinates['longitude']);
            let map = null;
            let shopMarker = null;
            let customerMarker = null;
            let routeLine = null;

            if (!form || !submitButton || !statusEl || !resultEl || !latitudeInput || !longitudeInput) {
                return;
            }

            if (mapContainer && window.L) {
                const initialLat = Number(shopLatitude || 14.0366133);
                const initialLng = Number(shopLongitude || 100.6558354);
                map = L.map(mapContainer).setView([initialLat, initialLng], 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
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

            function applySelectedOrderCoordinates() {
                const selectedOption = recentOrderSelect?.selectedOptions?.[0];

                if (!selectedOption || !selectedOption.dataset.latitude || !selectedOption.dataset.longitude) {
                    statusEl.textContent = 'กรุณาเลือกออเดอร์ที่มีพิกัดก่อน';
                    return;
                }

                latitudeInput.value = selectedOption.dataset.latitude;
                longitudeInput.value = selectedOption.dataset.longitude;
                updateMapPreview(latitudeInput.value, longitudeInput.value, selectedOption.dataset.label || 'ออเดอร์ที่เลือก');
                statusEl.textContent = 'เติมพิกัดจากออเดอร์ที่เลือกแล้ว';
            }

            function updateMapPreview(customerLatitude, customerLongitude, customerLabel = 'จุดลูกค้า') {
                if (!routeLink) {
                    return;
                }

                if (!shopLatitude || !shopLongitude || !customerLatitude || !customerLongitude) {
                    if (map && shopLatitude && shopLongitude) {
                        const shopLat = Number(shopLatitude);
                        const shopLng = Number(shopLongitude);

                        if (shopMarker) {
                            map.removeLayer(shopMarker);
                        }
                        if (customerMarker) {
                            map.removeLayer(customerMarker);
                            customerMarker = null;
                        }
                        if (routeLine) {
                            map.removeLayer(routeLine);
                            routeLine = null;
                        }

                        shopMarker = L.marker([shopLat, shopLng], { icon: shopIcon || undefined })
                            .addTo(map)
                            .bindPopup('ร้าน Washly');
                        map.setView([shopLat, shopLng], 14);
                    }
                    routeLink.href = 'https://www.google.com/maps';
                    return;
                }

                const routeUrl = `https://www.google.com/maps/dir/?api=1&origin=${shopLatitude},${shopLongitude}&destination=${customerLatitude},${customerLongitude}&travelmode=driving`;
                routeLink.href = routeUrl;

                if (!map) {
                    return;
                }

                const shopLat = Number(shopLatitude);
                const shopLng = Number(shopLongitude);
                const customerLat = Number(customerLatitude);
                const customerLng = Number(customerLongitude);

                if (shopMarker) {
                    map.removeLayer(shopMarker);
                }
                if (customerMarker) {
                    map.removeLayer(customerMarker);
                }
                if (routeLine) {
                    map.removeLayer(routeLine);
                }

                shopMarker = L.marker([shopLat, shopLng], { icon: shopIcon || undefined })
                    .addTo(map)
                    .bindPopup('ร้าน Washly');

                customerMarker = L.marker([customerLat, customerLng], { icon: customerIcon || undefined })
                    .addTo(map)
                    .bindPopup(customerLabel);

                routeLine = L.polyline([
                    [shopLat, shopLng],
                    [customerLat, customerLng],
                ], {
                    color: '#6366f1',
                    weight: 4,
                    opacity: 0.8,
                    dashArray: '10, 8',
                }).addTo(map);

                map.fitBounds(routeLine.getBounds(), { padding: [30, 30] });
            }

            latestOrderButton?.addEventListener('click', function () {
                latitudeInput.value = latestOrderButton.dataset.latitude || '';
                longitudeInput.value = latestOrderButton.dataset.longitude || '';
                updateMapPreview(latitudeInput.value, longitudeInput.value, 'ออเดอร์ล่าสุด');
                statusEl.textContent = 'เติมพิกัดจากออเดอร์ล่าสุดแล้ว';
            });

            selectedOrderButton?.addEventListener('click', applySelectedOrderCoordinates);
            recentOrderSelect?.addEventListener('change', applySelectedOrderCoordinates);

            latitudeInput.addEventListener('input', () => updateMapPreview(latitudeInput.value, longitudeInput.value));
            longitudeInput.addEventListener('input', () => updateMapPreview(latitudeInput.value, longitudeInput.value));

            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                const formData = new FormData(form);
                submitButton.disabled = true;
                statusEl.textContent = 'กำลังคำนวณ...';
                resultEl.classList.add('hidden');

                try {
                    const response = await fetch(@json(route('admin.settings.delivery.preview')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken || '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        throw new Error('preview-request-failed');
                    }

                    const quote = await response.json();
                    document.getElementById('preview-driving-distance').textContent = `${Number(quote.driving_distance_km || 0).toFixed(2)} กม.`;
                    document.getElementById('preview-straight-distance').textContent = `${Number(quote.straight_line_distance_km || 0).toFixed(2)} กม.`;
                    document.getElementById('preview-fee').textContent = `฿${Number(quote.fee || 0).toLocaleString('th-TH', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}`;
                    document.getElementById('preview-source').textContent = quote.source === 'osrm' ? 'ขับรถจริง' : 'สำรอง';

                    statusEl.textContent = 'คำนวณสำเร็จ';
                    resultEl.classList.remove('hidden');
                    updateMapPreview(formData.get('latitude'), formData.get('longitude'));
                } catch (error) {
                    statusEl.textContent = 'คำนวณไม่สำเร็จ กรุณาตรวจสอบพิกัดแล้วลองใหม่';
                } finally {
                    submitButton.disabled = false;
                }
            });

            updateMapPreview(latitudeInput.value, longitudeInput.value);
        })();
    </script>
@endsection