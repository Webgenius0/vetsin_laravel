<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    use ApiResponse;

    /**
     * Get all notifications for the authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        try {
            $user = auth()->user();
            $perPage = min($request->get('per_page', 20), 50);
            
            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Transform notifications to include formatted data
            $formattedNotifications = $notifications->getCollection()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'unknown',
                    'title' => $notification->data['title'] ?? '',
                    'body' => $notification->data['body'] ?? '',
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'is_read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

            $notifications->setCollection($formattedNotifications);

            return $this->success($notifications, 'Notifications retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get unread notifications count
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadCount()
    {
        try {
            $user = auth()->user();
            $unreadCount = $user->unreadNotifications()->count();

            return $this->success([
                'unread_count' => $unreadCount
            ], 'Unread count retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Mark a specific notification as read
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required|string|exists:notifications,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $notification = $user->notifications()->where('id', $request->notification_id)->first();

            if (!$notification) {
                return $this->error([], 'Notification not found', 404);
            }

            if (!$notification->read_at) {
                $notification->markAsRead();
            }

            return $this->success([
                'id' => $notification->id,
                'read_at' => $notification->read_at,
            ], 'Notification marked as read successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Mark all notifications as read for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        try {
            $user = auth()->user();
            $unreadNotifications = $user->unreadNotifications;
            
            $count = $unreadNotifications->count();
            $user->unreadNotifications->markAsRead();

            return $this->success([
                'marked_count' => $count
            ], "All {$count} notifications marked as read successfully", 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Delete a specific notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_id' => 'required|string|exists:notifications,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();
            $notification = $user->notifications()->where('id', $request->notification_id)->first();

            if (!$notification) {
                return $this->error([], 'Notification not found', 404);
            }

            $notification->delete();

            return $this->success([], 'Notification deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Delete all notifications for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllNotifications()
    {
        try {
            $user = auth()->user();
            $count = $user->notifications()->count();
            $user->notifications()->delete();

            return $this->success([
                'deleted_count' => $count
            ], "All {$count} notifications deleted successfully", 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Get notification statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationStats()
    {
        try {
            $user = auth()->user();
            
            $totalNotifications = $user->notifications()->count();
            $unreadNotifications = $user->unreadNotifications()->count();
            $readNotifications = $totalNotifications - $unreadNotifications;
            
            // Get notification types count
            $notificationTypes = $user->notifications()
                ->get()
                ->groupBy(function ($notification) {
                    return $notification->data['type'] ?? 'unknown';
                })
                ->map(function ($group) {
                    return $group->count();
                });

            return $this->success([
                'total_notifications' => $totalNotifications,
                'unread_notifications' => $unreadNotifications,
                'read_notifications' => $readNotifications,
                'notification_types' => $notificationTypes,
            ], 'Notification statistics retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
} 