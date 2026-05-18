<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EmployeeResource::collection(
            Employee::query()->orderBy('id')->get()
        );
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
