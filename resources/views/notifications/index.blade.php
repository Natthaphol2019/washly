@extends($layout)

@section('title', $pageTitle)

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">การแจ้งเตือนทั้งหมด</h1>
                <p id="unread-counter" data-unread="{{ $unreadCount }}" class="text-sm text-gray-500 dark:text-gray-400 mt-1">ยังไม่ได้อ่าน {{ $unreadCount }} รายการ</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('notifications.index', ['status' => 'all']) }}"
                    class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $status === 'all' ? 'bg-pink-500 text-white' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-700' }}">
                    ทั้งหมด
                </a>
                <a href="{{ route('notifications.index', ['status' => 'unread']) }}"
                    class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $status === 'unread' ? 'bg-pink-500 text-white' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-700' }}">
                    ยังไม่อ่าน
                </a>
                <a href="{{ route('notifications.index', ['status' => 'read']) }}"
                    class="px-3 py-2 rounded-xl text-sm font-medium transition-colors {{ $status === 'read' ? 'bg-pink-500 text-white' : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-700' }}">
                    อ่านแล้ว
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden shadow-sm">
            @forelse($notifications as $item)
                <div data-notif-row data-id="{{ $item['id'] }}"
                    class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 last:border-b-0 transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/40 {{ $item['is_read'] ? '' : 'bg-pink-50/70 dark:bg-pink-900/10' }}">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex items-center justify-center w-8 h-8 rounded-full {{ $item['badge_class'] }}">
                            <i class="{{ $item['icon'] }} text-xs"></i>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $item['title'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $item['message'] }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $item['created_at'] }}</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            @if (!$item['is_read'])
                                <button type="button" data-mark-read="{{ $item['id'] }}"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-medium bg-pink-100 text-pink-600 hover:bg-pink-200 dark:bg-pink-900/30 dark:text-pink-300 dark:hover:bg-pink-900/50 transition-colors">
                                    อ่านแล้ว
                                </button>
                            @endif

                            @if ($item['url'])
                                <a href="{{ $item['url'] }}"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-slate-700 dark:text-gray-200 dark:hover:bg-slate-600 transition-colors">
                                    เปิด
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-bell-slash text-3xl mb-3 text-gray-300 dark:text-gray-600"></i>
                    <p>ยังไม่มีการแจ้งเตือนในรายการนี้</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5">
            {{ $notifications->links() }}
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const unreadCounter = document.getElementById('unread-counter');
        const currentFilter = '{{ $status }}';

        function setUnreadCounter(nextValue) {
            const value = Math.max(0, Number(nextValue || 0));
            unreadCounter.dataset.unread = value;
            unreadCounter.textContent = `ยังไม่ได้อ่าน ${value} รายการ`;
        }

        document.querySelectorAll('[data-mark-read]').forEach((button) => {
            button.addEventListener('click', async function() {
                const id = this.getAttribute('data-mark-read');
                if (!id) return;

                try {
                    await fetch(`/api/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const row = document.querySelector(`[data-notif-row][data-id="${id}"]`);
                    if (row) {
                        row.classList.remove('bg-pink-50/70', 'dark:bg-pink-900/10');
                    }

                    this.remove();

                    const currentUnread = Number(unreadCounter.dataset.unread || 0);
                    setUnreadCounter(currentUnread - 1);

                    if (currentFilter === 'unread' && row) {
                        row.remove();
                    }
                } catch (error) {
                    console.error('ไม่สามารถอัปเดตสถานะอ่านแล้วได้', error);
                }
            });
        });
    </script>
@endsection
