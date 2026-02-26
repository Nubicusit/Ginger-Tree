<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Department;
use App\Models\InventoryStock;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

    // Search by name
    if ($request->search) {
        $query->where('client_name', 'like', '%' . $request->search . '%');
    }

    // Filter by status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $leads = $query->latest()->get();
        return view('Dashboard',compact('leads'));
    }

    public function leads(Request $request)
{
    $query = Lead::query();

    if ($request->filled('search')) {
        $query->where('client_name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    $leads = $query->latest()->get();

    $totalLeads = Lead::count();
    $convertedLeads = Lead::where('status', 'Won')->count();
    $failedLeads = Lead::where('status', 'Lost')->count();

    $designers = User::whereHas('department', function ($q) {
        $q->where('slug', 'designer');
    })->orderBy('name')->get();

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

            $stocks = InventoryStock::latest()->get();
            return view('admin.Masters.inventory',compact('stocks'));
        }
        public function getInventoryItems()
{
    return response()->json(
        InventoryStock::select('item_name', 'price')->orderBy('item_name')->get()
    );
}
public function storestock(Request $request)
{
    $request->validate([
        'item_name' => 'required',
        'price'     => 'required|numeric',
        'quantity'  => 'required|numeric',
        'gst_percentage'  => 'required|numeric',
    ]);

    InventoryStock::create([
        'item_name' => $request->item_name,
        'category'  => $request->category,
        'unit'      => $request->unit,
        'price'     => $request->price,
        'gst_percentage'     => $request->gst_percentage,
        'quantity'  => $request->quantity,
    ]);

    return redirect()->back()->with('success', 'Stock added successfully');
}
public function editstock($id)
{
    return InventoryStock::findOrFail($id);
}
public function updatestock(Request $request, $id)
{
    $stock = InventoryStock::findOrFail($id);

    $stock->update([
        'item_name' => $request->item_name,
        'category'  => $request->category,
        'unit'      => $request->unit,
        'price'     => $request->price,
        'gst_percentage'  => $request->gst_percentage,
        'quantity'  => $request->quantity,
        'status'    => $request->quantity > 0 ? 'In Stock' : 'Out of Stock',
    ]);

    return redirect()->route('inventory.index')->with('success', 'Stock updated');
}

public function destroystock($id)
{
    InventoryStock::findOrFail($id)->delete();

    return redirect()->route('inventory.index')->with('success', 'Stock deleted');
}
public function services(){

    $stocks = Service::latest()->get();
    return view('admin.Masters.services',compact('stocks'));
}

public function storeservice(Request $request)
{
    $request->validate([
        'service_name' => 'required',
        'price'     => 'required|numeric',
        'category_service'  => 'nullable|string',
        'gst_percentage' => 'nullable|numeric|min:0',
        'service_tax' => 'nullable|numeric|min:0',
    ]);

    Service::create([
        'service_name' => $request->service_name,
        'category_service'  => $request->category_service,
        'price'     => $request->price,
        'gst_percentage'     => $request->gst_percentage,
        'service_tax'  => $request->service_tax,
    ]);

    return redirect()->back()->with('success', 'Stock added successfully');
}

public function editservice($id)
{
    return Service::findOrFail($id);
}

public function updateservice(Request $request, $id)
{
    $stock = Service::findOrFail($id);

    $stock->update([
        'service_name' => $request->service_name,
        'category_service'  => $request->category_service,

        'price'     => $request->price,
        'gst_percentage'  => $request->gst_percentage,
        'service_tax'  => $request->service_tax,

    ]);
    return redirect()->route('services.index')->with('success', 'Service updated');
}

public function destroyservice($id)
{
    Service::findOrFail($id)->delete();

    return redirect()->route('services.index')->with('success', 'Service deleted');
}
public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt'
    ]);


    $file = fopen($request->file('csv_file'), 'r');

    while (($row = fgetcsv($file)) !== false) {

        Lead::create([
            'client_name' => $row[0],
            'phone' => $row[1],
            'email' => $row[2],
            'location' => $row[3],
            'lead_source' => $row[4],
            'project_type' => $row[5],
            'status' => 'New',
        ]);
    }

    fclose($file);

    return redirect()->back()->with('success', 'CSV Imported Successfully');
}
}
