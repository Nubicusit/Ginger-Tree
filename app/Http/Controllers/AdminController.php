<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Department;

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
        $designers = User::whereHas('department', function ($q) {
        $q->where('slug', 'designer');
    })->orderBy('name')->get();

    // ✅ Get sales executives via department slug
    $salesExecutives = User::whereHas('department', function ($q) {
        $q->where('slug', 'sales_executive');
    })->orderBy('name')->get();
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
        try {
            $request->validate([
                'designer_id' => 'nullable|exists:users,id',
                'sales_executive_id' => 'nullable|exists:users,id',
            ]);

            $lead->update([
                'designer_id' => $request->designer_id,
                'sales_executive_id' => $request->sales_executive_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead assigned successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // public function storeMarketing(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|in:marketing,designer',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:6|confirmed',
    //         'contact_no' => 'nullable|string|max:20',
    //     ]);

    //     // Determine type
    //     $type = $request->type;

    //     // Pick correct name
    //     $name = $type === 'marketing'
    //         ? $request->marketing_name
    //         : $request->designer_name;

    //     if (!$name) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Name is required'
    //         ], 422);
    //     }

    //     $user = User::create([
    //         'name'       => $name,
    //         'email'      => $request->email,
    //         'password'   => Hash::make($request->password), // ✅ manual password
    //         'contact_no' => $request->contact_no,
    //         'role'       => $type === 'marketing'
    //                             ? 'sales_executive'
    //                             : 'designer',
    //         'status'     => 1,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User created successfully'
    //     ]);
    // }

    public function useraccounts()
    {
         $users = User::with('department')
        ->latest()
        ->get();
        $departments = Department::orderBy('name')->get();

        return view('admin.Masters.useraccounts', compact('users','departments'));
    }

    public function storeUser(Request $request)
{

    $request->validate([
        'name'       => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|min:6',
        'department_id' => 'required|exists:departments,id',
        'contact_no' => 'nullable|string|max:15',
    ]);

    User::create([
        'name'             => $request->name,
        'email'            => $request->email,
        'password'         => bcrypt($request->password),
        'visible_password' => $request->password,
        'department_id'    => $request->department_id,
        'contact_no'       => $request->contact_no,
        'status'           => 1,
    ]);

    return redirect()
        ->route('useraccounts')
        ->with('success', 'User created successfully');
}

    public function toggleUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $user->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'status' => $user->status
        ]);
    }
    public function destroyUser(User $user)
{
    $user->delete();

    return redirect()
        ->route('useraccounts')
        ->with('success', 'User deleted successfully');
}

public function storeDepartment(Request $request)
{
    $request->validate([
        'name' => 'required|unique:departments,name'
    ]);

    Department::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name, '_'),
    ]);

    return back()->with('success', 'Department added');
}

public function stocks()
    {

        // Return inventory view
        return view('admin.Masters.inventory');
    }

    }
