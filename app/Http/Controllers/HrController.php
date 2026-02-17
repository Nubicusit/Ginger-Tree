<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;  // ← ADD THIS LINE
use Illuminate\Support\Facades\Schema;

class HRController extends Controller
{
    /**
     * HR Dashboard with stats
     */
    public function dashboard()
    {
        return view('HR.dashboard', [
            'totalEmployees' => Employee::count(),
            'newEmployees' => Employee::whereMonth('created_at', now()->month)->count(),
            'todayPresent' => Attendance::whereDate('date', today())
                                       ->where('status', 'present')
                                       ->count(),
            'todayAbsent' => Employee::count() - Attendance::whereDate('date', today())
                                                   ->where('status', 'present')
                                                   ->count(),
            'pendingLeaves' => 17, // Add your leave model logic here
            'approvedLeaves' => 45,
            'monthlyPayroll' => 42.5,
            'pendingPayroll' => 3,
            'activeAnnouncements' => 6,
            'draftAnnouncements' => 2
        ]);
    }

    // ===== EMPLOYEE MANAGEMENT =====
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

        $validated['employee_id'] = 'EMP' . str_pad(Employee::count() + 1, 5, '0', STR_PAD_LEFT);
        Employee::create($validated);

        return redirect()->route('hr.employees')->with('success', 'Employee added successfully!');
    }

    // ===== ATTENDANCE MANAGEMENT (HR VIEW) =====
    public function attendance(Request $request)
    {
        $query = Attendance::with('employee');
        
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
        $employees = Employee::all();
        
        return view('HR.attendance', compact('attendances', 'employees'));
    }

    // Today's Attendance API - NEW METHOD
public function todayAttendance()
{
    $today = Carbon::today();
    
    $attendances = Attendance::with(['employee.department', 'employee.designation'])
        ->whereDate('date', $today)
        ->orderBy('check_in', 'asc')
        ->get()
        ->map(function ($attendance) {
            return [
                'id' => $attendance->id,
                'employee_name' => $attendance->employee->first_name . ' ' . $attendance->employee->last_name,
                'employee_id' => $attendance->employee->employee_id,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'status' => $attendance->status,
                'department_name' => $attendance->employee->department->name ?? '-'
            ];
        });

    return response()->json($attendances);
}

   public function selfCheckinReport()
{
    $todayCheckIns = 228;
    $lateCheckIns = 8;
    $avgCheckInTime = '9:15 AM';
    
    // Only try database if table exists
    if (Schema::hasTable('attendances')) {
        try {
            $today = Carbon::today();
            $todayCheckIns = DB::table('attendances')
                ->whereDate('check_in_time', $today)
                ->count();
                
            $lateCheckIns = DB::table('attendances')
                ->whereDate('check_in_time', $today)
                ->where('check_in_time', '>', '09:00:00')
                ->count();
        } catch (\Exception $e) {
            // Keep fallback values
        }
    }

    return view('hr.dashboard', compact('todayCheckIns', 'lateCheckIns', 'avgCheckInTime'));
}
    // ===== EMPLOYEE SELF ATTENDANCE API =====
public function selfCheckIn(Request $request)
{
    try {
        $user = Auth::user();
        
        // Fix 1: Both methods try cheyyuka
        $employee = $user->employee ?? Employee::where('email', $user->email)->orWhere('user_id', $user->id)->first();
        
        if (!$employee) {
            // Fix 2: Safe auto-create with try-catch
            $employeeId = Employee::max('id') + 1;
            $employee = Employee::create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $user->name)[0] ?? 'Staff',
                'last_name' => '',
                'email' => $user->email,
                'employee_id' => 'EMP' . str_pad($employeeId, 5, '0', STR_PAD_LEFT),
                'status' => 'active',
                'joining_date' => now(),
                'salary' => 0,
                'department_id' => Department::first()?->id ?? 1
            ]);
        }

        $today = Carbon::today();
        $existing = Attendance::where('employee_id', $employee->id)
                             ->whereDate('date', $today)
                             ->first();

        if ($existing && $existing->check_in) {
            return response()->json([
                'success' => false, 
                'message' => 'Already checked-in at ' . $existing->check_in->format('H:i')
            ]);
        }

        $attendance = Attendance::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $today],
            ['check_in' => now(), 'status' => 'present']
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful at ' . now()->format('H:i'),
            'time' => now()->format('H:i')
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Check-in failed! Please try again. ' . $e->getMessage()
        ], 500);
    }
}


public function attendanceStatus()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();
    
    if (!$employee) {
        return response()->json([
            'checked_in_today' => false,
            'checkin_time' => null
        ]);
    }

    $today = Carbon::today();
    $attendance = Attendance::where('employee_id', $employee->id)
                           ->whereDate('date', $today)
                           ->first();

    return response()->json([
        'checked_in_today' => $attendance && $attendance->check_in,
        'checkin_time' => $attendance?->check_in?->format('H:i') ?? null
    ]);
}


public function selfCheckOut(Request $request)
{
    try {
        $user = Auth::user();
        $employee = $user->employee ?? Employee::where('email', $user->email)->orWhere('user_id', $user->id)->first();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee profile not found'], 400);
        }

        $today = Carbon::today();
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
            'status' => $this->calculateStatus($attendance->check_in, $checkOutTime)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out successful at ' . $checkOutTime->format('H:i')
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Check-out failed'], 500);
    }
}

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string'
        ]);

        $existing = Attendance::where('employee_id', $request->employee_id)
                             ->whereDate('date', $request->date)
                             ->first();

        if ($existing) {
            return back()->with('error', 'Attendance already marked for this date!');
        }

        Attendance::create($request->all());
        return redirect()->route('hr.attendance')->with('success', 'Attendance marked!');
    }

    public function edit($id)
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        $employees = Employee::all();
        return view('HR.attendance_edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string'
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());
        
        return redirect()->route('hr.attendance')->with('success', 'Attendance updated!');
    }

    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->route('hr.attendance')->with('success', 'Attendance deleted!');
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

   
}
