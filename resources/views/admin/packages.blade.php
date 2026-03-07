@extends('layouts.admin')

@section('title', 'จัดการแพ็กเกจ - Washly Admin')

@section('content')
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-boxes-stacked text-pink-500 mr-2"></i> จัดการแพ็กเกจบริการ
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">เพิ่ม แก้ไข และลบแพ็กเกจซักผ้าของคุณ</p>
            </div>
            
            <button onclick="openModal('addPackageModal')" class="bg-pink-500 hover:bg-pink-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> เพิ่มแพ็กเกจใหม่
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                <p class="font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-pink-50/50 dark:bg-slate-900/50 text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider border-b border-pink-100 dark:border-slate-700">
                            <th class="p-4 font-medium w-px whitespace-nowrap text-center">ลำดับ</th>
                            <th class="p-4 font-medium">ชื่อแพ็กเกจ / รายละเอียด</th>
                            <th class="p-4 font-medium whitespace-nowrap">น้ำยาอัตโนมัติ</th>
                            <th class="p-4 font-medium whitespace-nowrap text-right">ราคา (บาท)</th>
                            <th class="p-4 font-medium text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($packages as $index => $package)
                            <tr class="hover:bg-pink-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4 text-center text-gray-500 dark:text-gray-400 font-medium">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <p class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{ $package->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $package->description ?? '-' }}</p>
                                </td>
                                <td class="p-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $detergentOptions->firstWhere('code', $package->default_detergent_code)?->name ?? 'ใช้ค่าเริ่มต้นระบบ' }}
                                </td>
                                <td class="p-4 text-right">
                                    <span class="text-xl font-black text-pink-500 dark:text-pink-400">฿{{ number_format($package->price) }}</span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2 justify-center">
                                        <button type="button" onclick="openEditModal({{ $package->id }}, '{{ addslashes($package->name) }}', {{ $package->price }}, '{{ addslashes($package->description) }}', '{{ $package->default_detergent_code }}')" class="bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-400 px-3 py-1.5 rounded-lg transition-colors shadow-sm text-sm flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                        </button>
                                        
                                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($package->name) }}')" class="bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-400 px-3 py-1.5 rounded-lg transition-colors shadow-sm text-sm flex items-center gap-1">
                                                <i class="fa-solid fa-trash"></i> ลบ
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-500 dark:text-gray-400">
                                    <div class="inline-block p-4 rounded-full bg-pink-50 dark:bg-slate-800 mb-3"><i class="fa-solid fa-box-open text-4xl text-pink-300 dark:text-gray-600"></i></div>
                                    <p class="text-lg font-medium">ยังไม่มีแพ็กเกจในระบบ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="addPackageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addPackageModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-pink-500"></i> เพิ่มแพ็กเกจใหม่
            </h2>
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อแพ็กเกจ <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ราคา (บาท) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" min="0" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รายละเอียด (ตัวเลือก)</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">น้ำยาอัตโนมัติเมื่อ "ลูกค้าไม่มีน้ำยา"</label>
                        <select name="default_detergent_code" class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                            <option value="">ใช้ค่าเริ่มต้นระบบ</option>
                            @foreach($detergentOptions as $detergent)
                                <option value="{{ $detergent->code }}">{{ $detergent->name }} (+฿{{ number_format($detergent->unit_price) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addPackageModal')" class="px-5 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-sm transition-colors">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editPackageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editPackageModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-pink-500"></i> แก้ไขแพ็กเกจ
            </h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อแพ็กเกจ <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ราคา (บาท) <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_price" name="price" min="0" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รายละเอียด (ตัวเลือก)</label>
                        <textarea id="edit_description" name="description" rows="3" class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">น้ำยาอัตโนมัติเมื่อ "ลูกค้าไม่มีน้ำยา"</label>
                        <select id="edit_default_detergent_code" name="default_detergent_code" class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                            <option value="">ใช้ค่าเริ่มต้นระบบ</option>
                            @foreach($detergentOptions as $detergent)
                                <option value="{{ $detergent->code }}">{{ $detergent->name }} (+฿{{ number_format($detergent->unit_price) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editPackageModal')" class="px-5 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-sm transition-colors">อัปเดตข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    @php
        $addonSections = [
            'detergent' => ['title' => 'น้ำยาซักผ้า', 'icon' => 'fa-soap'],
            'softener' => ['title' => 'น้ำยาทำให้ผ้าฟู', 'icon' => 'fa-wind'],
            'service' => ['title' => 'บริการเสริม', 'icon' => 'fa-sparkles'],
        ];
    @endphp

    <div class="max-w-7xl mx-auto w-full mt-8 space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-flask-vial text-pink-500 mr-2"></i>เมนูเสริม (Add-ons)
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">เพิ่มและจัดการแยกตามหมวดเพื่อความรวดเร็ว</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                @foreach($addonSections as $category => $meta)
                    <form action="{{ route('admin.addons.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-gray-100 dark:border-slate-700 p-4 space-y-3 bg-gray-50/70 dark:bg-slate-900/30">
                        @csrf
                        <input type="hidden" name="category" value="{{ $category }}">

                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <i class="fa-solid {{ $meta['icon'] }} text-pink-500"></i> {{ $meta['title'] }}
                        </h3>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">รหัสเมนู (Auto)</label>
                            <input type="text" name="code" placeholder="ระบบสร้างให้อัตโนมัติ" readonly class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">ชื่อเมนู</label>
                            <input type="text" name="name" placeholder="เช่น น้ำยาซักฆ่าเชื้อ" required class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">ราคา (บาท)</label>
                            <input type="number" name="unit_price" min="0" required placeholder="เช่น 15" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">รูปเมนู (ถ้ามี)</label>
                            <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-xs flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> ใช้งาน</label>
                            @if($category !== 'service')
                                <label class="text-xs flex items-center gap-2"><input type="checkbox" name="is_default" value="1"> ตั้งเป็นค่าเริ่มต้น</label>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium">เพิ่ม{{ $meta['title'] }}</button>
                    </form>
                @endforeach
            </div>
        </div>

        @foreach($addonSections as $category => $meta)
            @php $items = $addonOptions->where('category', $category)->values(); @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fa-solid {{ $meta['icon'] }} text-pink-500"></i> รายการ{{ $meta['title'] }}
                    </h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $items->count() }} รายการ</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-500 border-b border-gray-200 dark:border-slate-700">
                                <th class="text-left py-2">Code</th>
                                <th class="text-left py-2">ชื่อ</th>
                                <th class="text-left py-2">รูป</th>
                                <th class="text-left py-2">ราคา</th>
                                <th class="text-left py-2">สถานะ</th>
                                <th class="text-left py-2">Default</th>
                                <th class="text-right py-2">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $addon)
                                <tr class="border-b border-gray-100 dark:border-slate-700/60">
                                    <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="category" value="{{ $category }}">
                                        <td class="py-2 pr-2 text-gray-500">{{ $addon->code }}</td>
                                        <td class="py-2 pr-2"><input type="text" name="name" value="{{ $addon->name }}" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-2 py-1 bg-white dark:bg-slate-700"></td>
                                        <td class="py-2 pr-2">
                                            <div class="flex items-center gap-2">
                                                @if($addon->image_path)
                                                    <img src="{{ asset('storage/' . $addon->image_path) }}" alt="{{ $addon->name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-slate-700">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-slate-700 border border-gray-200 dark:border-slate-600"></div>
                                                @endif
                                                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="text-xs w-44">
                                            </div>
                                        </td>
                                        <td class="py-2 pr-2"><input type="number" min="0" name="unit_price" value="{{ $addon->unit_price }}" class="w-28 border border-gray-300 dark:border-slate-600 rounded-lg px-2 py-1 bg-white dark:bg-slate-700"></td>
                                        <td class="py-2 pr-2"><label class="inline-flex items-center gap-1"><input type="checkbox" name="is_active" value="1" {{ $addon->is_active ? 'checked' : '' }}> ใช้งาน</label></td>
                                        <td class="py-2 pr-2">
                                            @if($category !== 'service')
                                                <label class="inline-flex items-center gap-1"><input type="checkbox" name="is_default" value="1" {{ $addon->is_default ? 'checked' : '' }}> default</label>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <button type="button" onclick="confirmUpdateAddon(this.form, '{{ $addon->name }}')" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700">แก้ไข</button>
                                                <button type="button" onclick="confirmDeleteAddon('{{ route('admin.addons.destroy', $addon->id) }}', '{{ $addon->name }}')" class="bg-rose-100 text-rose-700 px-3 py-1.5 rounded-lg hover:bg-rose-200">ลบ</button>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center text-gray-400">ยังไม่มี{{ $meta['title'] }}ในระบบ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <form id="addonDeleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openEditModal(id, name, price, description, defaultDetergentCode) {
            document.getElementById('editForm').action = `/admin/packages/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_default_detergent_code').value = defaultDetergentCode || '';
            openModal('editPackageModal');
        }

        function confirmDelete(form, packageName) {
            Swal.fire({
                title: 'ลบแพ็กเกจนี้?',
                html: `คุณแน่ใจหรือไม่ที่จะลบ <b>${packageName}</b> ?<br><span class="text-sm text-red-500">การกระทำนี้ไม่สามารถย้อนกลับได้</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> ใช่, ลบทิ้งเลย',
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

        function confirmDeleteAddon(actionUrl, addonName) {
            Swal.fire({
                title: 'ลบเมนูเสริมนี้?',
                html: `คุณแน่ใจหรือไม่ที่จะลบ <b>${addonName}</b> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('addonDeleteForm');
                    form.action = actionUrl;
                    form.submit();
                }
            });
        }

        function confirmUpdateAddon(form, addonName) {
            Swal.fire({
                title: 'บันทึกการแก้ไขเมนูเสริม?',
                html: `ยืนยันแก้ไข <b>${addonName}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ใช่, แก้ไขเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
@endsection