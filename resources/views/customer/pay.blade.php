@extends('layouts.customer')

@section('title', 'ชำระเงิน - Washly')

@section('content')
<div id="pay-live-region" class="max-w-lg mx-auto pb-10 pt-4">
    
    <a href="{{ route('customer.orders') }}" class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-pink-500 transition-colors mb-6">
        <i class="fa-solid fa-arrow-left-long mr-2"></i> ย้อนกลับไปหน้าออเดอร์
    </a>

    <div class="bg-white dark:bg-slate-800 rounded-[30px] p-8 shadow-md border border-gray-100 dark:border-slate-700 text-center">
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">สแกนเพื่อชำระเงิน</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-6">หมายเลขออเดอร์: <span class="font-bold text-pink-500">{{ $order->order_number }}</span></p>

        <div class="bg-blue-900 rounded-2xl p-6 inline-block mb-6 shadow-inner w-full max-w-[280px]">
            <img src="https://promptpay.io/1139900420087/{{ $order->total_price }}.png" alt="PromptPay QR Code" class="w-full bg-white rounded-xl p-2">
            <p class="text-white mt-4 font-medium"><i class="fa-solid fa-building-columns"></i> บัญชีร้าน Washly</p>
        </div>

        <div class="mb-8">
            <p class="text-sm text-gray-400 mb-1">ยอดที่ต้องชำระ</p>
            <p class="text-4xl font-extrabold text-pink-500">฿{{ number_format($order->total_price) }}</p>
        </div>

        <div class="mb-8 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 text-left">
            <p class="text-sm font-bold text-amber-800 dark:text-amber-300 flex items-center gap-2">
                <i class="fa-solid fa-money-bill-wave"></i> อยากเปลี่ยนเป็นเงินสดปลายทาง?
            </p>
            <p class="text-sm text-amber-700/90 dark:text-amber-200/90 mt-2 leading-6">
                ถ้ายังไม่ได้อัปโหลดสลิป คุณสามารถเปลี่ยนออเดอร์นี้เป็นชำระเงินปลายทางได้ทันที โดยไม่ต้องยกเลิกออเดอร์แล้วจองใหม่
            </p>

            <form id="switch-to-cash-form" action="{{ route('customer.orders.switch_to_cash', $order->id) }}" method="POST" class="mt-4">
                @csrf
                <button type="button" onclick="confirmSwitchToCash()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-5 rounded-full shadow-sm hover:shadow-md transition-all">
                    <i class="fa-solid fa-hand-holding-dollar"></i> เปลี่ยนเป็นชำระเงินปลายทาง
                </button>
            </form>
        </div>

        <form action="{{ route('customer.orders.upload_slip', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">แนบหลักฐานการโอนเงิน (สลิป)</label>
                <div class="relative">
                    <input type="file" name="slip" accept="image/jpeg, image/png, image/jpg" required
                        class="block w-full text-sm text-gray-500 dark:text-gray-400
                        file:mr-4 file:py-2.5 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-medium
                        file:bg-pink-50 file:text-pink-600 dark:file:bg-pink-900/30 dark:file:text-pink-400
                        hover:file:bg-pink-100 dark:hover:file:bg-pink-900/50 transition-colors
                        border border-gray-200 dark:border-slate-600 rounded-2xl p-2 bg-gray-50 dark:bg-slate-700/50 cursor-pointer">
                </div>
                @error('slip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-3.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all flex justify-center items-center gap-2 mt-4">
                <i class="fa-solid fa-cloud-arrow-up"></i> ยืนยันการชำระเงิน
            </button>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmSwitchToCash() {
        const form = document.getElementById('switch-to-cash-form');

        Swal.fire({
            title: 'เปลี่ยนเป็นเงินสดปลายทาง?',
            text: 'ระบบจะยกเลิกการชำระแบบโอนสำหรับออเดอร์นี้ และเปลี่ยนเป็นรอพนักงานเก็บเงินแทน',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'ใช่, เปลี่ยนเลย',
            cancelButtonText: 'ยกเลิก',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

<script>
    (function () {
        const currentOrderNumber = @json($order->order_number);
        let refreshTimer = null;
        let isRefreshing = false;

        async function refreshPaySection() {
            if (isRefreshing) {
                return;
            }

            isRefreshing = true;

            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                });

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const nextRegion = doc.querySelector('#pay-live-region');
                const currentRegion = document.querySelector('#pay-live-region');

                if (nextRegion && currentRegion) {
                    currentRegion.innerHTML = nextRegion.innerHTML;
                } else {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Refresh pay section failed', error);
                window.location.reload();
            } finally {
                isRefreshing = false;
            }
        }

        function scheduleRefresh() {
            if (refreshTimer !== null) {
                return;
            }

            refreshTimer = window.setTimeout(function () {
                refreshTimer = null;
                refreshPaySection();
            }, 450);
        }

        window.addEventListener('washly:notification-received', function (event) {
            const payload = event.detail || {};
            const haystack = `${payload.title || ''} ${payload.message || ''} ${payload.url || ''}`.toLowerCase();
            const orderNo = String(currentOrderNumber || '').toLowerCase();

            const isOrderRelated =
                haystack.includes('ชำระ') ||
                haystack.includes('สถานะ') ||
                haystack.includes('ออเดอร์') ||
                haystack.includes('/customer/orders');

            const isCurrentOrder = orderNo && haystack.includes(orderNo);

            if (isCurrentOrder || isOrderRelated) {
                scheduleRefresh();
            }
        });
    })();
</script>
@endpush