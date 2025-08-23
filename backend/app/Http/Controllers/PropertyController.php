<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties with relationships.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'properties_' . md5(serialize($request->all()));
        
        return Cache::remember($cacheKey, 300, function () use ($request) {
            $query = QueryBuilder::for(Property::class)
                ->allowedFilters([
                    AllowedFilter::exact('property_type_id'),
                    AllowedFilter::partial('name'),
                    AllowedFilter::partial('location'),
                    AllowedFilter::scope('active'),
                ])
                ->allowedSorts(['name', 'created_at', 'updated_at']);

            // Apply search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Load relationships based on request
            $withRelations = ['propertyType', 'createdBy'];
            
            if ($request->boolean('with_units')) {
                $withRelations[] = 'units.leases.tenant';
                $withRelations[] = 'units.unitType';
            }
            if ($request->boolean('with_owners')) {
                $withRelations[] = 'owners';
            }
            if ($request->boolean('with_financials')) {
                $withRelations[] = 'income';
                $withRelations[] = 'expenses';
                $withRelations[] = 'monthlySummaries';
            }
            if ($request->boolean('with_maintenance')) {
                $withRelations[] = 'maintenanceRequests.assignedTo';
            }
            if ($request->boolean('with_documents')) {
                $withRelations[] = 'propertyDocuments';
            }

            $properties = $query->with($withRelations)
                               ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $properties->items(),
                'pagination' => [
                    'current_page' => $properties->currentPage(),
                    'last_page' => $properties->lastPage(),
                    'per_page' => $properties->perPage(),
                    'total' => $properties->total(),
                ]
            ]);
        });
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'property_type_id' => 'nullable|exists:property_types,id',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $property = Property::create([
            'name' => $request->name,
            'location' => $request->location,
            'property_type_id' => $request->property_type_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        // Clear related caches
        Cache::forget('properties_*');
        Cache::forget('dashboard_stats');

        return response()->json([
            'success' => true,
            'data' => $property->load(['propertyType', 'createdBy']),
            'message' => 'Property created successfully'
        ], 201);
    }

    /**
     * Display the specified property.
     */
    public function show(Request $request, Property $property): JsonResponse
    {
        $cacheKey = 'property_' . $property->id . '_' . md5(serialize($request->all()));
        
        return Cache::remember($cacheKey, 300, function () use ($request, $property) {
            $withRelations = ['propertyType', 'createdBy', 'updatedBy'];

            // Load relationships based on request
            if ($request->boolean('with_units')) {
                $withRelations[] = 'units.leases.tenant';
                $withRelations[] = 'units.unitType';
            }
            if ($request->boolean('with_owners')) {
                $withRelations[] = 'owners';
            }
            if ($request->boolean('with_financials')) {
                $withRelations[] = 'income';
                $withRelations[] = 'expenses';
                $withRelations[] = 'monthlySummaries';
            }
            if ($request->boolean('with_maintenance')) {
                $withRelations[] = 'maintenanceRequests.assignedTo';
            }
            if ($request->boolean('with_documents')) {
                $withRelations[] = 'propertyDocuments';
            }
            if ($request->boolean('with_payments')) {
                $withRelations[] = 'payments.tenant';
            }

            $property->load($withRelations);

            return response()->json([
                'success' => true,
                'data' => $property
            ]);
        });
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string',
            'property_type_id' => 'sometimes|nullable|exists:property_types,id',
            'notes' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $property->update([
            'name' => $request->name ?? $property->name,
            'location' => $request->location ?? $property->location,
            'property_type_id' => $request->property_type_id ?? $property->property_type_id,
            'notes' => $request->notes ?? $property->notes,
            'updated_by' => Auth::id(),
        ]);

        // Clear related caches
        Cache::forget('properties_*');
        Cache::forget('property_' . $property->id . '_*');
        Cache::forget('dashboard_stats');

        return response()->json([
            'success' => true,
            'data' => $property->load(['propertyType', 'createdBy', 'updatedBy']),
            'message' => 'Property updated successfully'
        ]);
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property): JsonResponse
    {
        $property->delete();

        // Clear related caches
        Cache::forget('properties_*');
        Cache::forget('property_' . $property->id . '_*');
        Cache::forget('dashboard_stats');

        return response()->json([
            'success' => true,
            'message' => 'Property deleted successfully'
        ]);
    }

    /**
     * Get property statistics.
     */
    public function stats(): JsonResponse
    {
        return Cache::remember('property_stats', 600, function () {
            $stats = [
                'total_properties' => Property::count(),
                'active_properties' => Property::active()->count(),
                'total_units' => Property::withCount('units')->get()->sum('units_count'),
                'occupied_units' => Property::with(['units' => function ($q) {
                    $q->where('status', 'occupied');
                }])->get()->sum(function ($property) {
                    return $property->units->count();
                }),
                'total_income' => Property::withSum('income', 'amount')->get()->sum('income_sum_amount'),
                'total_expenses' => Property::withSum('expenses', 'amount')->get()->sum('expenses_sum_amount'),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        });
    }
}
