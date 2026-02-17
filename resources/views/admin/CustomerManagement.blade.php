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
                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-gray-600 font-medium">All Customers</span>
            </label>
            <div class="ml-4 h-6 border-l border-gray-200"></div>
        </div>

        <!-- Stats & Action Buttons -->
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div class="flex gap-4">
                <!-- Total Clients Badge -->
                <div class="bg-sky-500 text-white px-6 py-2 rounded-md font-semibold shadow-sm">
                    Total Clients : {{ $totalCustomers }}
                </div>

            </div>

            <!-- Add/Update Button -->
            <button onclick="openCustomerModal()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-md font-bold uppercase tracking-wider shadow-md">
                ADD AND UPDATE CUSTOMER
            </button>

        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto border-t border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-100">
                    <th class="p-4 w-12">
                        <input type="checkbox" class="table-checkbox w-4 h-4 border-gray-300 rounded">
                    </th>

                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Customer ID</th>

                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-1">
                            Client Name
                            <!-- Sort Icon -->
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                    </th>

                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                            </svg>
                            Project Type
                        </div>
                    </th>

                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Contact NO</th>
                    <!-- <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Location</th> -->
                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Company</th>

                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Payment Status</th>
                    <th class="p-4 text-sm font-semibold text-gray-500 uppercase tracking-wider">Project Status</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($customers as $customer)

                <tr class="hover:bg-gray-50">
                    <td class="p-4">
                        <input type="checkbox"
                            class="table-checkbox"
                            data-id="{{ $customer->id }}"
                            data-customer_id="{{ $customer->customer_id }}"
                            data-name="{{ $customer->name }}"
                            data-project_type="{{ $customer->project_type }}"
                            data-contact_no="{{ $customer->contact_no }}"
                            data-email="{{ $customer->email }}"
                            data-address="{{ $customer->address }}"
                            data-customer_type="{{ $customer->customer_type }}"
                            data-industry="{{ $customer->industry }}"
                            data-website="{{ $customer->website }}"
                            data-company="{{ $customer->company }}"
                            data-gst_number="{{ $customer->gst_number }}"
                            data-payment_status="{{ $customer->payment_status }}"
                            data-notes="{{ $customer->notes }}">
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
                        <span class="px-3 py-1 rounded text-white bg-black font-semibold" style="font-size: 12px;">
                            {{ $customer->payment_status }}
                        </span>
                    </td>

                    <td class="p-4">
                        @php
                        $status = strtolower($customer->project_status);
                        @endphp

                        @if($status === 'completed')
                        <span class="px-3 py-1 rounded text-xs font-semibold bg-green-500 text-white" style="font-size: 12px;">
                            Project Completed
                        </span>
                        @elseif($status === 'in_progress')
                        <span class="px-3 py-1 rounded text-xs font-semibold bg-orange-500 text-white" style="font-size: 12px;">
                            In Progress
                        </span>
                        @elseif($status === 'pending')
                        <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-500 text-white" style="font-size: 12px;">
                            Pending
                        </span>
                        @else
                        <span class="px-3 py-1 rounded text-xs font-semibold bg-gray-400 text-white" style="font-size: 12px;">
                            N/A
                        </span>
                        @endif
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

<div id="customerModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="relative w-full max-w-md bg-white rounded-lg shadow-2xl z-10 flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-blue-600 rounded-t-lg shrink-0">
            <h2 class="text-white font-semibold text-lg tracking-wide">
                Add/Update Customer
            </h2>
            <button onclick="closeCustomerModal()" class="text-white/80 hover:text-white text-xl leading-none transition-colors focus:outline-none" aria-label="Close modal">
                &times;
            </button>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex border-b text-sm bg-gray-50 shrink-0 overflow-x-auto">
            <button
                onclick="switchTab(event, 'customer-info')"
                class="tab-btn px-6 py-3 border-b-2 border-blue-600 text-blue-600 font-medium hover:bg-gray-100 transition-colors whitespace-nowrap"
                data-target="customer-info">
                Customer Info
            </button>
            <button
                onclick="switchTab(event, 'company-profile')"
                class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors whitespace-nowrap"
                data-target="company-profile">
                Company Profile
            </button>
            <button
                onclick="switchTab(event, 'payment-data')"
                class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors whitespace-nowrap"
                data-target="payment-data">
                Payment Data
            </button>
        </div>

        <!-- Scrollable Content Area -->
        <div class="p-6 overflow-y-auto custom-scrollbar flex-grow">
            <form id="customerForm">
                <input type="hidden" name="customer_id">

                <!-- TAB 1: Customer Information -->
                <div id="customer-info" class="tab-content space-y-4 fade-in">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="e.g. John Doe" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow" />
                    </div>

                    <!-- Customer Type -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Customer Type</label>
                        <select name="customer_type" class="w-full rounded border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option>Retail</option>
                            <option>Wholesale</option>
                            <option>Distributor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Project Type <span class="text-red-500">*</span></label>
                        <input type="text" name="project_type" placeholder="e.g. office interior" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-gray-700 font-medium text-sm">Phone Number</label>
                            <span class="text-xs text-gray-400">(Optional)</span>
                        </div>
                        <input type="tel" name="contact_no" value=""
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" placeholder="customer@example.com" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Street Address</label>
                        <input type="text" name="address" placeholder="123 Main St"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>

                <!-- TAB 2: Company Profile -->
                <div id="company-profile" class="tab-content space-y-4 hidden fade-in">
                    <!-- Company Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Company Name</label>
                        <input type="text" name="company" placeholder="e.g. Acme Corp"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Industry -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Industry</label>
                        <select name="industry" class="w-full rounded border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Select Industry</option>
                            <option>Technology</option>
                            <option>Healthcare</option>
                            <option>Finance</option>
                            <option>Retail</option>
                            <option>Manufacturing</option>
                        </select>
                    </div>

                    <!-- Website -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Website</label>
                        <div class="flex items-center">
                            <span class="bg-gray-100 border border-r-0 border-gray-300 rounded-l px-3 py-2 text-gray-500 text-sm">https://</span>
                            <input type="text" name="website" placeholder="www.example.com"
                                class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-l-none" />
                        </div>
                    </div>

                    <!-- Tax ID -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">GST No</label>
                        <input type="text" name="gst_number" placeholder="e.g. 12-3456789"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Internal Notes</label>
                        <textarea name="notes" rows="3" placeholder="Additional details about this customer..."
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                </div>

                <!-- TAB 3: Payment Data -->
                <div id="payment-data" class="tab-content space-y-4 hidden fade-in">
                    <!-- Currency -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Payment Status</label>
                        <select name="payment_status" class="w-full rounded border px-3 py-2">
                            <option value="pending">Pending</option>
                            <option value="balance">Balance</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>

                    <!-- Billing Address Toggle -->
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="sameAddress" name="sameAddress" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" checked>
                        <label for="sameAddress" class="text-sm text-gray-600">Billing address same as shipping</label>
                    </div>

                    <!-- Billing Address (conditionally shown in real app, kept visible for demo) -->
                    <div id="billing-address-fields" class="opacity-50 transition-opacity">
                        <label class="block text-gray-700 font-medium mb-1 text-sm text-xs uppercase tracking-wider mt-2">Billing Address</label>
                        <input type="text" placeholder="Street Address" disabled
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none bg-gray-50 cursor-not-allowed" />
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-lg shrink-0">
            <button onclick="closeCustomerModal()" class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200 transition-all">
                Cancel
            </button>
            <button onclick="saveCustomer()" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-all flex items-center gap-2">
                <span>Save</span>

            </button>
        </div>
    </div>
</div>
<script>
    function openCustomerModal() {
        const modal = document.getElementById('customerModal');
        const form = document.getElementById('customerForm');

        const checked = document.querySelectorAll('.table-checkbox:checked');

        // Reset form
        form.reset();
        form.querySelector('[name="customer_id"]').value = '';

        if (checked.length > 1) {
            alert("Please select only ONE customer to update ❌");
            return;
        }

        if (checked.length === 1) {
            const cb = checked[0];

            // Basic Info
            form.querySelector('[name="customer_id"]').value = cb.dataset.id || '';
            form.querySelector('[name="name"]').value = cb.dataset.name || '';
            form.querySelector('[name="project_type"]').value = cb.dataset.project_type || '';
            form.querySelector('[name="contact_no"]').value = cb.dataset.contact_no || '';
            form.querySelector('[name="email"]').value = cb.dataset.email || '';
            form.querySelector('[name="address"]').value = cb.dataset.address || '';

            // Customer Type (select)
            if (cb.dataset.customer_type) {
                form.querySelector('[name="customer_type"]').value = cb.dataset.customer_type;
            }

            // Company Profile
            form.querySelector('[name="company"]').value = cb.dataset.company || '';
            form.querySelector('[name="gst_number"]').value = cb.dataset.gst_number || '';

            // Industry (select)
            if (cb.dataset.industry) {
                form.querySelector('[name="industry"]').value = cb.dataset.industry;
            }

            // Website (remove https:// if exists)
            if (cb.dataset.website) {
                form.querySelector('[name="website"]').value =
                    cb.dataset.website.replace(/^https?:\/\//, '');
            }

            // Notes (textarea)
            form.querySelector('[name="notes"]').value = cb.dataset.notes || '';

            // Payment
            form.querySelector('[name="payment_status"]').value = cb.dataset.payment_status || 'pending';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCustomerModal() {
        const modal = document.getElementById('customerModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
<script>
    const selectAll = document.getElementById('select-all');

    const tableCheckboxes = document.querySelectorAll('.table-checkbox');

    selectAll.addEventListener('change', function() {
        tableCheckboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });
    });
</script>
<script>

    function switchTab(event, tabId) {
        // 1. Remove active state from all buttons
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // 2. Add active state to the clicked button
        const clickedBtn = event.currentTarget;
        clickedBtn.classList.remove('border-transparent', 'text-gray-500');
        clickedBtn.classList.add('border-blue-600', 'text-blue-600');

        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });

        const targetContent = document.getElementById(tabId);
        if (targetContent) {
            targetContent.classList.remove('hidden');

            targetContent.classList.remove('fade-in');
            void targetContent.offsetWidth;
            targetContent.classList.add('fade-in');
        }
    }

    function saveCustomer() {
        const form = document.getElementById('customerForm');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);

        fetch("{{ route('customers.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Customer saved successfully ✅");
                    closeCustomerModal();
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong ❌");
            });
    }

    document.getElementById('sameAddress').addEventListener('change', function(e) {
        const billingFields = document.getElementById('billing-address-fields');
        const input = billingFields.querySelector('input');

        if (e.target.checked) {
            billingFields.classList.add('opacity-50');
            input.disabled = true;
            input.classList.add('cursor-not-allowed', 'bg-gray-50');
            input.classList.remove('bg-white');
        } else {
            billingFields.classList.remove('opacity-50');
            input.disabled = false;
            input.classList.remove('cursor-not-allowed', 'bg-gray-50');
            input.classList.add('bg-white');
        }
    });
</script>
@endsection
