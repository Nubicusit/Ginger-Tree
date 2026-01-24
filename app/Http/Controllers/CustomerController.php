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
    $request->validate([
        'name' => 'required',
        'contact_no' => 'nullable|digits:10',

    ]);

    Customer::create([
        'customer_id' => 'CUST' . rand(1000,9999),
        'name' => $request->name,
        'project_type' => $request->project_type,
        'contact_no' => $request->contact_no,
        'company' => $request->company,
        'payment_status' => $request->payment_status,
        'gst_number' => $request->gst_number,
    ]);

    return redirect()->route('customers.index')->with('success','Customer added');
}


}
