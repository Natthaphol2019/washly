@extends('layouts.admin')

@section('title', 'Dashboard - Washly Admin')

@section('content')
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-house text-indigo-500 mr-2"></i> รายงานสรุปภาพรวม
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">จัดการแพ็กเกจ ตรวจสอบออเดอร์ และอัปเดตสถานะงานแบบรวมศูนย์</p>
            </div>
            <button onclick="window.location.reload()" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2 w-max">
                <i class="fa-solid fa-rotate-right"></i> รีเฟรชข้อมูล
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                <p class="font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl shrink-0"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">ออเดอร์ทั้งหมด</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->count() }} <span class="text-sm font-normal text-gray-500">รายการ</span></h3>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 flex items-center justify-center text-2xl shrink-0"><i class="fa-solid fa-jug-detergent"></i></div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">กำลังดำเนินการ</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $orders->whereIn('status', ['pending', 'picked_up', 'processing', 'delivering'])->count() }} <span class="text-sm font-normal text-gray-500">รายการ</span></h3>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4 transition-colors">
                <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center text-2xl shrink-0"><i class="fa-solid fa-sack-dollar"></i></div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">รายได้ (ออเดอร์เสร็จสิ้น)</p>
                    <h3 class="text-2xl font-bold text-green-500 dark:text-green-400">฿{{ number_format($orders->where('status', 'completed')->sum('total_price')) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100"><i class="fa-solid fa-box-open text-indigo-500 mr-2"></i> แพ็กเกจทั้งหมด ({{ $packages->count() }})</h2>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($packages as $package)
                    <div class="rounded-2xl border border-gray-100 dark:border-slate-700 p-5 bg-gradient-to-br from-white to-indigo-50/40 dark:from-slate-800 dark:to-slate-700/50 hover:shadow-md transition-all">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100">{{ $package->name }}</h3>
                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">฿{{ number_format($package->price) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $package->description ?: 'ไม่มีรายละเอียดเพิ่มเติม' }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-box-open text-3xl mb-2 text-gray-300 dark:text-gray-600"></i>
                        <p>ยังไม่มีแพ็กเกจในระบบ</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors">
            <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100"><i class="fa-solid fa-list-check text-indigo-500 mr-2"></i> รายการออเดอร์ล่าสุด</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider border-b border-gray-200 dark:border-slate-700">
                            <th class="p-4 font-medium whitespace-nowrap">ออเดอร์</th>
                            <th class="p-4 font-medium w-full">ลูกค้า</th>
                            <th class="p-4 font-medium whitespace-nowrap">แพ็กเกจ/สลิป</th>
                            <th class="p-4 font-medium text-center whitespace-nowrap w-px">สถานะ</th>
                            <th class="p-4 font-medium text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                
                                <td class="p-4 whitespace-nowrap">
                                    <p class="font-bold text-indigo-600 dark:text-indigo-400">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->created_at)->addYears(543)->format('d/m/Y H:i') }}</p>
                                </td>

                                <td class="p-4">
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $order->user->fullname ?? 'ไม่ระบุ' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><i class="fa-solid fa-phone mr-1"></i> {{ $order->user->phone ?? '-' }}</p>
                                </td>

                                <td class="p-4 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $order->package->name ?? '-' }}</p>
                                    <p class="text-xs text-pink-500 dark:text-pink-400 font-bold mt-1 mb-2">฿{{ number_format($order->total_price) }}</p>
                                    
                                    <div class="flex items-center gap-1">
                                        @if($order->payment_status == 'paid')
                                            <button type="button" onclick="showSlip('{{ asset('storage/' . $order->payment_slip) }}')" class="inline-flex items-center gap-1 text-[11px] bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-gray-300 px-2 py-1 rounded border border-gray-200 dark:border-slate-600 hover:bg-gray-200 transition-colors focus:outline-none">
                                                <i class="fa-solid fa-receipt"></i> ดูสลิป
                                            </button>
                                            <span class="text-[11px] text-green-600 dark:text-green-400 font-bold ml-1">
                                                <i class="fa-solid fa-circle-check"></i> ชำระแล้ว
                                            </span>
                                        
                                        @elseif($order->payment_status == 'reviewing' && $order->payment_slip)
                                            <button type="button" onclick="showSlip('{{ asset('storage/' . $order->payment_slip) }}')" class="inline-flex items-center gap-1 text-[11px] bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 px-2 py-1 rounded border border-blue-200 dark:border-blue-800 hover:bg-blue-200 dark:hover:bg-blue-800/60 transition-colors shadow-sm focus:outline-none">
                                                <i class="fa-solid fa-image"></i> ดูสลิป
                                            </button>
                                            
                                            <form action="{{ route('admin.orders.approve_payment', $order->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center text-[11px] bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400 w-6 h-6 rounded border border-green-200 dark:border-green-800 hover:bg-green-200 dark:hover:bg-green-800/60 transition-colors shadow-sm focus:outline-none" title="อนุมัติยอดเงิน">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.orders.reject_slip', $order->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="button" onclick="confirmRejectSlip(this.closest('form'))" class="inline-flex items-center justify-center text-[11px] bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 w-6 h-6 rounded border border-red-200 dark:border-red-800 hover:bg-red-200 dark:hover:bg-red-800/60 transition-colors shadow-sm focus:outline-none" title="ปฏิเสธสลิป ให้ส่งใหม่">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </button>
                                            </form>
                                        
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400 px-2 py-1 rounded border border-gray-200 dark:border-slate-600">
                                                <i class="fa-solid fa-hourglass-half"></i> รอชำระ
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'picked_up' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'processing' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                            'delivering' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'รอรับผ้า', 'picked_up' => 'รับผ้ามาแล้ว', 'processing' => 'กำลังซัก/อบ',
                                            'delivering' => 'กำลังไปส่งคืน', 'completed' => 'เสร็จสิ้น', 'cancelled' => 'ยกเลิก',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>

                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="flex flex-row items-center gap-2 justify-center m-0">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="text-sm border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-lg shadow-sm focus:border-indigo-400 py-1.5 px-2 outline-none w-auto min-w-[120px]">
                                            @foreach($statusLabels as $key => $label)
                                                <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white text-xs px-3 py-2 rounded-lg transition-colors shadow-sm shrink-0">อัปเดต</button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="inline-block p-4 rounded-full bg-gray-50 dark:bg-slate-800 mb-3"><i class="fa-solid fa-inbox text-4xl text-gray-300 dark:text-gray-600"></i></div>
                                    <p class="text-lg font-medium">ยังไม่มีออเดอร์ในระบบ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // ฟังก์ชันโชว์รูปสลิป
        function showSlip(imageUrl) {
            Swal.fire({
                title: 'หลักฐานการโอนเงิน',
                imageUrl: imageUrl,
                imageAlt: 'สลิปโอนเงิน',
                confirmButtonText: 'ปิดหน้าต่าง',
                confirmButtonColor: '#6366f1',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: {
                    popup: 'rounded-3xl',
                    image: 'rounded-xl max-h-[60vh] object-contain border border-gray-200 dark:border-slate-700'
                }
            });
        }

        // ฟังก์ชันถามยืนยันก่อนลบ/ปฏิเสธสลิป
        function confirmRejectSlip(form) {
            Swal.fire({
                title: 'ปฏิเสธสลิปใบนี้?',
                text: "สลิปนี้จะถูกลบออก และลูกค้าจะต้องอัปโหลดสลิปเข้ามาใหม่",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> ใช่, ปฏิเสธสลิป',
                cancelButtonText: 'ยกเลิก',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endsection