<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Notification::query();

        // Filter by user if not admin
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->has('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        // Load relationships
        $query->with(['user', 'property', 'tenant', 'owner']);

        $notifications = $query->orderBy('created_at', 'desc')
                              ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'property_id' => 'nullable|exists:properties,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'owner_id' => 'nullable|exists:owners,id',
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'property_id' => $request->property_id,
            'tenant_id' => $request->tenant_id,
            'owner_id' => $request->owner_id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'priority' => $request->priority,
            'data' => $request->data,
            'status' => 'unread',
        ]);

        $notification->load(['user', 'property', 'tenant', 'owner']);

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'data' => $notification
        ], 201);
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification): JsonResponse
    {
        // Check if user has access to this notification
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $notification->load(['user', 'property', 'tenant', 'owner']);

        return response()->json([
            'success' => true,
            'data' => $notification
        ]);
    }

    /**
     * Update the specified notification.
     */
    public function update(Request $request, Notification $notification): JsonResponse
    {
        // Check if user has access to this notification
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'status' => 'sometimes|required|in:unread,read,archived',
            'data' => 'sometimes|nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $notification->update($request->only([
            'title', 'message', 'priority', 'status', 'data'
        ]));

        $notification->load(['user', 'property', 'tenant', 'owner']);

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully',
            'data' => $notification
        ]);
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification): JsonResponse
    {
        // Check if user has access to this notification
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        // Check if user has access to this notification
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(Notification $notification): JsonResponse
    {
        // Check if user has access to this notification
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread'
        ]);
    }

    /**
     * Archive notification.
     */
    public function archive(Notification $notification): JsonResponse
    {
        // Check if user has access to this notification
        if (Auth::user()->role !== 'admin' && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $notification->archive();

        return response()->json([
            'success' => true,
            'message' => 'Notification archived'
        ]);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount(): JsonResponse
    {
        $query = Notification::query();

        // Filter by user if not admin
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $count = $query->where('status', 'unread')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $count
            ]
        ]);
    }

    /**
     * Get user's notifications.
     */
    public function userNotifications(Request $request): JsonResponse
    {
        $query = Notification::where('user_id', Auth::id());

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->with(['property', 'tenant', 'owner'])
                              ->orderBy('created_at', 'desc')
                              ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark all user notifications as read.
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id', Auth::id())
                   ->where('status', 'unread')
                   ->update([
                       'status' => 'read',
                       'read_at' => now()
                   ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get notification statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Notification::query();

        // Filter by user if not admin
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        $stats = [
            'total_notifications' => $query->count(),
            'unread_notifications' => $query->clone()->where('status', 'unread')->count(),
            'read_notifications' => $query->clone()->where('status', 'read')->count(),
            'archived_notifications' => $query->clone()->where('status', 'archived')->count(),
            'urgent_notifications' => $query->clone()->where('priority', 'urgent')->count(),
            'high_priority_notifications' => $query->clone()->where('priority', 'high')->count(),
            'recent_notifications' => $query->clone()->recent(7)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
