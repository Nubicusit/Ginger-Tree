<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Customer;
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

public function invoicesIndex(Request $request)
{
    $query = Quotation::with('lead')->orderByDesc('id');

    // Search
    if ($request->search) {
        $query->whereHas('lead', fn($q) => 
            $q->where('client_name', 'like', '%'.$request->search.'%')
        );
    }

    // Status filter
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Date filter
    if ($request->from_date) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->to_date) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $invoices = $query->paginate(10);

    return view('accounts.invoices', compact('invoices'));
}

public function invoicesCreate()
{
    $customers = Customer::orderBy('name')->get();
    return view('accounts.invoices.create', compact('customers'));
}

public function invoicesStore(Request $request)
{
    $request->validate([
        'customer_id'    => 'required|exists:customers,id',
        'invoice_date'   => 'required|date',
        'due_date'       => 'required|date|after_or_equal:invoice_date',
        'status'         => 'required|in:paid,unpaid,overdue,draft',
        'items'          => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.quantity'    => 'required|numeric|min:1',
        'items.*.unit_price'  => 'required|numeric|min:0',
    ]);

    $invoice = Invoice::create([
        'invoice_number' => 'INV-' . strtoupper(uniqid()),
        'customer_id'    => $request->customer_id,
        'invoice_date'   => $request->invoice_date,
        'due_date'       => $request->due_date,
        'status'         => $request->status,
        'notes'          => $request->notes,
        'total_amount'   => collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']),
    ]);

    foreach ($request->items as $item) {
        $invoice->items()->create([
            'description' => $item['description'],
            'quantity'    => $item['quantity'],
            'unit_price'  => $item['unit_price'],
            'total'       => $item['quantity'] * $item['unit_price'],
        ]);
    }

    return redirect()->route('accounts.invoices.index')
        ->with('success', 'Invoice created successfully.');
}

public function invoicesShow($id)
{
    $invoice = Invoice::with(['customer', 'items'])->findOrFail($id);
    return view('accounts.invoices.show', compact('invoice'));
}

public function invoicesEdit($id)
{
    $invoice   = Invoice::with('items')->findOrFail($id);
    $customers = Customer::orderBy('name')->get();
    return view('accounts.invoices.edit', compact('invoice', 'customers'));
}

public function invoicesUpdate(Request $request, $id)
{
    $request->validate([
        'customer_id'  => 'required|exists:customers,id',
        'invoice_date' => 'required|date',
        'due_date'     => 'required|date|after_or_equal:invoice_date',
        'status'       => 'required|in:paid,unpaid,overdue,draft',
        'items'        => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.quantity'    => 'required|numeric|min:1',
        'items.*.unit_price'  => 'required|numeric|min:0',
    ]);

    $invoice = Invoice::findOrFail($id);

    $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

    $invoice->update([
        'customer_id'  => $request->customer_id,
        'invoice_date' => $request->invoice_date,
        'due_date'     => $request->due_date,
        'status'       => $request->status,
        'notes'        => $request->notes,
        'total_amount' => $total,
    ]);

    // Replace all line items
    $invoice->items()->delete();
    foreach ($request->items as $item) {
        $invoice->items()->create([
            'description' => $item['description'],
            'quantity'    => $item['quantity'],
            'unit_price'  => $item['unit_price'],
            'total'       => $item['quantity'] * $item['unit_price'],
        ]);
    }

    return redirect()->route('accounts.invoices.index')
        ->with('success', 'Invoice updated successfully.');
}

public function invoicesDestroy($id)
{
    $invoice = Invoice::findOrFail($id);
    $invoice->items()->delete();
    $invoice->delete();

    return redirect()->route('accounts.invoices.index')
        ->with('success', 'Invoice deleted successfully.');
}

public function invoicesPrint($id)
{
    $invoice = Invoice::with(['customer', 'items'])->findOrFail($id);
    return view('accounts.invoices.print', compact('invoice'));
}

}