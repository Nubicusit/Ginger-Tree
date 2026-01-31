<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
  use App\Models\Customer;
class CustomerController extends Controller
{

public function index()
{
    $customers = Customer::latest()->get();
    $totalCustomers = $customers->count();
    return view('customer.customermanagement', compact('customers','totalCustomers'));
}
public function create()
{
    return view('customer.createcustomer');
}
public function store(Request $request)
{
    $data = $request->validate([
        'name'           => 'required|string',
        'project_type'   => 'nullable|string',
        'contact_no'     => 'required|string',
        'email'          => 'nullable|email',
        'address'        => 'nullable|string',
        'customer_type'  => 'nullable|string',
        'industry'       => 'nullable|string',
        'website'        => 'nullable|string',
        'company'        => 'nullable|string',
        'gst_number'     => 'nullable|string',
        'notes'          => 'nullable|string',
        'payment_status' => 'required|in:paid,pending,balance',
    ]);

    // Auto project status from payment status
    $data['project_status'] = match ($request->payment_status) {
        'paid'    => 'completed',
        'balance' => 'in_progress',
        default   => 'pending',
    };

    if ($request->customer_id) {
     
        Customer::where('id', $request->customer_id)->update($data);
    } else {

        $data['customer_id'] = '#' . rand(1000, 9999);
        Customer::create($data);
    }

    return response()->json([
        'success' => true,
        'message' => 'Customer saved successfully'
    ]);
}



}
