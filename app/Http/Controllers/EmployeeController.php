<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        $employees  = Employee::all();
        if ($employees->count() > 0) {
            return response()->json([
                "status"=>200,
                "employees"=> $employees
            ],200);
        } else {
            return response()->json([
                "status"=>404,
                "message"=> "No Records Found"
            ],404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "employee_id"=> "required|numeric|min:3|unique:employees,employee_id",
            "employee_name"=> "required | string",
            "phone_number"=> "required | digits:11 | unique:employees,phone_number",
            "department"=> "required | string",
            "designation"=> "required | string",
            "joining_date"=> "required",
            "address"=> "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"=>422,
                "errors"=> $validator->messages()
            ],422);
        } else {
            $employee = Employee::create([
                "employee_id"=> $request->employee_id,
                "employee_name"=> $request->employee_name,
                "phone_number"=> $request->phone_number,
                "department"=> $request->department,
                "designation"=> $request->designation,
                "joining_date"=> $request->joining_date,
                "address"=> $request->address
            ]);
            if ($employee) {
                return response()->json([
                    "status"=>201,
                    "message"=> "Employee Added Successfully"
                ],201);
            } else {
                return response()->json([
                    "status"=>500,
                    "message"=> "Something Went Wrong"
                ],500);
            }
        }
    }
    public function show($id): JsonResponse
    {
        $employee = Employee::find($id);
        if ($employee) {
            return response()->json([
                "status"=>200,
                "employee"=> $employee
            ],200);
        } else {
            return response()->json([
                "status"=>404,
                "message"=> "No Employee Found"
            ],404);
        }

    }

    public function edit($id): JsonResponse
    {
        $employee = Employee::find($id);
        if ($employee) {
            return response()->json([
                "status"=>200,
                "employee"=> $employee
            ],200);
        } else {
            return response()->json([
                "status"=>404,
                "message"=> "No Employee Id Found"
            ],404);
        }

    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "employee_id"=> "required | numeric | min:3",
            "employee_name"=> "required | string",
            "phone_number"=> "required | digits:11 | unique:employees,phone_number," .$id,
            "department"=> "required | string",
            "designation"=> "required | string",
            "joining_date"=> "required",
            "address"=> "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"=>422,
                "errors"=> $validator->messages()
            ],422);
        } else {
            $employee = Employee::find($id);
            if ($employee) {
                $employee->update([
                    "employee_id"=> $request->employee_id,
                    "employee_name"=> $request->employee_name,
                    "phone_number"=> $request->phone_number,
                    "department"=> $request->department,
                    "designation"=> $request->designation,
                    "joining_date"=> $request->joining_date,
                    "address"=> $request->address
                ]);
                return response()->json([
                    "status"=>200,
                    "message"=> "Employee Updated Successfully"
                ],200);
            } else {
                return response()->json([
                    "status"=>404,
                    "message"=> "No Such Employee Found"
                ],404);
            }
        }
    }

    public function delete($id): JsonResponse
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->delete();
            return response()->json([
                "status"=>200,
                "message"=> "Employee Deleted Successfully"
            ],200);
        } else {
            return response()->json([
                "status"=>404,
                "message"=> "No Such Employee Found"
            ],404);
        }
    }
}
