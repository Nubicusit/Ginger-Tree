<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HRController extends Controller
{
    public function dashboard()
    {
        return view('HR.dashboard', [
            'totalEmployees'      => Employee::count(),
            'newEmployees'        => Employee::whereMonth('created_at', now()->month)->count(),
            'todayPresent'        => Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'todayAbsent'         => Employee::count() - Attendance::whereDate('date', today())->where('status', 'present')->count(),
            'pendingLeaves'       => 17,
            'approvedLeaves'      => 45,
            'monthlyPayroll'      => 42.5,
            'pendingPayroll'      => 3,
            'activeAnnouncements' => 6,
            'draftAnnouncements'  => 2,
        ]);
    }

    // ===== EMPLOYEE MANAGEMENT =====
    public function employees()
    {
        $employees    = Employee::with(['department', 'designation'])->get();
        $departments  = Department::all();
        $designations = Designation::all();

        return view('HR.employees', compact('employees', 'departments', 'designations'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:employees,email',
            'phone'             => 'nullable|string|max:20',
            'department_id'     => 'required|exists:departments,id',
            'joining_date'      => 'required|date',
            'salary'            => 'required|numeric|min:0',
            'salary_type'       => 'required|in:monthly,weekly', // ✅ added
            'status'            => 'required|in:active,inactive',
            'address'           => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:20',
            'pan_number'        => 'nullable|string|max:10',
            'bank_account'      => 'nullable|string|max:20',
            'ifsc_code'         => 'nullable|string|max:11',
            'blood_group'       => 'nullable|string|max:5',
        ]);

        // ✅ Fixed: use withTrashed()->max() to avoid duplicate employee_id
        $lastEmployee = Employee::withTrashed()->max('employee_id');
        $nextNumber   = $lastEmployee ? (int) substr($lastEmployee, 3) + 1 : 1;
        $validated['employee_id'] = 'EMP' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Employee::create($validated);

        return redirect()->route('hr.employees')->with('success', 'Employee added successfully!');
    }



public function updateEmployee(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $validated = $request->validate([
        'first_name'        => 'required|string|max:255',
        'last_name'         => 'required|string|max:255',
        'email'             => 'required|email|unique:employees,email,' . $id,
        'phone'             => 'nullable|string|max:20',
        'department_id'     => 'required|exists:departments,id',
        'joining_date'      => 'required|date',
        'salary'            => 'required|numeric|min:0',
        'salary_type'       => 'required|in:monthly,weekly',
        'status'            => 'required|in:active,inactive',
        'address'           => 'nullable|string',
        'emergency_contact' => 'nullable|string|max:20',
        'pan_number'        => 'nullable|string|max:10',
        'bank_account'      => 'nullable|string|max:20',
        'ifsc_code'         => 'nullable|string|max:11',
        'blood_group'       => 'nullable|string|max:5',
    ]);

    $employee->update($validated);

    return redirect()->route('hr.employees')->with('success', 'Employee updated successfully!');
}

public function destroyEmployee($id)
{
    Employee::findOrFail($id)->delete();
    return redirect()->route('hr.employees')->with('success', 'Employee deleted successfully!');
}
    // ===== ATTENDANCE MANAGEMENT (HR VIEW) =====
    public function attendance(Request $request)
    {
        $query = Attendance::with('employee.department');

        if ($request->date) {
            $query->whereDate('date', $request->date);
        }
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(10);
        $employees   = Employee::all();

        return view('HR.attendance', compact('attendances', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'date'         => 'required|date',
            'check_in'     => 'nullable|date_format:H:i',
            'check_out'    => 'nullable|date_format:H:i',
            'status'       => 'required|in:present,absent,late,half_day,leave',
            'notes'        => 'nullable|string',
            'leave_type'   => 'nullable|in:annual,sick,emergency,unpaid',
            'leave_status' => 'nullable|in:approved,pending,applied',
            'late_minutes' => 'nullable|integer|min:0',
        ]);

        $existing = Attendance::where('employee_id', $request->employee_id)
                              ->whereDate('date', $request->date)
                              ->first();

        if ($existing) {
            return back()->with('error', 'Attendance already marked for this employee on this date!');
        }

        Attendance::create([
            'employee_id'  => $request->employee_id,
            'date'         => $request->date,
            'check_in'     => $request->check_in  ?: null,
            'check_out'    => $request->check_out ?: null,
            'status'       => $request->status,
            'notes'        => $request->notes,
            'leave_type'   => $request->leave_type,
            'leave_status' => $request->leave_status,
            'late_minutes' => $request->late_minutes,
        ]);

        return redirect()->route('hr.attendance')->with('success', 'Attendance marked successfully!');
    }

    public function edit($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        $employees  = Employee::all();
        return view('HR.attendance_edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'date'         => 'required|date',
            'check_in'     => 'nullable|date_format:H:i',
            'check_out'    => 'nullable|date_format:H:i',
            'status'       => 'required|in:present,absent,late,half_day,leave',
            'notes'        => 'nullable|string',
            'leave_type'   => 'nullable|in:annual,sick,emergency,unpaid',
            'leave_status' => 'nullable|in:approved,pending,applied',
            'late_minutes' => 'nullable|integer|min:0',
        ]);

        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'employee_id'  => $request->employee_id,
            'date'         => $request->date,
            'check_in'     => $request->check_in  ?: null,
            'check_out'    => $request->check_out ?: null,
            'status'       => $request->status,
            'notes'        => $request->notes,
            'leave_type'   => $request->leave_type,
            'leave_status' => $request->leave_status,
            'late_minutes' => $request->late_minutes,
        ]);

        return redirect()->route('hr.attendance')->with('success', 'Attendance updated successfully!');
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->route('hr.attendance')->with('success', 'Attendance deleted!');
    }

    // ===== TODAY'S ATTENDANCE API =====
    public function todayAttendance()
    {
        $today = Carbon::today();

        $attendances = Attendance::with(['employee.department'])
            ->whereDate('date', $today)
            ->orderBy('check_in', 'asc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id'              => $attendance->id,
                    'employee_name'   => $attendance->employee->first_name . ' ' . $attendance->employee->last_name,
                    'employee_id'     => $attendance->employee->employee_id,
                    'check_in'        => $attendance->check_in,
                    'check_out'       => $attendance->check_out,
                    'status'          => $attendance->status,
                    'department_name' => $attendance->employee->department->name ?? '-',
                ];
            });

        return response()->json($attendances);
    }

    // ===== EMPLOYEE SELF CHECK-IN =====
    public function selfCheckIn(Request $request)
    {
        try {
            $user     = Auth::user();
            $employee = $user->employee
                ?? Employee::where('email', $user->email)
                           ->orWhere('user_id', $user->id)
                           ->first();

            if (!$employee) {
                // ✅ Fixed: use withTrashed()->max() here too
                $lastEmployee = Employee::withTrashed()->max('employee_id');
                $nextNumber   = $lastEmployee ? (int) substr($lastEmployee, 3) + 1 : 1;

                $employee = Employee::create([
                    'user_id'       => $user->id,
                    'first_name'    => explode(' ', $user->name)[0] ?? 'Staff',
                    'last_name'     => '',
                    'email'         => $user->email,
                    'employee_id'   => 'EMP' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT),
                    'status'        => 'active',
                    'joining_date'  => now(),
                    'salary'        => 0,
                    'salary_type'   => 'monthly', // ✅ added default
                    'department_id' => Department::first()?->id ?? 1,
                ]);
            }

            $today    = Carbon::today();
            $existing = Attendance::where('employee_id', $employee->id)
                                  ->whereDate('date', $today)
                                  ->first();

            if ($existing && $existing->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already checked in at ' . Carbon::parse($existing->check_in)->format('H:i'),
                ]);
            }

            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $today],
                ['check_in' => now(), 'status' => 'present']
            );

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful at ' . now()->format('H:i'),
                'time'    => now()->format('H:i'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function selfCheckOut(Request $request)
    {
        try {
            $user     = Auth::user();
            $employee = $user->employee
                ?? Employee::where('email', $user->email)
                           ->orWhere('user_id', $user->id)
                           ->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee profile not found'], 400);
            }

            $today      = Carbon::today();
            $attendance = Attendance::where('employee_id', $employee->id)
                                    ->whereDate('date', $today)
                                    ->first();

            if (!$attendance || !$attendance->check_in) {
                return response()->json(['success' => false, 'message' => 'No check-in found for today'], 400);
            }

            if ($attendance->check_out) {
                return response()->json(['success' => false, 'message' => 'Already checked out'], 400);
            }

            $checkOutTime = now();
            $attendance->update([
                'check_out' => $checkOutTime,
                'status'    => $this->calculateStatus($attendance->check_in, $checkOutTime),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful at ' . $checkOutTime->format('H:i'),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Check-out failed: ' . $e->getMessage()], 500);
        }
    }

    public function attendanceStatus()
    {
        $user     = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return response()->json(['checked_in_today' => false, 'checkin_time' => null]);
        }

        $today      = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
                                ->whereDate('date', $today)
                                ->first();

        return response()->json([
            'checked_in_today' => $attendance && $attendance->check_in,
            'checkin_time'     => $attendance?->check_in ? Carbon::parse($attendance->check_in)->format('H:i') : null,
        ]);
    }

    // ===== PRIVATE HELPERS =====
    private function calculateStatus($checkIn, $checkOut)
    {
        if (!$checkIn) return 'absent';

        $checkInTime = Carbon::parse($checkIn);
        $officeStart = Carbon::parse('09:00');

        if ($checkInTime->gt($officeStart->copy()->addMinutes(15))) {
            return 'late';
        }

        if ($checkOut && $checkInTime->diffInHours(Carbon::parse($checkOut)) < 4) {
            return 'half_day';
        }

        return 'present';
    }

    public function payroll(Request $request)
    {
        $filter    = $request->get('filter', 'monthly');
        $month     = $request->get('month', now()->format('Y-m'));
        $weekStart = $request->get('week_start', now()->startOfWeek()->format('Y-m-d'));

        $employees = Employee::with('department')->get()->map(function ($employee) use ($filter, $month, $weekStart) {

            if ($filter === 'weekly') {
                $start = Carbon::parse($weekStart)->startOfWeek();
                $end   = Carbon::parse($weekStart)->endOfWeek();
            } else {
                $start = Carbon::parse($month . '-01')->startOfMonth();
                $end   = Carbon::parse($month . '-01')->endOfMonth();
            }

            $totalDays        = $start->diffInWeekdays($end) + 1;
            $presentDays      = Attendance::where('employee_id', $employee->id)
                                    ->whereBetween('date', [$start, $end])
                                    ->whereIn('status', ['present', 'late', 'half_day'])
                                    ->count();
            $absentDays       = Attendance::where('employee_id', $employee->id)
                                    ->whereBetween('date', [$start, $end])
                                    ->where('status', 'absent')
                                    ->count();
            $leaveDays        = Attendance::where('employee_id', $employee->id)
                                    ->whereBetween('date', [$start, $end])
                                    ->where('status', 'leave')
                                    ->count();
            $lateDays         = Attendance::where('employee_id', $employee->id)
                                    ->whereBetween('date', [$start, $end])
                                    ->where('status', 'late')
                                    ->count();
            $totalLateMinutes = Attendance::where('employee_id', $employee->id)
                                    ->whereBetween('date', [$start, $end])
                                    ->where('status', 'late')
                                    ->sum('late_minutes');

            // ✅ Fixed: respect salary_type for daily rate calculation
            $workingDays  = $employee->salary_type === 'weekly' ? 5 : 26;
            $dailySalary  = $employee->salary / $workingDays;
            $earnedSalary = $dailySalary * $presentDays;
            $deductions   = $dailySalary * $absentDays;
            $netSalary    = $earnedSalary;

            return [
                'id'                 => $employee->id,
                'employee_id'        => $employee->employee_id,
                'name'               => $employee->first_name . ' ' . $employee->last_name,
                'department'         => $employee->department->name ?? '—',
                'salary_type'        => $employee->salary_type ?? 'monthly', // ✅ added
                'gross_salary'       => $employee->salary,
                'daily_salary'       => round($dailySalary, 2),
                'present_days'       => $presentDays,
                'absent_days'        => $absentDays,
                'leave_days'         => $leaveDays,
                'late_days'          => $lateDays,
                'total_late_minutes' => $totalLateMinutes,
                'earned_salary'      => round($earnedSalary, 2),
                'deductions'         => round($deductions, 2),
                'net_salary'         => round($netSalary, 2),
            ];
        });

        return view('HR.payroll', compact('employees', 'filter', 'month', 'weekStart'));
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');

        return response()->json(['message' => 'Cache cleared successfully!']);
    }
}