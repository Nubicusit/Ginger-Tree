<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function dashboard()
    {
        return view('accounts.dashboard');
    }
 
public function incomeExpenses()
{
    $transactions  = Transaction::latest()->get(); // your model
    $totalIncome   = Transaction::where('type', 'income')->sum('amount');
    $totalExpenses = Transaction::where('type', 'expense')->sum('amount');

    return view('accounts.income-expenses', compact('transactions', 'totalIncome', 'totalExpenses'));
}


  public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'type'     => 'required|in:income,expense',
            'category' => 'required|string',
            'amount'   => 'required|numeric|min:0',
            'date'     => 'required|date',
        ]);

        Transaction::create($request->only([
            'title', 'type', 'category', 'amount',
            'date', 'payment_method', 'reference', 'note'
        ]));

        return redirect()->route('accounts.income-expenses')
            ->with('success', 'Transaction added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'type'     => 'required|in:income,expense',
            'category' => 'required|string',
            'amount'   => 'required|numeric|min:0',
            'date'     => 'required|date',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->only([
            'title', 'type', 'category', 'amount',
            'date', 'payment_method', 'reference', 'note'
        ]));

        return redirect()->route('accounts.income-expenses')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();

        return redirect()->route('accounts.income-expenses')
            ->with('success', 'Transaction deleted successfully.');
    }

public function payroll(Request $request)
{
    $selectedMonth = $request->get('month', now()->format('Y-m'));

    $start = \Carbon\Carbon::parse($selectedMonth . '-01')->startOfMonth();
    $end   = \Carbon\Carbon::parse($selectedMonth . '-01')->endOfMonth();

    $employees = \App\Models\Employee::with('department')->get()->map(function ($employee) use ($start, $end) {

        $workingDays  = $employee->salary_type === 'weekly' ? 5 : 26;
        $dailySalary  = $employee->salary / $workingDays;

        $presentDays = \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->whereIn('status', ['present', 'late', 'half_day'])
            ->count();

        $absentDays = \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->where('status', 'absent')
            ->count();

        $leaveDays = \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->where('status', 'leave')
            ->count();

        $earnedSalary = round($dailySalary * $presentDays, 2);
        $deductions   = round($dailySalary * $absentDays, 2);
        $netSalary    = round($earnedSalary, 2);

        return (object)[
            'employee_id'    => $employee->employee_id,
            'first_name'     => $employee->first_name,
            'last_name'      => $employee->last_name,
            'department'     => $employee->department,
            'basic_salary'   => $employee->salary,
            'allowances'     => 0,
            'deductions'     => $deductions,
            'net_salary'     => $netSalary,
            'present_days'   => $presentDays,
            'absent_days'    => $absentDays,
            'leave_days'     => $leaveDays,
            'payment_method' => $employee->salary_type,
            'paid_date'      => null,
            'status'         => 'pending',
        ];
    });

    $totalEmployees  = $employees->count();
    $totalNetSalary  = $employees->sum('net_salary');
    $totalPending    = $totalNetSalary;
    $totalPaid       = 0;

    // Use as collection for the view
    $payrolls = $employees;

    return view('accounts.payroll', compact(
        'payrolls', 'selectedMonth',
        'totalEmployees', 'totalNetSalary',
        'totalPending', 'totalPaid'
    ));
}

}