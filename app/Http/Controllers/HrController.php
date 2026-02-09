<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class HRController extends Controller
{
    public function dashboard()
    {
        return view('HR.dashboard');
    }

    //employees//
    public function employees()
    {
        $employees = Employee::with(['department', 'designation'])->get();
        $departments = Department::all();
        $designations = Designation::all();
        
        return view('HR.employees', compact('employees', 'departments', 'designations'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:10',
            'bank_account' => 'nullable|string|max:20',
            'ifsc_code' => 'nullable|string|max:11',
            'blood_group' => 'nullable|string|max:5',
        ]);

        // Generate employee ID (you can customize this logic)
        $validated['employee_id'] = 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT);

        Employee::create($validated);

        return redirect()->route('hr.employees')->with('success', 'Employee added successfully!');
    }
}