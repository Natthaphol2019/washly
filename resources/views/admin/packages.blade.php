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
                                <td class="p-4 text-right">
                                    <span class="text-xl font-black text-pink-500 dark:text-pink-400">฿{{ number_format($package->price) }}</span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2 justify-center">
                                        <button type="button" onclick="openEditModal({{ $package->id }}, '{{ addslashes($package->name) }}', {{ $package->price }}, '{{ addslashes($package->description) }}')" class="bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-400 px-3 py-1.5 rounded-lg transition-colors shadow-sm text-sm flex items-center gap-1">
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
                                <td colspan="4" class="p-10 text-center text-gray-500 dark:text-gray-400">
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
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editPackageModal')" class="px-5 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-sm transition-colors">อัปเดตข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openEditModal(id, name, price, description) {
            document.getElementById('editForm').action = `/admin/packages/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description;
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
    </script>
@endsection