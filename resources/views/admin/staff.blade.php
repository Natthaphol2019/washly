@extends('layouts.admin')

@section('title', 'จัดการพนักงาน - Washly Admin')

@section('content')
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    <i class="fa-solid fa-user-tie text-pink-500 mr-2"></i> จัดการพนักงานและแอดมิน
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">เพิ่ม แก้ไข กำหนดสิทธิ์ และลบบัญชีทีมงาน</p>
            </div>
            
            <button onclick="openModal('addStaffModal')" class="bg-pink-500 hover:bg-pink-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> เพิ่มทีมงาน
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                <p class="font-medium text-green-800 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-red-500 text-xl"></i>
                <p class="font-medium text-red-800 dark:text-red-400">{{ session('error') }}</p>
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
                            <th class="p-4 font-medium">ชื่อ-นามสกุล</th>
                            <th class="p-4 font-medium">อีเมล</th>
                            <th class="p-4 font-medium text-center">สิทธิ์การใช้งาน</th>
                            <th class="p-4 font-medium text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse($staff as $index => $user)
                            <tr class="hover:bg-pink-50/30 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4 text-center text-gray-500 dark:text-gray-400 font-medium">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <p class="font-bold text-gray-800 dark:text-gray-100">{{ $user->name }}
                                        @if(Auth::id() == $user->id) 
                                            <span class="text-xs ml-2 bg-pink-100 text-pink-600 px-2 py-0.5 rounded-full">คุณเอง</span> 
                                        @endif
                                    </p>
                                </td>
                                <td class="p-4 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                <td class="p-4 text-center">
                                    @if($user->role === 'admin')
                                        <span class="bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400 px-3 py-1 rounded-full text-sm font-medium border border-purple-200 dark:border-purple-800/50 shadow-sm"><i class="fa-solid fa-crown mr-1"></i> ผู้ดูแลระบบ (Admin)</span>
                                    @else
                                        <span class="bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400 px-3 py-1 rounded-full text-sm font-medium border border-blue-200 dark:border-blue-800/50 shadow-sm"><i class="fa-solid fa-user-gear mr-1"></i> พนักงาน (Staff)</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2 justify-center">
                                        <button type="button" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}')" class="bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-400 px-3 py-1.5 rounded-lg transition-colors shadow-sm text-sm flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                        </button>
                                        
                                        @if(Auth::id() != $user->id)
                                        <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($user->name) }}')" class="bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-400 px-3 py-1.5 rounded-lg transition-colors shadow-sm text-sm flex items-center gap-1">
                                                <i class="fa-solid fa-trash"></i> ลบ
                                            </button>
                                        </form>
                                        @else
                                        <button disabled class="bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-gray-500 px-3 py-1.5 rounded-lg text-sm flex items-center gap-1 cursor-not-allowed" title="ไม่สามารถลบตัวเองได้">
                                            <i class="fa-solid fa-lock"></i> ลบ
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-500 dark:text-gray-400">
                                    <p class="text-lg font-medium">ยังไม่มีข้อมูลพนักงาน</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="addStaffModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addStaffModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-pink-500"></i> เพิ่มทีมงานใหม่
            </h2>
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">อีเมล <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">รหัสผ่าน <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สิทธิ์การใช้งาน <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                            <option value="staff">พนักงาน (Staff)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addStaffModal')" class="px-5 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-sm transition-colors">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editStaffModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editStaffModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-pink-500"></i> แก้ไขข้อมูลทีมงาน
            </h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">อีเมล <span class="text-red-500">*</span></label>
                        <input type="email" id="edit_email" name="email" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">สิทธิ์การใช้งาน <span class="text-red-500">*</span></label>
                        <select id="edit_role" name="role" required class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                            <option value="staff">พนักงาน (Staff)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">เปลี่ยนรหัสผ่าน <span class="text-xs text-gray-400">(เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</span></label>
                        <input type="password" name="password" minlength="8" class="w-full border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-gray-100 rounded-xl px-4 py-2.5 outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editStaffModal')" class="px-5 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-2.5 rounded-xl font-medium shadow-sm transition-colors">อัปเดตข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openEditModal(id, name, email, role) {
            document.getElementById('editForm').action = `/admin/staff/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            openModal('editStaffModal');
        }

        function confirmDelete(form, staffName) {
            Swal.fire({
                title: 'ลบบัญชีทีมงาน?',
                html: `คุณแน่ใจหรือไม่ที่จะลบ <b>${staffName}</b> ออกจากระบบ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fa-solid fa-trash"></i> ใช่, ลบทิ้ง',
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