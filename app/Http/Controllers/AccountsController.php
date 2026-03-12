<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Payment;
use App\Models\SiteVisit;
use Illuminate\Support\Facades\Storage;
use App\Models\Estimation;
use App\Models\EstimationItem;

class AccountsController extends Controller
{
    public function dashboard()
    {
        return view('accounts.dashboard');
    }

    public function incomeExpenses()
    {
        $transactions  = Transaction::latest()->get();
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

            $workingDays = $employee->salary_type === 'weekly' ? 5 : 26;
            $dailySalary = $employee->salary / $workingDays;

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

        $totalEmployees = $employees->count();
        $totalNetSalary = $employees->sum('net_salary');
        $totalPending   = $totalNetSalary;
        $totalPaid      = 0;
        $payrolls       = $employees;

        return view('accounts.payroll', compact(
            'payrolls', 'selectedMonth',
            'totalEmployees', 'totalNetSalary',
            'totalPending', 'totalPaid'
        ));
    }

    public function invoicesIndex(Request $request)
    {
        $query = Quotation::with('lead')->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('lead', fn($l) => $l->where('client_name', 'like', '%' . $search . '%'))
                    ->orWhereNull('lead_id');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
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
            'customer_id'         => 'required|exists:customers,id',
            'invoice_date'        => 'required|date',
            'due_date'            => 'required|date|after_or_equal:invoice_date',
            'status'              => 'required|in:paid,unpaid,overdue,draft',
            'items'               => 'required|array|min:1',
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
            'customer_id'         => 'required|exists:customers,id',
            'invoice_date'        => 'required|date',
            'due_date'            => 'required|date|after_or_equal:invoice_date',
            'status'              => 'required|in:paid,unpaid,overdue,draft',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|numeric|min:1',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);
        $total   = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

        $invoice->update([
            'customer_id'  => $request->customer_id,
            'invoice_date' => $request->invoice_date,
            'due_date'     => $request->due_date,
            'status'       => $request->status,
            'notes'        => $request->notes,
            'total_amount' => $total,
        ]);

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
        $quotation = Quotation::with('lead')->findOrFail($id);
        return view('accounts.print', compact('quotation'));
    }

    public function invoicesApprove($id)
    {
        $quotation = Quotation::with('lead')->findOrFail($id);

        $quotation->update([
            'status'           => 'Approved',
            'rejection_reason' => null,
        ]);

        if ($quotation->lead) {
            $existingCustomer = Customer::where('email', $quotation->lead->email)->first();

            if (!$existingCustomer) {
                Customer::create([
                    'name'           => $quotation->lead->client_name,
                    'email'          => $quotation->lead->email,
                    'contact_no'     => $quotation->lead->phone ?? '0000000000',
                    'address'        => $quotation->lead->location ?? null,
                    'payment_status' => 'pending',
                    'project_type'   => $quotation->lead->project_type ?? null,
                ]);
            }
        }

        return back()->with('success', 'Quotation ' . $quotation->quotation_no . ' has been approved and customer created.');
    }

    public function invoicesReject(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
            'rejection_reason.max'      => 'Reason must not exceed 500 characters.',
        ]);

        $quotation->update([
            'status'             => 'Rejected',
            'rejection_reason'   => $request->rejection_reason,
            'approval_reason'    => null,
            'negotiation_reason' => null,
        ]);

        return back()->with('success', 'Quotation ' . $quotation->quotation_no . ' has been rejected.');
    }

    public function invoicesNegotiate(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        $request->validate([
            'negotiation_reason' => 'required|string|max:500',
        ], [
            'negotiation_reason.required' => 'Please provide negotiation notes.',
            'negotiation_reason.max'      => 'Notes must not exceed 500 characters.',
        ]);

        $quotation->update([
            'status'             => 'Negotiation',
            'negotiation_reason' => $request->negotiation_reason,
            'approval_reason'    => null,
            'rejection_reason'   => null,
        ]);

        return back()->with('success', 'Quotation ' . $quotation->quotation_no . ' has been sent for negotiation.');
    }

    /*
    |--------------------------------------------------------------------------
    | Project Payments
    |--------------------------------------------------------------------------
    */

    public function projectsIndex()
    {
        $projects = Project::withSum('payments', 'amount')
            ->latest()
            ->get()
            ->map(function ($project) {
                $paid              = $project->payments_sum_amount ?? 0;
                $project->balance  = $project->total_value - $paid;
                $project->paid_pct = $project->total_value > 0
                    ? min(100, round($paid / $project->total_value * 100))
                    : 0;
                return $project;
            });

        return view('accounts.payment', compact('projects'));
    }

    public function projectShow($projectId)
    {
        $project  = Project::findOrFail($projectId);
        $payments = Payment::where('project_id', $projectId)->latest()->get();

        $totalPaid     = $payments->sum('amount');
        $totalGST      = $payments->sum('gst_amount');
        $totalDiscount = $payments->sum('discount_amount');
        $balance       = $project->total_value - $totalPaid;
        $gstOnTotal    = $project->total_value * $project->gst_rate / 100;
        $grandTotal    = $project->total_value + $gstOnTotal;
        $pct           = $project->total_value > 0
            ? min(100, round($totalPaid / $project->total_value * 100))
            : 0;

        return view('accounts.payment', compact(
            'project', 'payments',
            'totalPaid', 'totalGST', 'totalDiscount',
            'balance', 'gstOnTotal', 'grandTotal', 'pct'
        ));
    }

    public function projectUpdateTotal(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $request->validate([
            'total_value' => 'required|numeric|min:0',
            'gst_rate'    => 'nullable|numeric|min:0|max:100',
        ]);

        $project->update([
            'total_value' => $request->total_value,
            'gst_rate'    => $request->gst_rate ?? $project->gst_rate,
        ]);

        return redirect()->route('accounts.projects.show', $projectId)
            ->with('success', 'Contract value updated successfully.');
    }

    public function paymentStore(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $request->validate([
            'payment_type'   => 'required|in:Advance,Partial,Full',
            'payment_method' => ['required', Rule::in(['Online', 'Bank Transfer', 'Cash'])],
            'amount'         => 'required|numeric|min:1|max:1000000',
            'remarks'        => 'nullable|string|max:500',
            'discount_type'  => 'nullable|in:none,percent,flat',
            'discount_value' => 'nullable|numeric|min:0|max:50000',
            'apply_gst'      => 'nullable|boolean',
            'gst_type'       => 'nullable|in:IGST,CGST+SGST',
            'transaction_id' => ['nullable', 'required_if:payment_method,Online', 'string', 'max:100'],
            'screenshot'     => ['nullable', 'required_if:payment_method,Online'],
            'screenshot.*'   => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'bank_name'      => ['nullable', Rule::requiredIf(fn() => $request->payment_method === 'Bank Transfer'), 'string', 'max:100'],
            'account_number' => ['nullable', Rule::requiredIf(fn() => $request->payment_method === 'Bank Transfer'), 'string', 'max:30'],
            'ifsc_code'      => ['nullable', Rule::requiredIf(fn() => $request->payment_method === 'Bank Transfer'), 'string', 'max:20'],
            'transfer_ref'   => ['nullable', Rule::requiredIf(fn() => $request->payment_method === 'Bank Transfer'), 'string', 'max:100'],
            'bank_slip'      => ['nullable'],
            'bank_slip.*'    => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'receipt'        => ['nullable', 'required_if:payment_method,Cash'],
            'receipt.*'      => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $base        = floatval($request->amount);
        $discType    = $request->discount_type ?? 'none';
        $discVal     = floatval($request->discount_value ?? 0);
        $discountAmt = 0;

        if ($discType === 'percent') {
            $discountAmt = $base * $discVal / 100;
        } elseif ($discType === 'flat') {
            $discountAmt = $discVal;
        }
        $discountAmt = round(min($discountAmt, $base), 2);

        $taxable = $base - $discountAmt;
        $gstAmt  = ($request->boolean('apply_gst') && $project->gst_rate)
            ? round($taxable * $project->gst_rate / 100, 2)
            : 0;

        $screenshotPaths = [];
        if ($request->hasFile('screenshot')) {
            foreach ($request->file('screenshot') as $file) {
                $filename = $file->hashName();
                $file->move(public_path('payments/screenshots'), $filename);
                $screenshotPaths[] = 'payments/screenshots/' . $filename;
            }
        }

        $bankSlipPaths = [];
        if ($request->hasFile('bank_slip')) {
            foreach ($request->file('bank_slip') as $file) {
                $filename = $file->hashName();
                $file->move(public_path('payments/bank_slips'), $filename);
                $bankSlipPaths[] = 'payments/bank_slips/' . $filename;
            }
        }

        $receiptPaths = [];
        if ($request->hasFile('receipt')) {
            foreach ($request->file('receipt') as $file) {
                $filename = $file->hashName();
                $file->move(public_path('payments/receipts'), $filename);
                $receiptPaths[] = 'payments/receipts/' . $filename;
            }
        }

        Payment::create([
            'project_id'      => $project->id,
            'payment_type'    => $request->payment_type,
            'payment_method'  => $request->payment_method,
            'amount'          => $base,
            'discount_type'   => $discType,
            'discount_value'  => $discVal,
            'discount_amount' => $discountAmt,
            'apply_gst'       => $request->boolean('apply_gst') ? 1 : 0,
            'gst_type'        => $request->gst_type ?? 'IGST',
            'gst_amount'      => $gstAmt,
            'total_payable'   => round($taxable + $gstAmt, 2),
            'transaction_id'  => $request->transaction_id,
            'bank_name'       => $request->bank_name,
            'account_number'  => $request->account_number,
            'ifsc_code'       => $request->ifsc_code,
            'transfer_ref'    => $request->transfer_ref,
            'screenshot_path' => json_encode($screenshotPaths),
            'bank_slip_path'  => json_encode($bankSlipPaths),
            'receipt_path'    => json_encode($receiptPaths),
            'remarks'         => $request->remarks,
            'status'          => 'Pending',
        ]);

        return redirect()->route('accounts.projects.show', $project->id)
            ->with('success', 'Payment recorded successfully!');
    }

    public function paymentUpdate(Request $request, $projectId, $paymentId)
    {
        $project = Project::findOrFail($projectId);
        $payment = Payment::where('project_id', $projectId)->findOrFail($paymentId);

        $request->validate([
            'payment_type'   => 'required|in:Advance,Partial,Full',
            'payment_method' => ['required', Rule::in(['Online', 'Bank Transfer', 'Cash'])],
            'amount'         => 'required|numeric|min:1|max:1000000',
            'remarks'        => 'nullable|string|max:500',
            'screenshot.*'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'bank_slip.*'    => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'receipt.*'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $base    = floatval($request->amount);
        $taxable = $base - $payment->discount_amount;
        $gstAmt  = $payment->apply_gst
            ? round($taxable * $project->gst_rate / 100, 2)
            : 0;

        if ($request->hasFile('screenshot')) {
            $existingPaths = is_array($payment->screenshot_path)
                ? $payment->screenshot_path
                : (json_decode($payment->screenshot_path, true) ?? []);
            $newPaths = [];
            foreach ($request->file('screenshot') as $file) {
                $filename = $file->hashName();
                $file->move(public_path('payments/screenshots'), $filename);
                $newPaths[] = 'payments/screenshots/' . $filename;
            }
            $payment->screenshot_path = json_encode(array_merge($existingPaths, $newPaths));
        }

        if ($request->hasFile('bank_slip')) {
            $existingPaths = is_array($payment->bank_slip_path)
                ? $payment->bank_slip_path
                : (json_decode($payment->bank_slip_path, true) ?? []);
            $newPaths = [];
            foreach ($request->file('bank_slip') as $file) {
                $filename = $file->hashName();
                $file->move(public_path('payments/bank_slips'), $filename);
                $newPaths[] = 'payments/bank_slips/' . $filename;
            }
            $payment->bank_slip_path = json_encode(array_merge($existingPaths, $newPaths));
        }

        if ($request->hasFile('receipt')) {
            $existingPaths = is_array($payment->receipt_path)
                ? $payment->receipt_path
                : (json_decode($payment->receipt_path, true) ?? []);
            $newPaths = [];
            foreach ($request->file('receipt') as $file) {
                $filename = $file->hashName();
                $file->move(public_path('payments/receipts'), $filename);
                $newPaths[] = 'payments/receipts/' . $filename;
            }
            $payment->receipt_path = json_encode(array_merge($existingPaths, $newPaths));
        }

        $payment->fill([
            'payment_type'   => $request->payment_type,
            'payment_method' => $request->payment_method,
            'amount'         => $base,
            'gst_amount'     => $gstAmt,
            'total_payable'  => round($taxable + $gstAmt, 2),
            'transaction_id' => $request->transaction_id ?? $payment->transaction_id,
            'bank_name'      => $request->bank_name      ?? $payment->bank_name,
            'account_number' => $request->account_number ?? $payment->account_number,
            'ifsc_code'      => $request->ifsc_code      ?? $payment->ifsc_code,
            'transfer_ref'   => $request->transfer_ref   ?? $payment->transfer_ref,
            'remarks'        => $request->remarks,
        ])->save();

        return redirect()->route('accounts.projects.show', $projectId)
            ->with('success', 'Payment updated successfully.');
    }

    public function paymentDestroy($projectId, $paymentId)
    {
        $payment = Payment::where('project_id', $projectId)->findOrFail($paymentId);

        foreach (['screenshot_path', 'bank_slip_path', 'receipt_path'] as $field) {
            $paths = is_array($payment->$field)
                ? $payment->$field
                : (json_decode($payment->$field, true) ?? []);
            foreach ($paths as $path) {
                @unlink(public_path($path));
            }
        }

        $payment->delete();

        return redirect()
            ->route('accounts.projects.show', $projectId)
            ->with('success', 'Payment deleted successfully.');
    }

    public function paymentFileDelete(Request $request, $projectId, $paymentId)
    {
        $payment  = Payment::where('project_id', $projectId)->findOrFail($paymentId);
        $fileType = $request->input('file_type');
        $filePath = $request->input('file_path');

        $allowedFields = ['screenshot_path', 'bank_slip_path', 'receipt_path'];
        if (!in_array($fileType, $allowedFields)) {
            return back()->with('error', 'Invalid file type.');
        }

        $paths = is_array($payment->$fileType)
            ? $payment->$fileType
            : (json_decode($payment->$fileType, true) ?? []);

        if (in_array($filePath, $paths)) {
            @unlink(public_path($filePath));
            $paths          = array_values(array_filter($paths, fn($p) => $p !== $filePath));
            $payment->$fileType = json_encode($paths);
            $payment->save();
        }

        return back()->with('success', 'File removed successfully.');
    }

    public function paymentApprove($projectId, $paymentId)
    {
        Payment::where('project_id', $projectId)
            ->findOrFail($paymentId)
            ->update(['status' => 'Cleared']);

        return redirect()->back()->with('success', 'Payment approved and marked as Cleared.');
    }

    public function paymentReject($projectId, $paymentId)
    {
        Payment::where('project_id', $projectId)
            ->findOrFail($paymentId)
            ->update(['status' => 'Rejected']);

        return redirect()->back()->with('success', 'Payment rejected.');
    }

    /*
    |--------------------------------------------------------------------------
    | Estimations
    |--------------------------------------------------------------------------
    */

    public function estimations()
    {
        // ── Quotations → Estimations sync ──
        $quotations = Quotation::with('lead')->get();

        foreach ($quotations as $quotation) {
            if (!$quotation->lead_id) continue;

            $items = is_array($quotation->items)
                ? $quotation->items
                : json_decode($quotation->items, true) ?? [];

            if (empty($items)) continue;

            $statusMap = [
                'Submitted'   => 'Sent',
                'Negotiation' => 'Revised',
                'Approved'    => 'Approved',
                'Rejected'    => 'Rejected',
            ];

            // QT-001 → EST-001
            $estimationNo = preg_replace('/^QT-?/i', 'EST-', $quotation->quotation_no);

            $estimation = Estimation::updateOrCreate(
                ['lead_id' => $quotation->lead_id, 'estimation_no' => $estimationNo],
                [
                    'client_name' => $quotation->lead->client_name ?? 'N/A',
                    'title'       => 'Quotation ' . $quotation->quotation_no,
                    'status'      => $statusMap[$quotation->status] ?? 'Sent',
                ]
            );

            // ── Items sync with correct field mapping ──
            EstimationItem::where('estimation_id', $estimation->id)->delete();

            $subtotal = 0;
            foreach ($items as $index => $item) {
                $qty       = floatval($item['quantity']  ?? $item['qty']   ?? 1);
                $unitPrice = floatval($item['unit_price'] ?? $item['price'] ?? 0);
                $amount    = floatval($item['amount']     ?? ($qty * $unitPrice));
                $subtotal += $amount;

                EstimationItem::create([
                    'estimation_id' => $estimation->id,
                    'section'       => $item['section']                                      ?? null,
                    'description'   => $item['description'] ?? $item['item_name']
                                       ?? $item['custom_name']                               ?? null,
                    'category'      => $item['category']                                     ?? null,
                    'unit'          => $item['unit']                                         ?? 'Nos',
                    'qty'           => $qty,
                    'unit_price'    => $unitPrice,
                    'amount'        => $amount,
                    'sort_order'    => $item['sort_order'] ?? $index,
                ]);
            }

            // ── Recalculate totals ──
            $gstPct    = $estimation->gst_pct  ?? 18;
            $discount  = $estimation->discount ?? 0;
            $gstAmount = round($subtotal * ($gstPct / 100), 2);

            $estimation->update([
                'subtotal'    => $subtotal,
                'gst_amount'  => $gstAmount,
                'grand_total' => round($subtotal - $discount + $gstAmount, 2),
            ]);
        }

        $estimations = Estimation::latest()->get();
        return view('accounts.estimations', compact('estimations'));
    }

    public function estimationStore(Request $request)
    {
        $request->validate([
            'estimation_no' => 'required|string|max:100|unique:estimations,estimation_no',
            'client_name'   => 'required|string|max:200',
            'gst_pct'       => 'nullable|numeric|min:0|max:100',
            'discount'      => 'nullable|numeric|min:0',
            'status'        => 'nullable|in:Draft,Sent,Approved,Rejected,Revised',
        ]);

        $estimation = Estimation::create([
            'lead_id'       => $request->lead_id    ?: null,
            'project_id'    => $request->project_id ?: null,
            'estimation_no' => $request->estimation_no,
            'client_name'   => $request->client_name,
            'client_email'  => $request->client_email,
            'client_phone'  => $request->client_phone,
            'site_address'  => $request->site_address,
            'title'         => $request->title,
            'scope'         => $request->scope,
            'valid_till'    => $request->valid_till ?: null,
            'status'        => $request->status     ?? 'Draft',
            'notes'         => $request->notes,
            'subtotal'      => $request->subtotal   ?? 0,
            'discount'      => $request->discount   ?? 0,
            'gst_pct'       => $request->gst_pct    ?? 18,
            'gst_amount'    => $request->gst_amount  ?? 0,
            'grand_total'   => $request->grand_total ?? 0,
            'created_by'    => auth()->id(),
        ]);

        $items = $this->saveEstimationItems($estimation->id, $request->sections ?? []);
        $this->syncEstimationToQuotation($estimation, $items);

        return redirect()->route('accounts.estimations')
            ->with('success', 'Estimation created successfully!');
    }

    public function estimationUpdate(Request $request, $id)
    {
        $estimation = Estimation::findOrFail($id);

        $request->validate([
            'estimation_no' => 'required|string|max:100|unique:estimations,estimation_no,' . $id,
            'client_name'   => 'required|string|max:200',
            'gst_pct'       => 'nullable|numeric|min:0|max:100',
            'discount'      => 'nullable|numeric|min:0',
            'status'        => 'nullable|in:Draft,Sent,Approved,Rejected,Revised',
        ]);

        $estimation->update([
            'lead_id'       => $request->lead_id    ?: null,
            'project_id'    => $request->project_id ?: null,
            'estimation_no' => $request->estimation_no,
            'client_name'   => $request->client_name,
            'client_email'  => $request->client_email,
            'client_phone'  => $request->client_phone,
            'site_address'  => $request->site_address,
            'title'         => $request->title,
            'scope'         => $request->scope,
            'valid_till'    => $request->valid_till ?: null,
            'status'        => $request->status     ?? 'Draft',
            'notes'         => $request->notes,
            'subtotal'      => $request->subtotal   ?? 0,
            'discount'      => $request->discount   ?? 0,
            'gst_pct'       => $request->gst_pct    ?? 18,
            'gst_amount'    => $request->gst_amount  ?? 0,
            'grand_total'   => $request->grand_total ?? 0,
        ]);

        EstimationItem::where('estimation_id', $id)->delete();
        $items = $this->saveEstimationItems($id, $request->sections ?? []);
        $this->syncEstimationToQuotation($estimation->fresh(), $items);

        return redirect()->route('accounts.estimations')
            ->with('success', 'Estimation updated successfully!');
    }

    public function estimationDestroy($id)
    {
        $estimation = Estimation::findOrFail($id);
        EstimationItem::where('estimation_id', $id)->delete();

        if ($estimation->lead_id) {
            $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);
            Quotation::where('lead_id', $estimation->lead_id)
                ->where('quotation_no', $quotationNo)
                ->delete();
        }

        $estimation->delete();

        return redirect()->route('accounts.estimations')
            ->with('success', 'Estimation deleted.');
    }

    public function estimationEditData($id)
    {
        $estimation = Estimation::findOrFail($id);
        $items      = EstimationItem::where('estimation_id', $id)->orderBy('sort_order')->get();
        $sections   = $this->groupItemsBySections($items);

        return response()->json(array_merge(
            $estimation->toArray(),
            ['sections' => array_values($sections)]
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Estimation View Data
    | — Estimation fields + items sections + site visit details
    |--------------------------------------------------------------------------
    */
    public function estimationViewData($id)
    {
        $estimation = Estimation::findOrFail($id);
        $items      = EstimationItem::where('estimation_id', $id)->orderBy('sort_order')->get();
        $sections   = $this->groupItemsBySections($items);

        // ── Site Visit data (always included; null if no lead / no visit) ──
        $siteVisitData = null;

        if ($estimation->lead_id) {
            $siteVisit = SiteVisit::where('lead_id', $estimation->lead_id)->first();

            if ($siteVisit) {
                $siteVisitData = [
                    'space_details'          => $siteVisit->space_details,
                    'materials_finishes'     => $siteVisit->materials_finishes,
                    'style_preferences'      => $siteVisit->style_preferences,
                    'appliances_accessories' => $siteVisit->appliances_accessories,
                    'brand_preferences'      => $siteVisit->brand_preferences,
                    'finish_preferences'     => $siteVisit->finish_preferences,
                    'site_condition_notes'   => $siteVisit->site_condition_notes,
                    'labour_charge'          => $siteVisit->labour_charge   ?? 0,
                    'transport_charge'       => $siteVisit->transport_charge ?? 0,
                ];
            }
        }

        return response()->json(array_merge(
            $estimation->toArray(),
            [
                'sections'   => array_values($sections),
                'site_visit' => $siteVisitData,   // null if no lead/visit → JS handles gracefully
            ]
        ));
    }

    public function estimationPdf($id)
    {
        $estimation = Estimation::findOrFail($id);
        $items      = EstimationItem::where('estimation_id', $id)->orderBy('sort_order')->get();
        $sections   = array_values($this->groupItemsBySections($items, true));

        return view('accounts.estimation-pdf', compact('estimation', 'sections'));
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */

    private function syncEstimationToQuotation(Estimation $estimation, array $items): void
    {
        if (!$estimation->lead_id) return;

        $statusMap = [
            'Draft'    => 'Submitted',
            'Sent'     => 'Submitted',
            'Approved' => 'Approved',
            'Rejected' => 'Rejected',
            'Revised'  => 'Negotiation',
        ];

        $quotationNo = preg_replace('/^EST-?/i', 'QT-', $estimation->estimation_no);

        Quotation::updateOrCreate(
            ['lead_id' => $estimation->lead_id, 'quotation_no' => $quotationNo],
            [
                'items'  => json_encode($items),
                'status' => $statusMap[$estimation->status] ?? 'Submitted',
            ]
        );
    }

    private function saveEstimationItems(int $estimationId, array $sections): array
    {
        $sortOrder = 0;
        $flatItems = [];

        foreach ($sections as $section) {
            $sectionName = $section['name'] ?? '';
            foreach (($section['items'] ?? []) as $item) {
                if (empty($item['description'])) continue;

                $sortOrder++;
                $qty       = floatval($item['qty']        ?? 1);
                $unitPrice = floatval($item['unit_price']  ?? 0);
                $amount    = floatval($item['amount']      ?? ($qty * $unitPrice));

                EstimationItem::create([
                    'estimation_id' => $estimationId,
                    'section'       => $sectionName,
                    'description'   => $item['description'],
                    'category'      => $item['category'] ?? null,
                    'unit'          => $item['unit']      ?? 'nos',
                    'qty'           => $qty,
                    'unit_price'    => $unitPrice,
                    'amount'        => $amount,
                    'sort_order'    => $sortOrder,
                ]);

                $flatItems[] = [
                    'section'     => $sectionName,
                    'description' => $item['description'],
                    'category'    => $item['category'] ?? null,
                    'unit'        => $item['unit']      ?? 'nos',
                    'qty'         => $qty,
                    'unit_price'  => $unitPrice,
                    'amount'      => $amount,
                    'sort_order'  => $sortOrder,
                ];
            }
        }

        return $flatItems;
    }

    /**
     * Group EstimationItem collection by section.
     *
     * $asObjects = true  → Eloquent objects (PDF blade)
     * $asObjects = false → plain arrays    (JSON API → JS view/edit modal)
     */
    private function groupItemsBySections($items, bool $asObjects = false): array
    {
        $sections = [];

        foreach ($items as $item) {
            $secName = $item->section ?? 'General';

            if (!isset($sections[$secName])) {
                $sections[$secName] = ['name' => $secName, 'items' => []];
            }

            $sections[$secName]['items'][] = $asObjects ? $item : [
                // ── Primary fields ──
                'id'          => $item->id,
                'description' => $item->description,
                'category'    => $item->category,
                'unit'        => $item->unit,
                'qty'         => $item->qty,
                'unit_price'  => $item->unit_price,
                'amount'      => $item->amount,
                'sort_order'  => $item->sort_order,
                // ── Measurement fields ──
                'length'      => $item->length  ?? null,
                'breadth'     => $item->breadth ?? null,
                'area'        => $item->area    ?? null,
                // ── Aliases so JS view modal works with either naming ──
                'item_name'   => $item->description, // quotation alias
                'price'       => $item->unit_price,  // quotation alias
                'quantity'    => $item->qty,          // quotation alias
            ];
        }

        return $sections;
    }
}
