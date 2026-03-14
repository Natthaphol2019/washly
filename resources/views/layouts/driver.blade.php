<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Washly - Driver Portal</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 min-h-screen flex flex-col transition-colors duration-500 pb-20 md:pb-0">

    {{-- 📱 & 💻 แถบเมนูด้านบน (Top Navbar) --}}
    <nav class="bg-white dark:bg-slate-800 sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16">
                
                {{-- โลโก้ --}}
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-r from-sky-500 to-blue-600 w-10 h-10 rounded-full flex items-center justify-center shadow-md">
                        <i class="fas fa-motorcycle text-white"></i>
                    </div>
                    <div>
                        <span class="font-black text-lg tracking-wide block leading-tight text-blue-600 dark:text-sky-400">WASHLY</span>
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">พนักงานขับรถ</span>
                    </div>
                </div>

                {{-- 💻 เมนูฝั่งขวา (Desktop) --}}
                <div class="hidden md:flex items-center gap-3 sm:gap-4">
                    <button id="theme-toggle-desktop" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center transition focus:outline-none hover:bg-slate-200 dark:hover:bg-slate-600">
                        <i id="theme-toggle-light-icon-desktop" class="hidden fas fa-sun text-yellow-500"></i>
                        <i id="theme-toggle-dark-icon-desktop" class="hidden fas fa-moon text-sky-400"></i>
                    </button>

                    <div class="text-right border-l border-slate-200 dark:border-slate-700 pl-4 transition-colors duration-500">
                        <div class="text-sm font-bold">{{ Auth::user()->fullname }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">ID: {{ Auth::user()->username }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="bg-rose-500 hover:bg-rose-600 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 shadow-sm border-none text-white">
                            <i class="fas fa-sign-out-alt"></i> 
                            <span>ออกจากระบบ</span>
                        </button>
                    </form>
                </div>
                
                {{-- 📱 ปุ่มสลับธีม (Mobile) --}}
                <div class="md:hidden flex items-center">
                    <button id="theme-toggle-mobile" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center transition focus:outline-none hover:bg-slate-200 dark:hover:bg-slate-600">
                        <i id="theme-toggle-light-icon-mobile" class="hidden fas fa-sun text-yellow-500"></i>
                        <i id="theme-toggle-dark-icon-mobile" class="hidden fas fa-moon text-sky-400"></i>
                    </button>
                </div>

            </div>
        </div>
    </nav>

    {{-- 📦 พื้นที่สำหรับใส่เนื้อหา (Content) --}}
    <main class="flex-grow max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    {{-- 📱 Mobile Widget (แถบเมนูด้านล่าง โชว์เฉพาะจอมือถือ) --}}
    <div class="md:hidden fixed bottom-0 w-full bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 z-50 pb-safe shadow-[0_-4px_20px_-10px_rgba(0,0,0,0.05)] transition-colors duration-500">
        <div class="flex justify-around items-center h-16 px-1">
            
            {{-- 1. คิวงาน --}}
            <a href="{{ route('driver.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 group transition-colors duration-500 {{ request()->routeIs('driver.dashboard') ? 'text-blue-600 dark:text-sky-400' : 'text-slate-400 dark:text-slate-500 hover:text-blue-500 dark:hover:text-sky-300' }}">
                <div class="relative flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-300 {{ request()->routeIs('driver.dashboard') ? 'bg-blue-100 dark:bg-slate-700' : 'bg-transparent group-hover:bg-slate-100 dark:group-hover:bg-slate-700' }}">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
                <span class="text-[10px] {{ request()->routeIs('driver.dashboard') ? 'font-bold' : 'font-medium' }}">คิวงาน</span>
            </a>

            {{-- 2. ประวัติ --}}
            <a href="{{ route('driver.history') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 group transition-colors duration-500 {{ request()->routeIs('driver.history') ? 'text-blue-600 dark:text-sky-400' : 'text-slate-400 dark:text-slate-500 hover:text-blue-500 dark:hover:text-sky-300' }}">
                <div class="relative flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-300 {{ request()->routeIs('driver.history') ? 'bg-blue-100 dark:bg-slate-700' : 'bg-transparent group-hover:bg-slate-100 dark:group-hover:bg-slate-700' }}">
                    <i class="fas fa-history text-lg"></i>
                </div>
                <span class="text-[10px] {{ request()->routeIs('driver.history') ? 'font-bold' : 'font-medium' }}">ประวัติ</span>
            </a>

            {{-- 3. โปรไฟล์ --}}
            <a href="{{ route('driver.profile') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 group transition-colors duration-500 {{ request()->routeIs('driver.profile') ? 'text-blue-600 dark:text-sky-400' : 'text-slate-400 dark:text-slate-500 hover:text-blue-500 dark:hover:text-sky-300' }}">
                <div class="relative flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-300 {{ request()->routeIs('driver.profile') ? 'bg-blue-100 dark:bg-slate-700' : 'bg-transparent group-hover:bg-slate-100 dark:group-hover:bg-slate-700' }}">
                    <i class="fas fa-user-circle text-lg"></i>
                </div>
                <span class="text-[10px] {{ request()->routeIs('driver.profile') ? 'font-bold' : 'font-medium' }}">โปรไฟล์</span>
            </a>

            {{-- 4. ออกจากระบบ --}}
            <form method="POST" action="{{ route('logout') }}" class="w-full h-full m-0 flex">
                @csrf
                <button type="submit" class="flex flex-col items-center justify-center w-full h-full space-y-1 group bg-transparent border-none transition-colors duration-500 text-rose-400 hover:text-rose-500 dark:text-rose-500 dark:hover:text-rose-400">
                    <div class="relative flex items-center justify-center w-8 h-8 rounded-xl transition-all duration-300 bg-transparent group-hover:bg-rose-100 dark:group-hover:bg-rose-900/40">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                    </div>
                    <span class="text-[10px] font-medium group-hover:font-bold">ออกจากระบบ</span>
                </button>
            </form>

        </div>
    </div>

    <script>
        function initThemeToggle(btnId, darkIconId, lightIconId) {
            var btn = document.getElementById(btnId);
            var darkIcon = document.getElementById(darkIconId);
            var lightIcon = document.getElementById(lightIconId);
            if (!btn) return;

            if (document.documentElement.classList.contains('dark')) {
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
            }

            btn.addEventListener('click', function() {
                document.querySelectorAll('[id^="theme-toggle-dark-icon"]').forEach(el => el.classList.toggle('hidden'));
                document.querySelectorAll('[id^="theme-toggle-light-icon"]').forEach(el => el.classList.toggle('hidden'));

                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }
        initThemeToggle('theme-toggle-desktop', 'theme-toggle-dark-icon-desktop', 'theme-toggle-light-icon-desktop');
        initThemeToggle('theme-toggle-mobile', 'theme-toggle-dark-icon-mobile', 'theme-toggle-light-icon-mobile');
    </script>
</body>
</html>