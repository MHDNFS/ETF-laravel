<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    /**
     * Cursor-paginated list for the employees table (infinite scroll on the frontend).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min(max((int) $request->input('per_page', 25), 1), 100);

        $employees = Employee::query()
            ->search($request->input('search'))
            ->filterCompany($request->input('company'))
            ->filterBranch($request->input('branch'))
            ->filterStatus($request->input('status'))
            ->orderByDesc('id')
            ->cursorPaginate($perPage);

        return EmployeeResource::collection($employees)->additional([
            'meta' => [
                'next_cursor' => $employees->nextCursor()?->encode(),
                'prev_cursor' => $employees->previousCursor()?->encode(),
                'per_page' => $employees->perPage(),
                'has_more' => $employees->hasMorePages(),
            ],
        ]);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = Employee::query()->create($request->validated());

        return response()->json([
            'message' => 'Employee created successfully.',
            'data' => EmployeeResource::make($employee),
        ], 201);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee->update($request->validated());

        return response()->json([
            'message' => 'Employee updated successfully.',
            'data' => EmployeeResource::make($employee->fresh()),
        ]);
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully.',
        ]);
    }
}
