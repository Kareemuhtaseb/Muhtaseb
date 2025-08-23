<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of maintenance requests.
     */
    public function index(Request $request): JsonResponse
    {
        $query = MaintenanceRequest::query();

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->has('overdue')) {
            $query->overdue();
        }

        // Load relationships
        $query->with(['reportedBy', 'assignedTo']);

        $maintenanceRequests = $query->orderBy('request_date', 'desc')
                                   ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequests
        ]);
    }

    /**
     * Store a newly created maintenance request.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:plumbing,electrical,hvac,structural,appliance,pest_control,cleaning,landscaping,security,other',
            'request_date' => 'required|date',
            'scheduled_date' => 'nullable|date|after_or_equal:request_date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest = MaintenanceRequest::create([
            'reported_by' => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'request_date' => $request->request_date,
            'scheduled_date' => $request->scheduled_date,
            'estimated_cost' => $request->estimated_cost,
            'notes' => $request->notes,
        ]);

        $maintenanceRequest->load(['reportedBy', 'assignedTo']);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request created successfully',
            'data' => $maintenanceRequest
        ], 201);
    }

    /**
     * Display the specified maintenance request.
     */
    public function show(MaintenanceRequest $maintenanceRequest): JsonResponse
    {
        $maintenanceRequest->load(['reportedBy', 'assignedTo']);

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequest
        ]);
    }

    /**
     * Update the specified maintenance request.
     */
    public function update(Request $request, MaintenanceRequest $maintenanceRequest): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'category' => 'sometimes|in:plumbing,electrical,hvac,structural,appliance,pest_control,cleaning,landscaping,security,other',
            'status' => 'sometimes|in:open,in_progress,completed,cancelled',
            'scheduled_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest->update($request->only([
            'title', 'description', 'priority', 'category', 'status',
            'scheduled_date', 'completed_date', 'estimated_cost', 'actual_cost',
            'notes', 'assigned_to'
        ]));

        $maintenanceRequest->load(['reportedBy', 'assignedTo']);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request updated successfully',
            'data' => $maintenanceRequest
        ]);
    }

    /**
     * Remove the specified maintenance request.
     */
    public function destroy(MaintenanceRequest $maintenanceRequest): JsonResponse
    {
        $maintenanceRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request deleted successfully'
        ]);
    }

    /**
     * Assign maintenance request to a user.
     */
    public function assign(Request $request, MaintenanceRequest $maintenanceRequest): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress'
        ]);

        $maintenanceRequest->load(['reportedBy', 'assignedTo']);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request assigned successfully',
            'data' => $maintenanceRequest
        ]);
    }

    /**
     * Complete a maintenance request.
     */
    public function complete(Request $request, MaintenanceRequest $maintenanceRequest): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'actual_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest->update([
            'status' => 'completed',
            'completed_date' => now(),
            'actual_cost' => $request->actual_cost,
            'notes' => $request->notes,
        ]);

        $maintenanceRequest->load(['reportedBy', 'assignedTo']);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request completed successfully',
            'data' => $maintenanceRequest
        ]);
    }

    /**
     * Get maintenance statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total' => MaintenanceRequest::count(),
            'open' => MaintenanceRequest::where('status', 'open')->count(),
            'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
            'completed' => MaintenanceRequest::where('status', 'completed')->count(),
            'overdue' => MaintenanceRequest::overdue()->count(),
            'by_priority' => [
                'urgent' => MaintenanceRequest::where('priority', 'urgent')->count(),
                'high' => MaintenanceRequest::where('priority', 'high')->count(),
                'medium' => MaintenanceRequest::where('priority', 'medium')->count(),
                'low' => MaintenanceRequest::where('priority', 'low')->count(),
            ],
            'by_category' => MaintenanceRequest::selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get maintenance requests by assigned user.
     */
    public function byAssignedTo(Request $request): JsonResponse
    {
        $userId = $request->get('user_id', Auth::id());

        $maintenanceRequests = MaintenanceRequest::where('assigned_to', $userId)
            ->with(['reportedBy', 'assignedTo'])
            ->orderBy('request_date', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequests
        ]);
    }
}
