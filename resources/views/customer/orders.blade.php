@extends('layouts.customer')

@section('title', 'ออเดอร์ของฉัน - Washly')

@section('content')
    <div class="max-w-3xl mx-auto pb-12 px-4 sm:px-6">

        <div class="text-center mb-10 pt-8">
            <div class="inline-flex items-center justify-center bg-purple-100 dark:bg-slate-800 text-purple-500 dark:text-purple-400 w-20 h-20 rounded-full mb-5 shadow-sm hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-clipboard-list text-4xl fa-bounce" style="--fa-animation-duration: 2.5s; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1;"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-gray-100 mb-3 tracking-tight">ออเดอร์ของฉัน</h2>
            <p class="text-gray-500 dark:text-gray-400 text-lg">ติดตามสถานะการซักผ้าของคุณได้ที่นี่ <i class="fa-solid fa-sparkles text-yellow-400 ml-1"></i></p>
        </div>

        <div id="orders-live-region">
            @if ($orders->isEmpty())
                {{-- กรณีไม่มีออเดอร์เลย --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-10 text-center shadow-sm border border-gray-100 dark:border-slate-700 transition-colors mt-6">
                    <div class="animate-bounce" style="animation-duration: 3s;">
                        <i class="fa-solid fa-basket-shopping text-7xl text-gray-200 dark:text-gray-700 mb-6 drop-shadow-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-2">ยังไม่มีประวัติการสั่งซักผ้า</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">ตะกร้าผ้าล้นแล้วหรือยัง? ให้เราจัดการให้สิ!</p>
                    <a href="{{ route('customer.book') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold py-3.5 px-8 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <i class="fa-solid fa-plus mr-2 text-xl"></i> จองคิวเลย
                    </a>
                </div>
            @else
                @php
                    // 🌟 แยกออเดอร์เป็น 2 กลุ่ม: กำลังดำเนินการ VS ประวัติ (เสร็จสิ้น/ยกเลิก)
                    $activeOrders = $orders->filter(function($order) {
                        return !in_array($order->status, ['completed', 'cancelled']);
                    });
                    $historyOrders = $orders->filter(function($order) {
                        return in_array($order->status, ['completed', 'cancelled']);
                    });
                @endphp

                {{-- ============================================= --}}
                {{-- 🔴 1. ออเดอร์ที่กำลังดำเนินการ (Active Orders) --}}
                {{-- ============================================= --}}
                @if($activeOrders->isNotEmpty())
                    <div class="mb-6 px-2">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">กำลังดำเนินการ ({{ $activeOrders->count() }})</h3>
                    </div>
                    <div class="space-y-6">
                        @foreach ($activeOrders as $order)
                            @include('customer.partials.order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
                <br><br>
                {{-- ============================================= --}}
                {{-- ⚪ 2. ประวัติออเดอร์ (History / พับเก็บได้) --}}
                {{-- ============================================= --}}
                @if($historyOrders->isNotEmpty())
                    <details class="group mt-12" {{ $activeOrders->isEmpty() ? 'open' : '' }}>
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                            <span class="text-lg font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> ประวัติการทำรายการ ({{ $historyOrders->count() }})
                            </span>
                            <span class="transition-transform duration-300 group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-500"></i>
                            </span>
                        </summary>
                        <div class="mt-6 space-y-6 opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                            @foreach ($historyOrders as $order)
                                @include('customer.partials.order-card', ['order' => $order, 'isHistory' => true])
                            @endforeach
                        </div>
                    </details>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        let refreshTimer = null;
        let isRefreshing = false;

        async function refreshOrdersSection() {
            if (isRefreshing) return;
            isRefreshing = true;
            try {
                const response = await fetch(window.location.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                });
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const nextRegion = doc.querySelector('#orders-live-region');
                const currentRegion = document.querySelector('#orders-live-region');
                if (nextRegion && currentRegion) {
                    currentRegion.innerHTML = nextRegion.innerHTML;
                } else {
                    window.location.reload();
                }
            } catch (error) {
                window.location.reload();
            } finally {
                isRefreshing = false;
            }
        }

        function scheduleRefresh() {
            if (refreshTimer !== null) return;
            refreshTimer = window.setTimeout(function () {
                refreshTimer = null;
                refreshOrdersSection();
            }, 500);
        }

        window.addEventListener('washly:notification-received', function (event) {
            const payload = event.detail || {};
            const haystack = `${payload.title || ''} ${payload.message || ''} ${payload.url || ''}`.toLowerCase();
            const isOrderRelated = haystack.includes('ออเดอร์') || haystack.includes('สถานะ') || haystack.includes('ชำระ') || haystack.includes('/customer/orders') || haystack.includes('ไรเดอร์');
            if (isOrderRelated) scheduleRefresh();
        });

        // 💳 ฟังก์ชันแสดง QR Code ชำระเงิน
        let qrModal = null;
        
        function showOrderQRCode(orderId, amount, orderNumber) {
            if (!qrModal) {
                qrModal = document.createElement('div');
                qrModal.id = 'order-qr-modal';
                qrModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-[60] hidden items-center justify-center p-4';
                qrModal.innerHTML = `
                    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-[fadeIn_0.2s_ease-out]">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-qrcode"></i> สแกน QR ชำระเงิน
                            </h3>
                            <button type="button" onclick="closeOrderQRModal()" class="text-white/80 hover:text-white transition-colors">
                                <i class="fa-solid fa-xmark text-2xl"></i>
                            </button>
                        </div>
                        <div class="p-6">
                            <div class="bg-white p-4 rounded-2xl border-2 border-emerald-200 dark:border-slate-600 mb-4 text-center">
                                <img src="{{ asset('qr-code/qr-code.jpg') }}" alt="QR Code ชำระเงิน" class="w-full max-w-[280px] mx-auto rounded-lg shadow-sm">
                            </div>
                            <div class="text-center mb-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ออเดอร์</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">${orderNumber}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">ยอดชำระ</p>
                                <p id="order-qr-amount" class="text-3xl font-black text-emerald-600 dark:text-emerald-400">฿${amount.toLocaleString()}</p>
                            </div>
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 mb-4">
                                <p class="text-sm text-emerald-800 dark:text-emerald-200 flex items-start gap-2">
                                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                                    <span>สแกน QR ด้วยแอปธนาคารของคุณ เพื่อชำระล่วงหน้า</span>
                                </p>
                            </div>
                            <div class="space-y-3">
                                <button type="button" onclick="copyOrderAmount(${amount})"
                                    class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 text-lg">
                                    <i class="fa-regular fa-copy text-xl"></i>
                                    <span>คัดลอกยอดเงิน</span>
                                </button>
                                <a href="#" onclick="window.location.href='{{ route('customer.orders.pay', '__ORDER_ID__') }}'.replace('__ORDER_ID__', ${orderId}); return false;"
                                    class="w-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 font-bold py-4 rounded-2xl transition-all duration-300 flex items-center justify-center gap-2 text-lg">
                                    <i class="fa-solid fa-upload"></i>
                                    <span>แนบสลิปโอนเงิน</span>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(qrModal);
            }
            
            document.getElementById('order-qr-amount').textContent = '฿' + amount.toLocaleString();
            qrModal.classList.remove('hidden');
            qrModal.classList.add('flex');
        }
        
        function closeOrderQRModal() {
            if (qrModal) {
                qrModal.classList.add('hidden');
                qrModal.classList.remove('flex');
            }
        }

        function copyOrderAmount(amount) {
            navigator.clipboard.writeText(amount.toString()).then(() => {
                showToast('คัดลอกยอดเงินแล้ว', 'success');
            }).catch(() => {
                showToast('ไม่สามารถคัดลอกได้', 'error');
            });
        }

        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check' : 'fa-circle-exclamation';

            const toast = document.createElement('div');
            toast.className = `fixed top-4 left-1/2 -translate-x-1/2 ${bgColor} text-white px-6 py-3 rounded-full shadow-lg z-[70] animate-[fadeIn_0.2s_ease-out] flex items-center gap-2 font-semibold`;
            toast.innerHTML = `<i class="fa-solid ${icon}"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
        
        qrModal?.addEventListener('click', function(e) {
            if (e.target === qrModal) {
                closeOrderQRModal();
            }
        });
    })();
</script>
@endpush