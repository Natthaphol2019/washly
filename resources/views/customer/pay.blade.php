@extends('layouts.customer')

@section('title', 'ชำระเงิน - Washly')

@section('content')
<div class="max-w-lg mx-auto pb-10 pt-4">
    
    <a href="{{ route('customer.orders') }}" class="inline-flex items-center text-gray-500 dark:text-gray-400 hover:text-pink-500 transition-colors mb-6">
        <i class="fa-solid fa-arrow-left-long mr-2"></i> ย้อนกลับไปหน้าออเดอร์
    </a>

    <div class="bg-white dark:bg-slate-800 rounded-[30px] p-8 shadow-md border border-gray-100 dark:border-slate-700 text-center">
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2">สแกนเพื่อชำระเงิน</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-6">หมายเลขออเดอร์: <span class="font-bold text-pink-500">{{ $order->order_number }}</span></p>

        <div class="bg-blue-900 rounded-2xl p-6 inline-block mb-6 shadow-inner w-full max-w-[280px]">
            <img src="https://promptpay.io/0812345678/{{ $order->total_price }}.png" alt="PromptPay QR Code" class="w-full bg-white rounded-xl p-2">
            <p class="text-white mt-4 font-medium"><i class="fa-solid fa-building-columns"></i> บัญชีร้าน Washly</p>
        </div>

        <div class="mb-8">
            <p class="text-sm text-gray-400 mb-1">ยอดที่ต้องชำระ</p>
            <p class="text-4xl font-extrabold text-pink-500">฿{{ number_format($order->total_price) }}</p>
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