<?php

namespace App\Http\Controllers;
use App\Models\SalesExecutive;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function dashboard()
    {
      return view('sale_executive.Dashboard');
    }
   public function leads()
    {
        if (Auth::user()->role !== 'sales_executive') {
            abort(403);
        }

        $user = Auth::user();

        $salesExecutive = SalesExecutive::where('email', $user->email)->first();

        if (!$salesExecutive) {
            abort(403, 'Sales Executive not found');
        }

        $leads = Lead::where('sales_executive_id', $salesExecutive->id)
            ->latest()
            ->get();

        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'Won')->count();
        $failedLeads = Lead::where('status', 'Lost')->count();
        $totalarrivedLeads = Lead::where('sales_executive_id', $salesExecutive->id)->count();

        return view('sale_executive.leads', compact('leads','failedLeads','convertedLeads','totalLeads','totalarrivedLeads'));
    }

    public function showJson(Lead $lead)
{
    $lead->load('tasks');
    return response()->json([
        'client_name' => $lead->client_name,
        'phone' => $lead->phone,
        'email' => $lead->email,
        'location' => $lead->location,
        'project_type' => $lead->project_type,
        'budget_range' => $lead->budget_range,
        'lead_source' => $lead->lead_source,
        'notes' => $lead->notes,
        'expected_start_date' => $lead->expected_start_date,
        'tasks' => $lead->tasks
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'lead_id' => 'required|exists:leads,id',
        'title' => 'required',
        'followup_date' => 'nullable|date',
    ]);

    Task::create([
        'lead_id' => $request->lead_id,
        'sales_executive_id' => Auth::id(),
        'title' => $request->title,
        'followup_date' => $request->followup_date,
    ]);

    return response()->json([
        'success' => true
    ]);
}
// public function updateTask(Request $request, $id)
// {
//     $task = Task::findOrFail($id);

//     $task->update([
//         'title' => $request->title,
//         'followup_date' => $request->followup_date,
//     ]);

//     return response()->json([
//         'success' => true
//     ]);
// }
public function show($id)
{
    $lead = Lead::with(['tasks' => function ($q) {
        $q->orderBy('followup_date', 'asc');
    }])->findOrFail($id);

    return response()->json($lead);
}
public function leadTasks($leadId)
{
    $tasks = Task::where('lead_id', $leadId)
        ->orderBy('followup_date')
        ->get();

    return response()->json($tasks);
}
public function deleteTask($id)
{
    Task::findOrFail($id)->delete();
    return response()->json(['success' => true]);
}
public function updateTask(Request $request, $id)
{
    $task = Task::findOrFail($id);
    $task->update([
        'title' => $request->title
    ]);

    return response()->json(['success' => true]);
}
public function update(Request $request, Lead $lead)
{
    $data = [];

    // Update notes if provided
    if ($request->has('notes')) {
        $data['notes'] = $request->notes;

        
        if ($lead->status === 'New') {
            $data['status'] = 'Contacted';
        }
    }

    if (!empty($data)) {
        $lead->update($data);
    }

    return response()->json(['success' => true]);
}

}
