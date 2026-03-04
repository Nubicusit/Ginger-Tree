<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
  use App\Models\Customer;
  use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{

public function index()
{
    $customers = Customer::latest()->get();
    $totalCustomers = $customers->count();
    return view('admin.customermanagement', compact('customers','totalCustomers'));
}
public function create()
{
    return view('admin.createcustomer');
}
public function store(Request $request)
{
    $data = $request->validate([
        'name'           => 'required|string',
        'project_type'   => 'nullable|string',
        'contact_no'     => 'required|string',
        'email'          => 'required|email|unique:users,email',
        'address'        => 'nullable|string',
        'customer_type'  => 'nullable|string',
        'industry'       => 'nullable|string',
        'website'        => 'nullable|string',
        'company'        => 'nullable|string',
        'gst_number'     => 'nullable|string',
        'notes'          => 'nullable|string',
        'payment_status' => 'required|in:paid,pending,balance',
    ]);

    // Auto project status
    $data['project_status'] = match ($request->payment_status) {
        'paid'    => 'completed',
        'balance' => 'in_progress',
        default   => 'pending',
    };

    if ($request->customer_id) {

        // 🔹 UPDATE CUSTOMER
        Customer::where('id', $request->customer_id)->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully'
        ]);
    }

    // 🔹 GENERATE CUSTOMER CODE (CUST-0001)
    $lastCustomer = Customer::orderBy('id', 'desc')->first();

    if ($lastCustomer && $lastCustomer->customer_id) {
        $lastNumber = (int) str_replace('CUST-', '', $lastCustomer->customer_id);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    $customerCode = 'CUST-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    $data['customer_id'] = $customerCode;

    // 🔹 CREATE CUSTOMER
    $customer = Customer::create($data);

    // 🔹 GENERATE PASSWORD
    $plainPassword = Str::random(8);

    // 🔹 CREATE LOGIN ACCOUNT
    User::create([
        'user_unique_id'  => $customer->customer_id,
        'name'            => $customer->name,
        'email'           => $customer->email,
        'contact_no'      => $customer->contact_no,
        'password'        => Hash::make($plainPassword),
        'visible_password'=> $plainPassword, // optional
        'role'            => 'customer',
        'status'          => 1,
        'department_id'   => null,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Customer created successfully',
        'password' => $plainPassword
    ]);
}
// public function store(Request $request)
// {
//     $data = $request->validate([
//         'name'           => 'required|string',
//         'project_type'   => 'nullable|string',
//         'contact_no'     => 'required|string',
//         'email'          => 'nullable|email',
//         'address'        => 'nullable|string',
//         'customer_type'  => 'nullable|string',
//         'industry'       => 'nullable|string',
//         'website'        => 'nullable|string',
//         'company'        => 'nullable|string',
//         'gst_number'     => 'nullable|string',
//         'notes'          => 'nullable|string',
//         'payment_status' => 'required|in:paid,pending,balance',
//     ]);

//     // Auto project status from payment status
//     $data['project_status'] = match ($request->payment_status) {
//         'paid'    => 'completed',
//         'balance' => 'in_progress',
//         default   => 'pending',
//     };

//     if ($request->customer_id) {

//         Customer::where('id', $request->customer_id)->update($data);
//     } else {

//         Customer::create($data);
//     }

//     return response()->json([
//         'success' => true,
//         'message' => 'Customer saved successfully'
//     ]);
// }

}
