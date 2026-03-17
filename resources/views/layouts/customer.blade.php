<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'บริการรับส่งซักผ้า')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

            <a href="{{ route('customer.main') }}" class="text-2xl font-extrabold flex items-center gap-3 hover:scale-105 transition-transform duration-300 group flex-1 min-w-0 md:flex-none md:min-w-[280px]">
                <span class="h-12 w-36 sm:w-48 overflow-hidden flex items-center shrink-0">
                    <img src="{{ asset('Brand.png') }}" alt="Washly Logo" class="h-20 sm:h-24 w-auto max-w-none -ml-4 sm:-ml-5 drop-shadow-sm">
                </span>
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

                <div class="relative">
                    <button type="button" id="notif-toggle"
                        class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-pink-100 hover:text-pink-600 dark:hover:bg-slate-600 transition-colors shadow-sm relative">
                        <i class="fa-solid fa-bell"></i>
                        <span id="notif-badge"
                            class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-slate-800">0</span>
                    </button>

                    <div id="notif-panel"
                        class="hidden absolute right-0 mt-2 w-80 max-w-[90vw] bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-xl z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                            <p class="font-semibold text-sm">การแจ้งเตือน</p>
                            <button type="button" id="notif-read-all" class="text-xs text-pink-500 hover:text-pink-600">อ่านแล้วทั้งหมด</button>
                        </div>
                        <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700"></div>
                        <a href="{{ route('notifications.index') }}" class="block text-center text-xs text-gray-500 dark:text-gray-400 py-2 bg-gray-50 dark:bg-slate-900/40 hover:text-pink-500">ดูทั้งหมด</a>
                    </div>
                </div>
                
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
        <a href="{{ route('customer.packages') }}" class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('customer.packages') ? 'text-pink-500' : 'text-gray-400 dark:text-gray-400 hover:text-pink-400' }}">
            <i class="fa-solid fa-box-open text-xl mb-1 {{ request()->routeIs('customer.packages') ? 'drop-shadow-sm' : '' }}"></i>
            <span class="text-[10px] font-medium">แพ็คเกจ</span>
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

        const notifToggle = document.getElementById('notif-toggle');
        const notifPanel = document.getElementById('notif-panel');
        const notifList = document.getElementById('notif-list');
        const notifBadge = document.getElementById('notif-badge');
        const notifReadAllBtn = document.getElementById('notif-read-all');
        const realtimeStatusEl = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const currentUserId = @json((string) auth()->id());
        let previousUnreadCount = null;
        let realtimeStateBound = false;
        let lastRealtimePopupAt = 0;

        function setRealtimeStatus(state) {
            if (!realtimeStatusEl) {
                return;
            }

            const classes = [
                'bg-emerald-100', 'text-emerald-700', 'dark:bg-emerald-900/40', 'dark:text-emerald-300',
                'bg-amber-100', 'text-amber-700', 'dark:bg-amber-900/40', 'dark:text-amber-300',
                'bg-red-100', 'text-red-700', 'dark:bg-red-900/40', 'dark:text-red-300',
                'bg-slate-200', 'text-slate-700', 'dark:bg-slate-700', 'dark:text-slate-200'
            ];
            realtimeStatusEl.classList.remove(...classes);

            if (state === 'connected') {
                realtimeStatusEl.classList.add('bg-emerald-100', 'text-emerald-700', 'dark:bg-emerald-900/40', 'dark:text-emerald-300');
                realtimeStatusEl.innerHTML = '<span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span> Realtime: Connected';
                return;
            }

            if (state === 'connecting' || state === 'initialized') {
                realtimeStatusEl.classList.add('bg-amber-100', 'text-amber-700', 'dark:bg-amber-900/40', 'dark:text-amber-300');
                realtimeStatusEl.innerHTML = '<span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span> Realtime: Connecting';
                return;
            }

            if (state === 'unavailable' || state === 'disconnected' || state === 'failed') {
                realtimeStatusEl.classList.add('bg-red-100', 'text-red-700', 'dark:bg-red-900/40', 'dark:text-red-300');
                realtimeStatusEl.innerHTML = '<span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span> Realtime: Disconnected';
                return;
            }

            realtimeStatusEl.classList.add('bg-slate-200', 'text-slate-700', 'dark:bg-slate-700', 'dark:text-slate-200');
            realtimeStatusEl.innerHTML = '<span class="inline-block w-1.5 h-1.5 rounded-full bg-current"></span> Realtime: Unknown';
        }

        function bindRealtimeConnectionState() {
            if (realtimeStateBound) {
                return;
            }

            const connection = window.Echo?.connector?.pusher?.connection;
            if (!connection) {
                setRealtimeStatus('connecting');
                setTimeout(bindRealtimeConnectionState, 1200);
                return;
            }

            realtimeStateBound = true;
            setRealtimeStatus(connection.state || 'connecting');

            connection.bind('state_change', (states) => {
                setRealtimeStatus(states?.current || 'unknown');
            });
        }


        function showRealtimeToast(notification) {
            const popupIcon = notification.level === 'success' ? 'success' : (notification.level === 'warning' ? 'warning' : 'info');
            lastRealtimePopupAt = Date.now();

            Swal.fire({
                icon: popupIcon,
                title: notification.title || 'มีการแจ้งเตือนใหม่',
                text: notification.message || '',
                showConfirmButton: false,
                timer: 3600,
                timerProgressBar: true,
                width: window.innerWidth < 640 ? '90%' : 420,
                background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: { popup: 'rounded-3xl' }
            });
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function getUnreadCountFromBadge() {
            if (!notifBadge || notifBadge.classList.contains('hidden')) {
                return 0;
            }

            const parsed = parseInt(notifBadge.textContent, 10);
            return Number.isFinite(parsed) ? parsed : 0;
        }

        function setUnreadBadge(unreadCount) {
            if (!notifBadge) {
                return;
            }

            if (unreadCount > 0) {
                notifBadge.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
                notifBadge.classList.remove('hidden');
            } else {
                notifBadge.classList.add('hidden');
            }
        }

        function prependRealtimeNotification(notification) {
            if (!notifList) {
                return;
            }

            const title = escapeHtml(notification.title || 'มีการแจ้งเตือนใหม่');
            const message = escapeHtml(notification.message || 'มีการอัปเดตใหม่ในระบบ');
            const url = escapeHtml(notification.url || '#');
            const level = notification.level || 'info';

            const badgeClassByLevel = {
                success: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                warning: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                info: 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300',
            };

            const iconByLevel = {
                success: 'fa-solid fa-circle-check',
                warning: 'fa-solid fa-triangle-exclamation',
                info: 'fa-solid fa-circle-info',
            };

            const badgeClass = badgeClassByLevel[level] || badgeClassByLevel.info;
            const icon = iconByLevel[level] || iconByLevel.info;

            const html = `
                <a href="${url}" class="notif-item block px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors bg-pink-50 dark:bg-pink-900/10">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex items-center justify-center w-7 h-7 rounded-full ${badgeClass}">
                            <i class="${icon} text-[11px]"></i>
                        </span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-100">${title}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${message}</p>
                            <p class="text-[11px] text-gray-400 mt-1">เมื่อสักครู่</p>
                        </div>
                    </div>
                </a>
            `;

            if (notifList.innerHTML.includes('ยังไม่มีการแจ้งเตือน')) {
                notifList.innerHTML = html;
                return;
            }

            notifList.insertAdjacentHTML('afterbegin', html);
        }

        function renderNotificationItem(item) {
            const link = item.url || '#';
            const unreadClass = item.is_read ? 'bg-white dark:bg-slate-800' : 'bg-pink-50 dark:bg-pink-900/10';
            const badgeClass = item.badge_class || 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
            const icon = item.icon || 'fa-solid fa-circle-info';

            return `
                <a href="${link}" data-id="${item.id}" class="notif-item block px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors ${unreadClass}">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex items-center justify-center w-7 h-7 rounded-full ${badgeClass}">
                            <i class="${icon} text-[11px]"></i>
                        </span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-100">${item.title}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${item.message}</p>
                            <p class="text-[11px] text-gray-400 mt-1">${item.created_at || ''}</p>
                        </div>
                    </div>
                </a>
            `;
        }

        async function fetchNotifications() {
            try {
                const response = await fetch('{{ route('api.notifications') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                const unreadCount = Number(data.unread_count || 0);

                if (previousUnreadCount !== null && unreadCount > previousUnreadCount) {
                    const justShownByRealtime = Date.now() - lastRealtimePopupAt < 5000;
                    if (!justShownByRealtime) {
                        Swal.fire({
                            icon: 'info',
                            title: 'มีการแจ้งเตือนใหม่',
                            text: `คุณมีข้อความใหม่ ${unreadCount - previousUnreadCount} รายการ`,
                            showConfirmButton: false,
                            timer: 2800,
                            timerProgressBar: true,
                            width: window.innerWidth < 640 ? '90%' : 420,
                            background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                            color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                            customClass: { popup: 'rounded-3xl' }
                        });
                    }
                }

                setUnreadBadge(unreadCount);

                previousUnreadCount = unreadCount;

                if (data.notifications.length === 0) {
                    notifList.innerHTML = '<p class="px-4 py-6 text-center text-sm text-gray-400">ยังไม่มีการแจ้งเตือน</p>';
                    return;
                }

                notifList.innerHTML = data.notifications.map(renderNotificationItem).join('');
            } catch (error) {
                console.error('โหลดการแจ้งเตือนไม่สำเร็จ', error);
            }
        }

        async function readAllNotifications() {
            await fetch('{{ route('api.notifications.read_all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
        }

        async function readOneNotification(id) {
            await fetch(`/api/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
        }

        notifToggle?.addEventListener('click', async function() {
            notifPanel.classList.toggle('hidden');
            if (!notifPanel.classList.contains('hidden')) {
                await fetchNotifications();
            }
        });

        notifList?.addEventListener('click', function(event) {
            const item = event.target.closest('.notif-item');
            if (!item) return;
            const id = item.getAttribute('data-id');
            if (id) {
                readOneNotification(id);
            }
        });

        notifReadAllBtn?.addEventListener('click', async function() {
            await readAllNotifications();
            await fetchNotifications();
        });

        document.addEventListener('click', function(event) {
            if (!notifPanel.contains(event.target) && !notifToggle.contains(event.target)) {
                notifPanel.classList.add('hidden');
            }
        });

        function bindRealtimeNotifications() {
            if (!window.Echo || !currentUserId) {
                setRealtimeStatus('disconnected');
                return;
            }

            bindRealtimeConnectionState();

            window.Echo.private(`App.Models.User.${currentUserId}`)
                .notification(async (notification) => {
                    const payload = notification || {};
                    window.dispatchEvent(new CustomEvent('washly:notification-received', {
                        detail: payload,
                    }));

                    showRealtimeToast(payload);
                    prependRealtimeNotification(payload);

                    const nextUnreadCount = getUnreadCountFromBadge() + 1;
                    setUnreadBadge(nextUnreadCount);
                    previousUnreadCount = nextUnreadCount;

                    setTimeout(fetchNotifications, 1200);
                });
        }

        fetchNotifications();
        bindRealtimeNotifications();
        setInterval(fetchNotifications, 20000);
    </script>

    @if (session('success_order'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'เย้! จองคิวสำเร็จ 🎉',
                    text: '{{ session('success_order') }}',
                    icon: 'success',
                    confirmButtonColor: '#ec4899',
                    confirmButtonText: 'สแกน QR ชำระเงิน',
                    denyButtonText: 'ดูออเดอร์ของฉัน',
                    showDenyButton: true,
                    background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ไปหน้าออเดอร์เพื่อดู QR Code
                        window.location.href = '{{ route('customer.orders') }}';
                    } else if (result.isDenied) {
                        window.location.href = '{{ route('customer.orders') }}';
                    }
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>