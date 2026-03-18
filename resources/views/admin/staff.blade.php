@extends('layouts.admin')
@section('title', 'จัดการพนักงาน - Washly Admin')
@section('page_title', 'จัดการพนักงานและแอดมิน')

@section('content')
    <div class="max-w-7xl mx-auto w-full flex flex-col gap-6 pb-10">

        {{-- 🔹 หัวข้อหลัก --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl washly-card shadow-sm flex items-center justify-center border border-sky-100 dark:border-slate-700">
                    <i class="fa-solid fa-user-tie text-2xl text-sky-500"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold washly-shell">จัดการทีมงาน</h1>
                    <p class="text-sm text-gray-500 mt-1">เพิ่ม แก้ไข กำหนดสิทธิ์บัญชีทีมงาน</p>
                </div>
            </div>

            <button onclick="openModal('addStaffModal')" class="washly-brand-btn text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> เพิ่มทีมงาน
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-4 rounded-xl shadow-sm flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                <p class="font-medium text-emerald-700 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-4 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm text-rose-600 dark:text-rose-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 🔍 Filter และ Search --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
            <form method="GET" action="{{ route('admin.staff.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    
                    {{-- ค้นหาชื่อ/username --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-search"></i> ค้นหา
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="ชื่อ หรือ username..."
                            class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                    </div>

                    {{-- กรองตามบทบาท --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-user-tag"></i> บทบาท
                        </label>
                        <select name="role" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                            <option value="">ทั้งหมด</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>แอดมิน</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>พนักงาน</option>
                        </select>
                    </div>

                    {{-- เรียงลำดับ --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            <i class="fa-solid fa-arrow-down-a-z"></i> เรียงตาม
                        </label>
                        <select name="sort" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 outline-none transition">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ชื่อ A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ชื่อ Z-A</option>
                            <option value="role_asc" {{ request('sort') == 'role_asc' ? 'selected' : '' }}>บทบาท A-Z</option>
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>ใหม่ → เก่า</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                    </button>
                    <a href="{{ route('admin.staff.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i> ล้างตัวกรอง
                    </a>
                    
                    @if(request()->hasAny(['search', 'role', 'sort']))
                        <div class="flex items-center gap-2 ml-auto text-xs text-gray-500 dark:text-gray-400">
                            <span class="font-semibold text-sky-600 dark:text-sky-400">{{ $staff->count() }}</span> คน
                        </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- สรุปผลการค้นหา --}}
        @if(request()->hasAny(['search', 'role']))
            <div class="flex items-center justify-between text-sm bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 px-4 py-2.5 rounded-xl">
                <p class="text-sky-700 dark:text-sky-400">
                    <i class="fa-solid fa-filter"></i> 
                    กำลังแสดงผลลัพธ์ที่กรอง: 
                    @if(request('search')) <span class="font-semibold">ค้นหา="{{ request('search') }}"</span> @endif
                    @if(request('role') == 'admin') <span class="font-semibold">บทบาท="แอดมิน"</span> @endif
                    @if(request('role') == 'staff') <span class="font-semibold">บทบาท="พนักงาน"</span> @endif
                </p>
                <span class="text-sky-600 dark:text-sky-400 font-bold">{{ $staff->count() }} คน</span>
            </div>
        @endif

        {{-- 🔹 ตารางทีมงาน --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="p-4 font-semibold w-px whitespace-nowrap text-center">ลำดับ</th>
                            <th class="p-4 font-semibold">ชื่อ-นามสกุล</th>
                            <th class="p-4 font-semibold">ชื่อผู้ใช้งาน (Username)</th>
                            <th class="p-4 font-semibold text-center">สิทธิ์การใช้งาน</th>
                            <th class="p-4 font-semibold text-center whitespace-nowrap w-px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 washly-shell">
                        @forelse($staff as $index => $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <p class="font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                        {{ $user->fullname }}
                                        @if(Auth::id() == $user->id) 
                                            <span class="text-[10px] bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full border border-sky-200 dark:bg-sky-900/50 dark:text-sky-300 dark:border-sky-800">คุณเอง</span> 
                                        @endif
                                    </p>
                                </td>
                                <td class="p-4 text-gray-500">{{ $user->username }}</td>
                                <td class="p-4 text-center">
                                    @if($user->role === 'admin')
                                        <span class="bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-crown mr-1"></i> แอดมิน</span>
                                    @else
                                        <span class="bg-sky-50 text-sky-600 border border-sky-200 dark:bg-sky-900/30 dark:border-sky-800 px-3 py-1 rounded-full text-xs font-bold"><i class="fa-solid fa-user-gear mr-1"></i> พนักงาน</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap w-px">
                                    <div class="flex items-center gap-2 justify-center">
                                        <button type="button" onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->fullname) }}', '{{ addslashes($user->username) }}', '{{ $user->role }}')" class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 hover:bg-amber-100 transition flex items-center justify-center border border-amber-200 dark:border-amber-800/50">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        
                                        @if(Auth::id() != $user->id)
                                            <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this.closest('form'), '{{ addslashes($user->fullname) }}')" class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-500 hover:bg-rose-100 transition flex items-center justify-center border border-rose-200 dark:border-rose-800/50">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-700 flex items-center justify-center cursor-not-allowed border border-slate-200 dark:border-slate-600" title="ไม่สามารถลบตัวเองได้">
                                                <i class="fa-solid fa-lock text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-400">
                                    <i class="fa-solid fa-users-slash text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium">ยังไม่มีข้อมูลพนักงาน</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🟢 Modal: เพิ่มพนักงานใหม่ --}}
    <div id="addStaffModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('addStaffModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8 border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold washly-brand-text mb-6 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-sky-500"></i> เพิ่มทีมงานใหม่
            </h2>
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                        <input type="text" name="fullname" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อผู้ใช้งาน (Username) <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">รหัสผ่าน <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">สิทธิ์การใช้งาน <span class="text-rose-500">*</span></label>
                        <select name="role" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                            <option value="staff">พนักงาน (Staff)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addStaffModal')" class="px-5 py-2.5 rounded-xl font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">ยกเลิก</button>
                    <button type="submit" class="washly-brand-btn text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:brightness-110">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🟡 Modal: แก้ไขพนักงาน --}}
    <div id="editStaffModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('editStaffModal')"></div>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg mx-4 z-10 transform transition-all p-6 md:p-8 border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-bold text-amber-500 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูลทีมงาน
            </h2>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อ-นามสกุล <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_fullname" name="fullname" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">ชื่อผู้ใช้งาน <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_username" name="username" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">สิทธิ์การใช้งาน <span class="text-rose-500">*</span></label>
                        <select id="edit_role" name="role" required class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                            <option value="staff">พนักงาน (Staff)</option>
                            <option value="admin">ผู้ดูแลระบบ (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">เปลี่ยนรหัสผ่าน <span class="text-xs text-slate-400 font-normal">(เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</span></label>
                        <input type="password" name="password" minlength="8" class="w-full border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editStaffModal')" class="px-5 py-2.5 rounded-xl font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">ยกเลิก</button>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md transition-colors">อัปเดตข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script ควบคุม Modal และ SweetAlert --}}
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function openEditModal(id, fullname, username, role) {
            document.getElementById('editForm').action = `/admin/staff/${id}`;
            document.getElementById('edit_fullname').value = fullname;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_role').value = role;
            openModal('editStaffModal');
        }

        function confirmDelete(form, staffName) {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: 'ลบบัญชีทีมงาน?',
                html: `คุณแน่ใจหรือไม่ที่จะลบ <b>${staffName}</b> ออกจากระบบ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ใช่, ลบทิ้ง',
                cancelButtonText: 'ยกเลิก',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
@endsection