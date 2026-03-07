<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private function levelMeta(string $level): array
    {
        return match ($level) {
            'success' => [
                'icon' => 'fa-solid fa-circle-check',
                'icon_class' => 'text-green-500',
                'badge_class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            ],
            'warning' => [
                'icon' => 'fa-solid fa-triangle-exclamation',
                'icon_class' => 'text-amber-500',
                'badge_class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            ],
            'danger' => [
                'icon' => 'fa-solid fa-circle-xmark',
                'icon_class' => 'text-red-500',
                'badge_class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            ],
            default => [
                'icon' => 'fa-solid fa-circle-info',
                'icon_class' => 'text-blue-500',
                'badge_class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            ],
        };
    }

    private function mapNotification($notification): array
    {
        $level = $notification->data['level'] ?? 'info';
        $meta = $this->levelMeta($level);

        return [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'แจ้งเตือนใหม่',
            'message' => $notification->data['message'] ?? '',
            'url' => $notification->data['url'] ?? null,
            'level' => $level,
            'is_read' => $notification->read_at !== null,
            'created_at' => optional($notification->created_at)->diffForHumans(),
            'icon' => $meta['icon'],
            'icon_class' => $meta['icon_class'],
            'badge_class' => $meta['badge_class'],
        ];
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($notification) => $this->mapNotification($notification))
            ->values();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function page(Request $request)
    {
        $status = $request->query('status', 'all');
        $user = $request->user();

        $query = $user->notifications()->latest();

        if ($status === 'unread') {
            $query->whereNull('read_at');
        }

        if ($status === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();

        $mappedNotifications = $notifications->getCollection()
            ->map(fn($notification) => $this->mapNotification($notification));

        $notifications->setCollection($mappedNotifications);

        return view('notifications.index', [
            'notifications' => $notifications,
            'status' => $status,
            'unreadCount' => $user->unreadNotifications()->count(),
            'layout' => in_array($user->role, ['admin', 'staff']) ? 'layouts.admin' : 'layouts.customer',
            'pageTitle' => 'การแจ้งเตือนทั้งหมด',
        ]);
    }

    public function readAll(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function readOne(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if ($notification && $notification->read_at === null) {
            $notification->markAsRead();
        }

        return response()->json(['ok' => true]);
    }
}
