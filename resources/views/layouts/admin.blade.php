<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'ระบบจัดการหลังบ้าน - Washly Admin')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])

    <script>
        if (localStorage.theme === 'dark' || !('theme' in localStorage)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200 font-sans antialiased transition-colors duration-300 overflow-hidden">

    <div class="flex h-screen w-full">

        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 dark:bg-slate-950 text-gray-300 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl md:shadow-none border-r border-slate-800">

            <div class="h-16 flex items-center justify-between px-6 bg-slate-950/50 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-pink-500 flex items-center gap-2">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Washly Admin
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.main') || request()->routeIs('admin.dashboard') ? 'bg-pink-600 text-white shadow-lg shadow-pink-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i>
                    <span class="font-medium">รายงานสรุปภาพรวม</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-pink-600 text-white shadow-lg shadow-pink-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-list-check w-5 text-center"></i>
                    <span class="font-medium">จัดการออเดอร์</span>
                </a>

                <a href="{{ route('admin.packages.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-pink-600 text-white shadow-lg shadow-pink-500/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                    <span class="font-medium">จัดการแพ็กเกจ</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-pink-500 flex items-center justify-center text-white font-bold">
                        {{ mb_substr(Auth::user()->fullname ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->fullname ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400">ผู้ดูแลระบบ</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            <header
                class="h-16 bg-white dark:bg-slate-800 shadow-sm border-b border-gray-100 dark:border-slate-700 flex items-center justify-between px-4 sm:px-6 transition-colors z-30">

                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-500 dark:text-gray-400 hover:text-pink-500 focus:outline-none p-2 rounded-lg bg-gray-50 dark:bg-slate-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex-1"></div>

                <div class="flex items-center gap-3 sm:gap-4">

                    <button onclick="toggleTheme()" id="theme-toggle-btn"
                        class="text-sm w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition flex items-center justify-center text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-moon"></i>
                    </button>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="button" onclick="confirmLogout(this.closest('form'))"
                            class="hidden sm:flex items-center text-red-500 dark:text-red-400 hover:text-white hover:bg-red-500 border border-red-500 dark:border-red-400 px-4 py-2 rounded-full font-medium transition-all text-sm">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> ออกจากระบบ
                        </button>
                        <button type="button" onclick="confirmLogout(this.closest('form'))"
                            class="sm:hidden text-red-500 dark:text-red-400 w-10 h-10 flex items-center justify-center rounded-full bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>

            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-slate-900 p-4 sm:p-6 lg:p-8 transition-colors relative">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        // 1. ระบบเปิด/ปิด Sidebar (สำหรับมือถือ)
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // 2. ระบบสลับธีม สว่าง/มืด
        const themeBtn = document.getElementById('theme-toggle-btn');
        const htmlEl = document.documentElement;

        function updateIcon() {
            if (htmlEl.classList.contains('dark')) {
                themeBtn.innerHTML = '<i class="fa-solid fa-sun text-yellow-400 text-lg"></i>';
            } else {
                themeBtn.innerHTML = '<i class="fa-solid fa-moon text-gray-600 text-lg"></i>';
            }
        }
        updateIcon();

        function toggleTheme() {
            if (htmlEl.classList.contains('dark')) {
                htmlEl.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                htmlEl.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateIcon();
        }

        // 3. ระบบยืนยันก่อนออกจากระบบ (SweetAlert2)
        function confirmLogout(form) {
            Swal.fire({
                title: 'ออกจากระบบแอดมิน?',
                text: "คุณต้องการออกจากระบบจัดการหลังบ้านใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: '<i class="fa-solid fa-right-from-bracket"></i> ใช่, ออกจากระบบ',
                cancelButtonText: 'ยกเลิก',
                background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: {
                    popup: 'rounded-3xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>
</body>

</html>