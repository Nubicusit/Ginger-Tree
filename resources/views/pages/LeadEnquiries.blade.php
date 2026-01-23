@extends('layout.app')

@section('title', 'Leads and Inquiries')

@section('page-title', 'Leads and Inquiries')

@section('content')
  
  <!-- Top Stats Section -->
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-8">
    
    <!-- Total Leads Card -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          </div>
          <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total Leads</span>
        </div>
        <span class="text-3xl font-bold text-purple-700">871</span>
      </div>
      <div class="grid grid-cols-3 gap-y-1 text-[10px] font-bold text-gray-600">
        <div>Website: 59</div>
        <div>Referral: 0</div>
        <div>BNI: 0</div>
        <div class="col-span-2">Meta (Facebook, Insta): 812</div>
        <div>Walk In: 0</div>
        <div>Ads: 0</div>
      </div>
    </div>

    <!-- Failed Leads Card -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 011.414.586l4.586 4.586a2 2 0 01.586 1.414v4.018a2 2 0 01-.586 1.414L14.142 19.142a2 2 0 01-1.414.586H8.736a2 2 0 01-1.789-1.106l-3.5-7A2 2 0 015.236 9H10V14z"></path></svg>
          </div>
          <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Failed Leads</span>
        </div>
        <span class="text-3xl font-bold text-red-600">250</span>
      </div>
      <div class="grid grid-cols-3 gap-y-1 text-[10px] font-bold text-gray-600">
        <div>Website: 10</div>
        <div>Referral: 0</div>
        <div>BNI: 0</div>
        <div class="col-span-2">Meta (Facebook, Insta): 120</div>
        <div>Walk In: 0</div>
        <div>Ads: 0</div>
      </div>
    </div>

    <!-- Converted Leads Card -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Converted Leads</span>
        </div>
        <span class="text-3xl font-bold text-green-600">55</span>
      </div>
      <div class="grid grid-cols-3 gap-y-1 text-[10px] font-bold text-gray-600">
        <div>Website: 10</div>
        <div>Referral: 0</div>
        <div>BNI: 0</div>
        <div class="col-span-2">Meta (Facebook, Insta): 120</div>
        <div>Walk In: 0</div>
        <div>Ads: 0</div>
      </div>
    </div>

    <!-- Action Buttons Column -->
    <div class="flex flex-col gap-2">
      <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md text-sm transition-colors shadow-sm">
        ADD Designer
      </button>
      <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md text-sm transition-colors shadow-sm">
        ADD marketing executive
      </button>
    </div>
  </div>

  <!-- Table Section -->
  <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="text-gray-500 text-sm font-semibold bg-gray-50 border-b border-gray-200">
            <th class="px-6 py-4">Name</th>
            <th class="px-6 py-4">Inquiry Type</th>
            <th class="px-6 py-4">Date Created</th>
            <th class="px-6 py-4">Source</th>
            <th class="px-6 py-4">Mobile Phone Number</th>
            <th class="px-6 py-4">Assigned To</th>
            <th class="px-6 py-4">View Details</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <!-- Row 1 -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">John Smith</td>
            <td class="px-6 py-4 text-gray-600">Interior Design</td>
            <td class="px-6 py-4 text-gray-600">01/15/2022</td>
            <td class="px-6 py-4 text-gray-600">Website</td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assign Lead</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
          <!-- Row 2 -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">Sarah Lee</td>
            <td class="px-6 py-4 text-gray-600">Renovation Project</td>
            <td class="px-6 py-4 text-gray-600">01/08/2022</td>
            <td class="px-6 py-4 text-gray-600">Rebaral</td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assign Lead</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
          <!-- Row 3 -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">David Kim</td>
            <td class="px-6 py-4 text-gray-600">New Home Build</td>
            <td class="px-6 py-4 text-gray-600">01/06/2022</td>
            <td class="px-6 py-4 text-gray-600"></td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assign Lead</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
          <!-- Row 4 -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">Megan Brown</td>
            <td class="px-6 py-4 text-gray-600">Interior Design</td>
            <td class="px-6 py-4 text-gray-600">01/08/2022</td>
            <td class="px-6 py-4 text-gray-600">Website</td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assign Lead</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
          <!-- Row 5 -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">Alex Turner</td>
            <td class="px-6 py-4 text-gray-600">Office Design</td>
            <td class="px-6 py-4 text-gray-600">01/09/2022</td>
            <td class="px-6 py-4 text-gray-600">Social Media</td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assign Lead</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
          <!-- Row 6 (Assigned State) -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">Michael Adams</td>
            <td class="px-6 py-4 text-gray-600">Renovation Project</td>
            <td class="px-6 py-4 text-gray-600">01/07/2022</td>
            <td class="px-6 py-4 text-gray-600">Website</td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-green-500 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assigned</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
          <!-- Row 7 (Assigned State) -->
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-semibold">Jessica Patel</td>
            <td class="px-6 py-4 text-gray-600">Stage Remodel</td>
            <td class="px-6 py-4 text-gray-600">01/12/2022</td>
            <td class="px-6 py-4 text-gray-600">Website</td>
            <td class="px-6 py-4 text-xs font-medium text-gray-700">PH +917428730894, +917428730894</td>
            <td class="px-6 py-4">
              <button class="bg-green-500 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[120px]">Assigned</button>
            </td>
            <td class="px-6 py-4">
              <button class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase w-full max-w-[100px]">Details</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

@endsection