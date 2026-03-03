@extends('layouts.customer')

@section('title', 'จองคิวรับผ้า - Laundry Drop')

@section('content')
    <div class="max-w-3xl mx-auto pb-10">

        <div class="text-center mb-8 pt-4">
            <div
                class="inline-block bg-pink-100 dark:bg-slate-700 text-pink-500 dark:text-pink-400 p-4 rounded-full mb-4 shadow-sm">
                <i class="fa-solid fa-motorcycle text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">จองคิวให้ไรเดอร์ไปรับผ้า</h2>
            <p class="text-gray-500 dark:text-gray-400">กรอกข้อมูลให้ครบถ้วน เพื่อความรวดเร็วในการเข้ารับบริการ</p>
        </div>

        <div
            class="bg-white dark:bg-slate-800 rounded-[30px] p-6 md:p-10 shadow-lg border border-pink-50 dark:border-slate-700 transition-colors">

            <form action="{{ route('customer.book.store') }}" method="POST" class="space-y-10">
                @csrf

                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="shrink-0 bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">
                            1</div>
                        <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">เลือกขนาดแพ็กเกจ</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pl-0 md:pl-11">
                        @foreach ($packages as $package)
                            <label class="cursor-pointer relative group">
                                <input type="radio" name="package_id" value="{{ $package->id }}" class="peer sr-only"
                                    required>

                                <div
                                    class="p-5 h-full rounded-2xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 hover:border-pink-300 dark:hover:border-pink-500 peer-checked:border-pink-500 dark:peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-slate-700 transition-all text-center flex flex-col justify-between shadow-sm hover:shadow-md">
                                    <div>
                                        <i
                                            class="fa-solid fa-shirt text-3xl text-gray-300 dark:text-gray-600 peer-checked:text-pink-400 dark:peer-checked:text-pink-400 mb-3 transition-colors"></i>
                                        <h4 class="font-bold text-gray-700 dark:text-gray-200">{{ $package->name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                                            {{ $package->description }}</p>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-slate-600">
                                        <p class="text-xl font-extrabold text-pink-500 dark:text-pink-400">
                                            ฿{{ number_format($package->price) }}</p>
                                    </div>
                                    <div
                                        class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-pink-500 transition-opacity">
                                        <i
                                            class="fa-solid fa-circle-check text-xl bg-white dark:bg-slate-800 rounded-full"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="shrink-0 bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">
                            2</div>
                        <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">เลือกรอบเวลารับผ้า</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pl-0 md:pl-11">
                        @foreach ($timeSlots as $slot)
                            <label class="cursor-pointer relative">
                                <input type="radio" name="time_slot_id" value="{{ $slot->id }}" class="peer sr-only"
                                    required>
                                <div
                                    class="px-4 py-4 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50 hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-slate-700 transition-all flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <i class="fa-regular fa-clock text-gray-400 peer-checked:text-pink-500"></i>
                                        <span
                                            class="font-medium text-gray-700 dark:text-gray-200">{{ $slot->round_name }}</span>
                                    </div>
                                    <i
                                        class="fa-solid fa-check text-pink-500 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="shrink-0 bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">
                            3</div>
                        <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">ตั้งค่าการซักและอบ</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pl-0 md:pl-11">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-droplet text-blue-400 mr-1"></i> อุณหภูมิน้ำ (ซัก)
                            </label>
                            <select name="wash_temp"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-gray-200 focus:border-pink-400 focus:ring-0 outline-none transition-colors cursor-pointer">
                                <option value="เย็น">ซักน้ำเย็น (ถนอมผ้า)</option>
                                <option value="อุ่น">ซักน้ำอุ่น (คราบทั่วไป)</option>
                                <option value="ร้อน">ซักน้ำร้อน (ฆ่าเชื้อโรค)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                                <i class="fa-solid fa-wind text-orange-400 mr-1"></i> อุณหภูมิความร้อน (อบ)
                            </label>
                            <select name="dry_temp"
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-gray-200 focus:border-pink-400 focus:ring-0 outline-none transition-colors cursor-pointer">
                                <option value="อุ่น">อบลมอุ่น (ถนอมผ้า)</option>
                                <option value="ปานกลาง">อบความร้อนปานกลาง</option>
                                <option value="ร้อน">อบความร้อนสูง (ผ้าหนา)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="shrink-0 bg-pink-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold shadow-md">4</div>
                        <h3 class="text-xl font-medium text-gray-800 dark:text-gray-200">สถานที่รับ-ส่งผ้า</h3>
                    </div>
                    
                    <div class="pl-0 md:pl-11">
                        
                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 mb-4 rounded-r-xl shadow-sm transition-colors">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                                <p class="text-sm text-blue-800 dark:text-blue-200 leading-relaxed">
                                    ระบบจะอ้างอิงพิกัด GPS จากที่อยู่หลักของคุณ หากต้องการเปลี่ยนสถานที่รับ-ส่งผ้าแบบถาวร <br class="hidden sm:block">
                                    <span class="opacity-80">กรุณาไปแก้ไขที่</span> 
                                    <a href="{{ route('customer.profile') }}" class="font-bold underline decoration-blue-400 underline-offset-2 hover:text-blue-600 dark:hover:text-blue-300 transition-colors">หน้าตั้งค่าโปรไฟล์ <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i></a>
                                </p>
                            </div>
                        </div>

                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                            ที่อยู่ปัจจุบัน / จุดสังเกตเพิ่มเติมให้ไรเดอร์
                        </label>
                        <textarea id="pickup_address" name="pickup_address" rows="3" required readonly
                                  placeholder="เช่น ฝากไว้ที่ป้อมยาม, ตึก A ชั้น 3, บ้านประตูสีฟ้า..."
                                  class="w-full p-4 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-800 dark:text-gray-200 focus:border-pink-400 focus:ring-0 outline-none transition-colors resize-none leading-relaxed">{{ Auth::user()->address }}</textarea>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-slate-700">
                    <button type="submit"
                        class="w-full bg-pink-500 hover:bg-pink-600 text-white text-xl font-bold py-5 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-3">
                        ยืนยันการจองคิว <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
