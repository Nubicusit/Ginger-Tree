@extends('layout.app')

@section('title', 'Customer Data Management')

@section('page-title', 'Customer Management')

@section('content')
  <!-- Main Card Container -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">

    <!-- Card Header Section -->
    <div class="p-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Customer Data</h1>

      <!-- Filter / Checkbox Row -->
      <div class="flex items-center mb-6">
        <label class="flex items-center space-x-2 cursor-pointer">
          <input type="checkbox" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
          <span class="text-gray-600 font-medium">All Customers</span>
        </label>
        <div class="ml-4 h-6 border-l border-gray-200"></div>
      </div>

      <!-- Stats & Action Buttons -->
      <div class="flex flex-wrap justify-between items-center gap-4">
        <div class="flex gap-4">
          <!-- Total Clients Badge -->
          <div class="bg-sky-500 text-white px-6 py-2 rounded-md font-semibold shadow-sm">
            Total Clients : 102
          </div>
          <!-- Total Payment Balance Badge -->
          <div class="bg-pink-700 text-white px-6 py-2 rounded-md font-semibold shadow-sm">
            Total Payment Balance : 30
          </div>
        </div>

        <!-- Add/Update Button -->
        <a href="{{ url('customers/create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-md font-bold uppercase tracking-wider transition-colors shadow-md text-center">
          ADD AND UPDATE CUSTOMER
        </a>
      </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto border-t border-gray-100">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-y border-gray-100">
            <th class="p-4 w-12">
              <input type="checkbox" class="w-4 h-4 border-gray-300 rounded">
            </th>
            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Customer ID</th>
            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">
              <div class="flex items-center gap-1">
                Client Name
                <!-- Sort Icon -->
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
              </div>
            </th>
            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">
              <div class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Project Type
              </div>
            </th>
            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Contact NO</th>
            <!-- <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Location</th> -->
            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Company</th>

            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Payment Status</th>
            <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">GST number</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
          @foreach($customers as $customer)
            <tr class="hover:bg-gray-50">
                <td class="p-4">
                    <input type="checkbox">
                </td>

                <td class="p-4 text-gray-600">
                    {{ $customer->customer_id }}
                </td>

                <td class="p-4 font-medium text-gray-800">
                    {{ $customer->name }}
                </td>

                <td class="p-4 text-gray-600">
                    {{ $customer->project_type }}
                </td>

                <td class="p-4 text-gray-600">
                    {{ $customer->contact_no }}
                </td>

                <td class="p-4 text-gray-600">
                    {{ $customer->company }}
                </td>

                <td class="p-4">
                    <span class="px-3 py-1 rounded text-white
                        {{ $customer->payment_status == 'paid' ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ strtoupper($customer->payment_status) }}
                    </span>
                </td>

                <td class="p-4 font-mono text-sm">
                    {{ $customer->gst_number }}
                </td>
            </tr>
          @endforeach

          <!-- Row 1 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#15624</td>
            <td class="p-4 font-medium text-gray-800">Priya Sharma</td>
            <td class="p-4 text-gray-600">Apartment Interior</td>
            <td class="p-4 text-gray-600">Beverley Hills</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 2 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#14589</td>
            <td class="p-4 font-medium text-gray-800">Anish & Rupa</td>
            <td class="p-4 text-gray-600">Villa Interior</td>
            <td class="p-4 text-gray-600">Cupertino, CA</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">Payment Completed</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 3 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#14301</td>
            <td class="p-4 font-medium text-gray-800">Kamal Mehta</td>
            <td class="p-4 text-gray-600">Office Interior</td>
            <td class="p-4 text-gray-600">San Francisco</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 4 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#13987</td>
            <td class="p-4 font-medium text-gray-800">Elger Family</td>
            <td class="p-4 text-gray-600">Retail / Showrom</td>
            <td class="p-4 text-gray-600">Venice, CA</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 5 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#13052</td>
            <td class="p-4 font-medium text-gray-800">Tina Kapoor</td>
            <td class="p-4 text-gray-600">Apartment Interior</td>
            <td class="p-4 text-gray-600">Santa Monica</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 6 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#13209</td>
            <td class="p-4 font-medium text-gray-800">Lisa Patel</td>
            <td class="p-4 text-gray-600">Villa Interior</td>
            <td class="p-4 text-gray-600">Pab Alto, CA</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 7 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#13187</td>
            <td class="p-4 font-medium text-gray-800">Zhang Li</td>
            <td class="p-4 text-gray-600">Apartment Interior</td>
            <td class="p-4 text-gray-600">Monna Del Re</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->

          <!-- Row 8 -->
          <!-- <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4"><input type="checkbox" class="w-4 h-4 border-gray-300 rounded"></td>
            <td class="p-4 text-gray-600">#12642</td>
            <td class="p-4 font-medium text-gray-800">Shoalb Khan</td>
            <td class="p-4 text-gray-600">Modular Kitchen</td>
            <td class="p-4 text-gray-600">Los Angeles,</td>
            <td class="p-4">
              <span class="bg-black text-white text-[14px] px-3 py-1 rounded font-bold uppercase tracking-tighter">BALANCE : 300000</span>
            </td>
            <td class="p-4 text-gray-600 font-mono text-sm">27ABCDE1234F2Z5.</td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>

@endsection
