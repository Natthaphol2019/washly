<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ระบบจัดการหลังบ้าน - Washly Admin')</title>

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

<body
    class="washly-spectrum-bg washly-shell dark:bg-slate-900 text-gray-800 dark:text-gray-200 font-sans antialiased transition-colors duration-300 overflow-hidden">

    <div class="flex h-screen w-full">

        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 dark:bg-slate-950 text-gray-300 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl md:shadow-none border-r border-slate-800">

            <div class="h-16 flex items-center justify-between px-6 bg-slate-950/50 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}"
                    class="text-xl font-bold washly-brand-text flex items-center gap-2">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Washly Admin
                </a>
                <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.main') || request()->routeIs('admin.dashboard') ? 'washly-brand-btn text-white shadow-lg shadow-sky-400/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i>
                    <span class="font-medium">รายงานสรุปภาพรวม</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.orders.*') ? 'washly-brand-btn text-white shadow-lg shadow-sky-400/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-list-check w-5 text-center"></i>
                    <span class="font-medium">จัดการออเดอร์</span>
                </a>

                <a href="{{ route('admin.packages.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.packages.*') ? 'washly-brand-btn text-white shadow-lg shadow-sky-400/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                    <span class="font-medium">แพ็กเกจและเมนูเสริม</span>
                </a>
                <a href="{{ route('admin.customers.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.customers.*') ? 'washly-brand-btn text-white shadow-lg shadow-sky-400/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span class="font-medium">จัดการลูกค้า</span>
                </a>

                <a href="{{ route('admin.staff.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.staff.*') ? 'washly-brand-btn text-white shadow-lg shadow-sky-400/30' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-tie w-5 text-center"></i>
                    <span class="font-medium">จัดการพนักงาน</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full washly-brand-btn flex items-center justify-center text-white font-bold">
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
                class="h-16 bg-white/80 dark:bg-slate-800 shadow-sm border-b border-sky-100 dark:border-slate-700 flex items-center justify-between px-4 sm:px-6 transition-colors z-30 washly-card">

                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-500 dark:text-gray-400 hover:text-sky-600 focus:outline-none p-2 rounded-lg bg-gray-50 dark:bg-slate-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex-1"></div>

                <div class="flex items-center gap-3 sm:gap-4">

                    <button onclick="toggleTheme()" id="theme-toggle-btn"
                        class="text-sm w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition flex items-center justify-center text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-moon"></i>
                    </button>

                    <div class="relative">
                        <button type="button" id="notif-toggle"
                            class="relative p-2 text-gray-500 hover:text-sky-600 transition-colors rounded-full hover:bg-sky-50 dark:hover:bg-slate-700">
                            <i class="fa-solid fa-bell text-xl"></i>
                            <span id="notif-badge"
                                class="hidden absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-slate-800 shadow-sm">
                                0
                            </span>
                        </button>

                        <div id="notif-panel"
                            class="hidden absolute right-0 mt-2 w-80 max-w-[90vw] bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-xl z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                                <p class="font-semibold text-sm">การแจ้งเตือน</p>
                                <button type="button" id="notif-read-all" class="text-xs text-sky-600 hover:text-sky-700">อ่านแล้วทั้งหมด</button>
                            </div>
                            <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-slate-700"></div>
                            <a href="{{ route('notifications.index') }}" class="block text-center text-xs text-gray-500 dark:text-gray-400 py-2 bg-gray-50 dark:bg-slate-900/40 hover:text-sky-600">ดูทั้งหมด</a>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="m-0 flex items-center">
                        @csrf
                        <button type="button" onclick="confirmLogout(this.closest('form'))"
                            class="hidden sm:flex items-center text-red-500 dark:text-red-400 hover:text-white hover:bg-red-500 border border-red-500 dark:border-red-400 px-4 py-2 rounded-full font-medium transition-all text-sm ml-2">
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> ออกจากระบบ
                        </button>

                        <button type="button" onclick="confirmLogout(this.closest('form'))"
                            class="sm:hidden text-red-500 dark:text-red-400 w-10 h-10 flex items-center justify-center rounded-full bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors ml-2">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>

            <main
                class="flex-1 overflow-x-hidden overflow-y-auto bg-transparent dark:bg-slate-900 p-4 sm:p-6 lg:p-8 transition-colors relative">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        // ==========================================
        // 1. ระบบเปิด/ปิด Sidebar (สำหรับมือถือ)
        // ==========================================
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // ==========================================
        // 2. ระบบสลับธีม สว่าง/มืด
        // ==========================================
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

        // ==========================================
        // 3. ระบบยืนยันก่อนออกจากระบบ (SweetAlert2)
        // ==========================================
        function confirmLogout(form) {
            Swal.fire({
                title: 'ออกจากระบบแอดมิน?',
                text: "คุณต้องการออกจากระบบจัดการหลังบ้านใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1980d5',
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

        function showWashlyAlert(options) {
            Swal.fire({
                confirmButtonColor: '#1980d5',
                confirmButtonText: 'รับทราบ',
                background: htmlEl.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: htmlEl.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                customClass: {
                    popup: 'rounded-3xl'
                },
                ...options,
            });
        }

        // ==========================================
        // 🔔 4. ระบบแจ้งเตือน (Database Notifications)
        // ==========================================
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
                info: 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300',
            };

            const iconByLevel = {
                success: 'fa-solid fa-circle-check',
                warning: 'fa-solid fa-triangle-exclamation',
                info: 'fa-solid fa-circle-info',
            };

            const badgeClass = badgeClassByLevel[level] || badgeClassByLevel.info;
            const icon = iconByLevel[level] || iconByLevel.info;

            const html = `
                <a href="${url}" class="notif-item block px-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors bg-sky-50 dark:bg-sky-900/10">
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
            const unreadClass = item.is_read ? 'bg-white dark:bg-slate-800' : 'bg-sky-50 dark:bg-sky-900/10';
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showWashlyAlert({
                    title: 'สำเร็จ',
                    text: @json(session('success')),
                    icon: 'success'
                });
            @endif

            @if (session('info'))
                showWashlyAlert({
                    title: 'แจ้งให้ทราบ',
                    text: @json(session('info')),
                    icon: 'info'
                });
            @endif

            @if (session('error'))
                showWashlyAlert({
                    title: 'เกิดข้อผิดพลาด',
                    text: @json(session('error')),
                    icon: 'error'
                });
            @endif
        });
    </script>
</body>

</html>
