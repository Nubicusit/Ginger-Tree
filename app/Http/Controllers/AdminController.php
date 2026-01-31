<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Designer;
use App\Models\SalesExecutive;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        return view('Dashboard');
    }

    public function leads()
    {
        $leads = Lead::latest()->get();

    $totalLeads = Lead::count();
    $convertedLeads = Lead::where('status', 'Won')->count();
    $failedLeads = Lead::where('status', 'Lost')->count();
    $designers = Designer::orderBy('designer_name')->get();
    $salesExecutives = SalesExecutive::orderBy('name')->get();
    return view('admin.LeadEnquiries', compact(
        'leads',
        'totalLeads',
        'convertedLeads',
        'failedLeads',
        'designers',
        'salesExecutives'
    ));

    }

    public function create()
    {
        return view('admin.createlead');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required',
            'phone' => 'required',
            'lead_source' => 'required',
            'budget_range' => 'required',
            'project_type' => 'required',
        ]);

        Lead::create($request->all());

        return redirect()->route('leads')
            ->with('success', 'Lead created successfully');
    }

    public function edit(Lead $lead)
    {
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $lead->update($request->all());

        return redirect()->route('leads')
            ->with('success', 'Lead updated successfully');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return back()->with('success', 'Lead deleted');
    }
    public function getLead($id)
{
    $lead = Lead::findOrFail($id);

    return response()->json($lead);
}

public function assign(Request $request, Lead $lead)
{

    $request->validate([
        'designer_id' => 'nullable|exists:designers,id',
        'sales_executive_id' => 'nullable|exists:sales_executives,id',
    ]);


    $lead->update([
        'designer_id' => $request->designer_id,
        'sales_executive_id' => $request->sales_executive_id,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Lead assigned successfully',
        'lead' => $lead
    ]);
}

public function storeMarketing(Request $request)
{
    if ($request->type == 'marketing') {

        $sales = SalesExecutive::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
        ]);

        $firstFour = strtolower(substr(preg_replace('/\s+/', '', $request->name), 0, 4));
        $generatedPassword = $firstFour . '123@';

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role' => 'sales_executive',
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'password' => $generatedPassword
        ]);
    }

    // if ($request->type == 'designer') {

    // }
}

}
