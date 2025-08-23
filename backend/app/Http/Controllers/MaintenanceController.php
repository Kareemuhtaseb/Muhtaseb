<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
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

        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        if ($request->has('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->has('overdue')) {
            $query->overdue();
        }

        // Load relationships
        $query->with(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);

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
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:plumbing,electrical,hvac,structural,appliance,other',
            'request_date' => 'required|date',
            'scheduled_date' => 'nullable|date|after_or_equal:request_date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest = MaintenanceRequest::create([
            'property_id' => $request->property_id,
            'unit_id' => $request->unit_id,
            'tenant_id' => $request->tenant_id,
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

        $maintenanceRequest->load(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);

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
        $maintenanceRequest->load(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);

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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'category' => 'sometimes|required|in:plumbing,electrical,hvac,structural,appliance,other',
            'status' => 'sometimes|required|in:open,in_progress,completed,cancelled',
            'scheduled_date' => 'sometimes|nullable|date',
            'completed_date' => 'sometimes|nullable|date',
            'estimated_cost' => 'sometimes|nullable|numeric|min:0',
            'actual_cost' => 'sometimes|nullable|numeric|min:0',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
            'notes' => 'sometimes|nullable|string',
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
            'assigned_to', 'notes'
        ]));

        $maintenanceRequest->load(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);

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
            'scheduled_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest->update([
            'assigned_to' => $request->assigned_to,
            'scheduled_date' => $request->scheduled_date,
            'status' => 'in_progress',
            'notes' => $request->notes,
        ]);

        $maintenanceRequest->load(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);

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

        $maintenanceRequest->load(['property', 'unit', 'tenant', 'reportedBy', 'assignedTo']);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request completed successfully',
            'data' => $maintenanceRequest
        ]);
    }

    /**
     * Get maintenance statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = MaintenanceRequest::query();

        if ($request->has('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        $stats = [
            'total_requests' => $query->count(),
            'open_requests' => $query->clone()->where('status', 'open')->count(),
            'in_progress_requests' => $query->clone()->where('status', 'in_progress')->count(),
            'completed_requests' => $query->clone()->where('status', 'completed')->count(),
            'overdue_requests' => $query->clone()->overdue()->count(),
            'urgent_requests' => $query->clone()->where('priority', 'urgent')->count(),
            'high_priority_requests' => $query->clone()->where('priority', 'high')->count(),
            'total_estimated_cost' => $query->clone()->sum('estimated_cost'),
            'total_actual_cost' => $query->clone()->where('status', 'completed')->sum('actual_cost'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get maintenance requests by property.
     */
    public function byProperty(Property $property): JsonResponse
    {
        $maintenanceRequests = $property->maintenanceRequests()
            ->with(['unit', 'tenant', 'reportedBy', 'assignedTo'])
            ->orderBy('request_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequests
        ]);
    }

    /**
     * Get maintenance requests by unit.
     */
    public function byUnit(Unit $unit): JsonResponse
    {
        $maintenanceRequests = $unit->maintenanceRequests()
            ->with(['property', 'tenant', 'reportedBy', 'assignedTo'])
            ->orderBy('request_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequests
        ]);
    }
}
