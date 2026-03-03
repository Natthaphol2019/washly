<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'บริการรับส่งซักผ้า')</title>

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

<body class="bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-gray-200 font-sans antialiased pb-20 md:pb-0 transition-colors duration-300">

    <nav class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl sticky top-0 z-50 border-b border-pink-100/50 dark:border-slate-700/50 transition-colors shadow-[0_4px_20px_-10px_rgba(236,72,153,0.15)] dark:shadow-none">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            <a href="{{ route('customer.main') }}" class="text-2xl font-extrabold flex items-center gap-2 hover:scale-105 transition-transform duration-300 group">
                <div class="bg-gradient-to-tr from-pink-500 to-rose-400 text-white p-1.5 rounded-xl shadow-md group-hover:rotate-12 transition-transform">
                    <i class="fa-solid fa-shirt text-sm"></i>
                </div>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-500 to-rose-400">Washly</span>
            </a>

            <div class="hidden md:flex items-center gap-1 bg-gray-100/80 dark:bg-slate-900/50 p-1.5 rounded-full shadow-inner border border-gray-200/50 dark:border-slate-700">
                
                <a href="{{ route('customer.main') }}" class="relative px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('customer.main') || request()->routeIs('customer.dashboard') ? 'bg-gradient-to-r from-pink-500 to-rose-400 text-white shadow-md transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:text-pink-600 dark:hover:text-pink-400 hover:bg-white dark:hover:bg-slate-800' }}">
                    <i class="fa-solid fa-house mr-1 {{ request()->routeIs('customer.main') || request()->routeIs('customer.dashboard') ? 'animate-pulse' : '' }}"></i> หน้าแรก
                </a>
                
                <a href="{{ route('customer.book') }}" class="relative px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('customer.book') ? 'bg-gradient-to-r from-pink-500 to-rose-400 text-white shadow-md transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:text-pink-600 dark:hover:text-pink-400 hover:bg-white dark:hover:bg-slate-800' }}">
                    <i class="fa-solid fa-basket-shopping mr-1"></i> จองคิว
                </a>
                
                <a href="{{ route('customer.orders') }}" class="relative px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('customer.orders*') ? 'bg-gradient-to-r from-pink-500 to-rose-400 text-white shadow-md transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:text-pink-600 dark:hover:text-pink-400 hover:bg-white dark:hover:bg-slate-800' }}">
                    <i class="fa-solid fa-motorcycle mr-1"></i> ออเดอร์
                </a>
                
                <a href="{{ route('customer.packages') }}" class="relative px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('customer.packages') ? 'bg-gradient-to-r from-pink-500 to-rose-400 text-white shadow-md transform scale-105' : 'text-gray-600 dark:text-gray-400 hover:text-pink-600 dark:hover:text-pink-400 hover:bg-white dark:hover:bg-slate-800' }}">
                    <i class="fa-solid fa-box-open mr-1"></i> แพ็กเกจ
                </a>
            </div>

            <div class="flex items-center gap-3 md:gap-4">
                
                <a href="{{ route('customer.profile') }}" class="hidden md:flex items-center gap-2 pl-1 pr-3 py-1 rounded-full bg-pink-50 dark:bg-slate-800 border border-pink-100 dark:border-slate-700 hover:shadow-sm hover:border-pink-300 dark:hover:border-slate-500 transition-all group">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-pink-400 to-rose-300 flex items-center justify-center text-white font-bold text-xs shadow-sm group-hover:scale-110 transition-transform">
                        {{ mb_substr(Auth::user()->fullname ?? 'C', 0, 1) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">
                        {{ explode(' ', Auth::user()->fullname)[0] ?? 'ลูกค้า' }}
                    </span>
                </a>

                <button onclick="toggleTheme()" id="theme-toggle-btn" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-pink-100 hover:text-pink-600 dark:hover:bg-slate-600 transition-colors shadow-sm">
                    <i class="fa-solid fa-moon"></i>
                </button>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="button" onclick="confirmLogout(this.closest('form'))" class="hidden md:flex items-center gap-2 text-red-500 dark:text-red-400 hover:text-white bg-red-50 dark:bg-red-500/10 hover:bg-red-500 px-4 py-2 rounded-full text-sm font-medium transition-all shadow-sm">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>ออก</span>
                    </button>
                    <button type="button" onclick="confirmLogout(this.closest('form'))" class="md:hidden text-red-500 dark:text-red-400 w-9 h-9 flex items-center justify-center rounded-full bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors shadow-sm">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>

            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 min-h-screen">
        @yield('content')
    </main>

    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-t border-pink-100 dark:border-slate-700 flex justify-around items-center h-16 z-50 shadow-[0_-5px_20px_rgba(236,72,153,0.15)] dark:shadow-none pb-safe transition-colors">
        <a href="{{ route('customer.main') }}" class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('customer.main') || request()->routeIs('customer.dashboard') ? 'text-pink-500' : 'text-gray-400 dark:text-gray-400 hover:text-pink-400' }}">
            <i class="fa-solid fa-house text-xl mb-1 {{ request()->routeIs('customer.main') || request()->routeIs('customer.dashboard') ? 'drop-shadow-sm' : '' }}"></i>
            <span class="text-[10px] font-medium">หน้าแรก</span>
        </a>
        <a href="{{ route('customer.book') }}" class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('customer.book') ? 'text-pink-500' : 'text-gray-400 dark:text-gray-400 hover:text-pink-400' }}">
            <i class="fa-solid fa-basket-shopping text-xl mb-1 {{ request()->routeIs('customer.book') ? 'drop-shadow-sm' : '' }}"></i>
            <span class="text-[10px] font-medium">จองคิว</span>
        </a>
        <a href="{{ route('customer.orders') }}" class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('customer.orders*') ? 'text-pink-500' : 'text-gray-400 dark:text-gray-400 hover:text-pink-400' }}">
            <i class="fa-solid fa-motorcycle text-xl mb-1 {{ request()->routeIs('customer.orders*') ? 'drop-shadow-sm' : '' }}"></i>
            <span class="text-[10px] font-medium">ออเดอร์</span>
        </a>
        <a href="{{ route('customer.profile') }}" class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('customer.profile') ? 'text-pink-500' : 'text-gray-400 dark:text-gray-400 hover:text-pink-400' }}">
            <i class="fa-solid fa-user text-xl mb-1 {{ request()->routeIs('customer.profile') ? 'drop-shadow-sm' : '' }}"></i>
            <span class="text-[10px] font-medium">โปรไฟล์</span>
        </a>
    </div>

    <script>
        const themeBtn = document.getElementById('theme-toggle-btn');
        const htmlEl = document.documentElement;

        function updateIcon() {
            if (htmlEl.classList.contains('dark')) {
                themeBtn.innerHTML = '<i class="fa-solid fa-sun text-yellow-400"></i>';
            } else {
                themeBtn.innerHTML = '<i class="fa-solid fa-moon"></i>';
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

        function confirmLogout(form) {
            Swal.fire({
                title: 'ออกจากระบบ?',
                text: "คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ Washly?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e', 
                cancelButtonColor: '#9ca3af', 
                confirmButtonText: '<i class="fa-solid fa-right-from-bracket"></i> ใช่, ออกจากระบบ',
                cancelButtonText: 'ยกเลิก',
                background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        }
    </script>

    @if (session('success_order'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'เย้! จองคิวสำเร็จ 🎉',
                    text: '{{ session('success_order') }}',
                    icon: 'success',
                    confirmButtonColor: '#ec4899', 
                    confirmButtonText: 'รับทราบ',
                    background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                    customClass: { popup: 'rounded-3xl' }
                });
            });
        </script>
    @endif
</body>
</html>