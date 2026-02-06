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
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-purple-600 uppercase tracking-wider">Total Leads</span>
            </div>
            <span class="text-3xl font-bold text-purple-700">
                {{ $totalLeads }}
            </span>
        </div>
        <div class="grid grid-cols-3 gap-y-1 text-[10px] font-bold text-gray-600">
            <div>Website: 59</div>
            <div>Referral: 0</div>
            <div>BNI: 0</div>
            <div>Meta: 812</div>
            <div>Walk In: 0</div>
            <div>Ads: 0</div>
        </div>
    </div>

    <!-- Failed Leads Card -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 011.414.586l4.586 4.586a2 2 0 01.586 1.414v4.018a2 2 0 01-.586 1.414L14.142 19.142a2 2 0 01-1.414.586H8.736a2 2 0 01-1.789-1.106l-3.5-7A2 2 0 015.236 9H10V14z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Failed Leads</span>
            </div>
            <span class="text-3xl font-bold text-red-700">
                {{ $failedLeads }}
            </span>
        </div>
        <div class="grid grid-cols-3 gap-y-1 text-[10px] font-bold text-gray-600">
            <div>Website: 10</div>
            <div>Referral: 0</div>
            <div>BNI: 0</div>
            <div>Meta: 120</div>
            <div>Walk In: 0</div>
            <div>Ads: 0</div>
        </div>
    </div>

    <!-- Converted Leads Card -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Converted Leads</span>
            </div>
            <span class="text-3xl font-bold text-green-700">
                {{ $convertedLeads }}
            </span>
        </div>
        <div class="grid grid-cols-3 gap-y-1 text-[10px] font-bold text-gray-600">
            <div>Website: 10</div>
            <div>Referral: 0</div>
            <div>BNI: 0</div>
            <div>Meta: 120</div>
            <div>Walk In: 0</div>
            <div>Ads: 0</div>
        </div>
    </div>

    <!-- Action Buttons Column -->
    <div class="flex flex-col gap-2">
        <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md text-sm">
            + ADD LEAD
        </button>
        <button
            onclick="openMarketingModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md text-sm transition-colors shadow-sm">
            ADD MARKETING / DESIGNER
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
                    <th class="px-6 py-4">Location</th>
                    <th class="">Mobile Phone Number</th>
                    <th class="px-6 py-4">Lead Status</th>
                    <th class="px-6 py-4">Assign&Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($leads as $lead)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold">
                        {{ $lead->client_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $lead->project_type }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $lead->created_at->format('d/m/Y') }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $lead->lead_source }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $lead->location }}
                    </td>

                    <td class="px-6 py-4 text-xs font-medium text-gray-700">
                        {{ $lead->phone }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-2 rounded text-[10px] font-bold uppercase
@if($lead->status == 'Won')
    bg-green-500 text-white
@elseif($lead->status == 'Lost')
    bg-red-500 text-white
@elseif($lead->status == 'Contacted')
    bg-yellow-400 text-white
@elseif($lead->status == 'Site Visit')
    bg-blue-400 text-white
@else
    bg-orange-500 text-white
@endif">
    {{ $lead->status }}
</span>

                    </td>

                    <td class="px-6 py-4" id="assigned_sales_{{ $lead->id }}">

                        <button
                            onclick="openLeadDrawer({{ $lead->id }})"
                            class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase">
                            @if($lead->salesExecutive)
                            {{ $lead->salesExecutive->name }}
                            @else
                            Assign Lead
                            @endif
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div id="leadDrawer"
    class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center">
    <!-- Drawer -->
    <div class="relative w-[900px] h-[90vh] bg-gray-100 shadow-2xl
            flex gap-4 p-4 rounded-lg overflow-y-auto items-stretch ">

        <!-- CLOSE -->
        <button onclick="closeLeadDrawer()"
            class="absolute top-4 right-4 text-xl text-gray-400 hover:text-black">
            ✕
        </button>

        <!-- CLIENT INFORMATION -->
        <div class="w-1/2 bg-white rounded-lg p-5 shadow-sm flex flex-col h-full">

            <h3 class="font-semibold mb-4">Client Information</h3>

            <!-- Avatar + Name -->
            <div class="flex items-center gap-4 mb-5">
                <!-- Dummy Avatar -->
                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                    <span id="avatar_initials">JD</span>
                </div>

                <!-- Name & Email -->
                <div>
                    <p class="font-semibold text-gray-800 leading-tight" id="client_name">
                        John Doe
                    </p>
                    <p class="text-xs text-gray-500" id="email">
                        johndoe@gmail.com
                    </p>
                </div>
            </div>

            <!-- First / Last Name -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label">First Name</label>
                    <input id="first_name" class="input" readonly>
                </div>
                <div>
                    <label class="label">Last Name</label>
                    <input id="last_name" class="input" readonly>
                </div>
            </div>

            <!-- Phone -->
            <div class="mt-3">
                <label class="label">Phone</label>
                <input id="phone" class="input" readonly>
            </div>

            <!-- Email -->
            <div class="mt-3">
                <label class="label">Email</label>
                <input id="email_input" class="input" readonly>
            </div>

            <!-- Address -->
            <div class="mt-3">
                <label class="label">Address</label>
                <input id="address" class="input" readonly>
            </div>

            <!-- FOLLOW UPS -->
            <div class="mt-5">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-sm">Follow-up Tasks</h4>
                    <button onclick="openTaskModal()"
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded">
                        + ADD A TASK
                    </button>
                </div>

                <div class="text-xs text-gray-400">
                    No follow-ups yet
                </div>
            </div>
        </div>

        <!-- PROJECT INFORMATION -->
        <div class="w-1/2 bg-white rounded-lg p-5 shadow-sm flex flex-col h-full .pb-6">

            <input type="hidden" id="current_lead_id">
            <h3 class="font-semibold mb-4">Project Information</h3>

            <!-- Project Type -->
            <div class="mb-3">
                <label class="label">Project Type</label>
                <input id="project_type" class="input" readonly>
            </div>

            <!-- Budget Range -->
            <div class="mb-3">
                <label class="label">Budget Range</label>
                <input id="budget_range" class="input" readonly>
            </div>

            <!-- Lead Source -->
            <div class="mb-3">
                <label class="label">Lead Source</label>
                <input id="lead_source" class="input" readonly>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="label">Expected Start Date</label>
                <input id="expected_start_date" class="input" readonly>
            </div>

            <!-- Assign Designer -->
            <div class="mb-3">
                <label class="label">Assigned Designer</label>
                <select class="input" id="assigned_designer" name="designer_id">
                    <option value="">Assign Designer</option>

                    @foreach($designers as $designer)
                    <option value="{{ $designer->id }}">
                        {{ $designer->designer_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Assign Marketing -->
            <div class="mb-4">
                <label class="label">Assigned Marketing Executive</label>
                <select class="input" id="assigned_sales" name="sales_executive_id">
                    <option value="">Assign Sales Executive</option>

                    @foreach($salesExecutives as $sales)
                    <option value="{{ $sales->id }}">
                        {{ $sales->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="bottom-0 pb-6
            flex justify-end">
                <button
                    onclick="saveLeadAssignment()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded">
                    SAVE
                </button>
            </div>

        </div>
    </div>
</div>
<div id="taskModal" class="fixed inset-0 bg-black/40 hidden z-60 flex items-center justify-center">
    <div class="bg-white w-[400px] rounded-lg shadow-xl p-4">

        <h3 class="font-bold mb-3">Add New Task</h3>

        <input id="task_title" class="input mb-2" placeholder="Task Title">

        <input type="date" id="task_date" class="input mb-2">

        <div class="flex justify-end gap-2 mt-3">
            <button onclick="closeTaskModal()">Cancel</button>
            <button onclick="saveTask()" class="bg-blue-600 text-white px-3 py-1 rounded">
                ADD TO TASK
            </button>
        </div>
    </div>
</div>

<!-- Marketing / Designer Modal -->
<div id="marketingModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="relative w-full max-w-md bg-white rounded-lg shadow-2xl z-10 flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-blue-600 rounded-t-lg shrink-0">
            <h2 class="text-white font-semibold text-lg tracking-wide">
                Add Marketing/Designer
            </h2>
            <button onclick="closeMarketingModal()" class="text-white/80 hover:text-white text-xl leading-none transition-colors focus:outline-none" aria-label="Close modal">
                &times;
            </button>
        </div>
        <!-- Tabs Navigation -->
        <div class="flex border-b text-sm bg-gray-50 shrink-0 overflow-x-auto">
            <button
                onclick="switchTab(event, 'marketing-info')"
                class="tab-btn px-6 py-3 border-b-2 border-blue-600 text-blue-600 font-medium hover:bg-gray-100 transition-colors whitespace-nowrap"
                data-target="marketing-info">
                Marketing Executive
            </button>
            <button
                onclick="switchTab(event, 'designer-profile')"
                class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors whitespace-nowrap"
                data-target="designer-profile">
                Designer
            </button>
        </div>

        <!-- Scrollable Content Area -->
        <div class="p-6 overflow-y-auto custom-scrollbar flex-grow">
            <form id="marketingForm">
                @csrf
                <input type="hidden" name="sales_executive_id">

                <!-- TAB 1: Customer Information -->
                <div id="marketing-info" class="tab-content space-y-4 fade-in">

                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Executive Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="e.g. John Doe" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow" />
                    </div>


                    <!-- Phone -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-gray-700 font-medium text-sm">Phone Number</label>
                            <span class="text-xs text-gray-400">(Optional)</span>
                        </div>
                        <input type="number" name="contact_no" value=""
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" placeholder="marketingexecutive@example.com" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Address</label>
                        <input type="text" name="address" placeholder="123 Main St"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>

                <!-- TAB 2: Designer Profile -->
                <div id="designer-profile" class="tab-content space-y-4 hidden fade-in">

                    <!-- Designer Name -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Designer Name <span class="text-red-500">*</span></label>
                        <input type="text" name="designer_name" placeholder="e.g. john"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-gray-700 font-medium text-sm">Phone Number</label>
                            <span class="text-xs text-gray-400">(Optional)</span>
                        </div>
                        <input type="number" name="designer_no" value=""
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="designer_email" placeholder="designer@example.com" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Address</label>
                        <input type="text" name="designer_address" placeholder="123 Main St"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-lg shrink-0">
            <button onclick="closeMarketingModal()" class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200 transition-all">
                Cancel
            </button>
            <button onclick="saveMarketer()" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-all flex items-center gap-2">
                <span>Save</span>
            </button>
        </div>
    </div>
</div>

<!-- Add Lead Modal -->
<div id="leadModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">

    <div class="relative w-full max-w-xl bg-white rounded-lg shadow-2xl z-10 flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-blue-600 rounded-t-lg shrink-0">
            <h2 class="text-white font-semibold text-lg tracking-wide">
                Add New Lead
            </h2>
            <button onclick="closeModal()"
                class="text-white/80 hover:text-white text-xl leading-none">
                &times;
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="p-6 overflow-y-auto flex-grow">
            <form id="leadForm" action="{{ route('leads.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <!-- Client Name -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Client Name <span class="text-red-500">*</span>
                        </label>
                        <input name="client_name" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input name="phone" required
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input name="email"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Location</label>
                        <input name="location"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Budget -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Budget Range</label>
                        <input name="budget_range"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Expected Start Date -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Expected Start Date
                        </label>
                        <input type="date" name="expected_start_date"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm">
                    </div>

                    <!-- Lead Source -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Lead Source</label>
                        <select name="lead_source"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option>Website</option>
                            <option>Instagram</option>
                            <option>WhatsApp</option>
                            <option>Walk-in</option>
                            <option>BNI</option>
                            <option>Referral</option>
                            <option>Ads</option>
                        </select>
                    </div>

                    <!-- Project Type -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Project Type</label>
                        <select name="project_type"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option>Residential</option>
                            <option>Commercial</option>
                            <option>Flat</option>
                            <option>Industry</option>
                            <option>Outsourcing</option>
                            <option>Piece Rate</option>
                        </select>
                    </div>

                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-lg shrink-0">
            <button onclick="closeModal()"
                class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100">
                Cancel
            </button>

            <button onclick="document.getElementById('leadForm').submit()"
                class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                Save Lead
            </button>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('leadModal').classList.remove('hidden');
        document.getElementById('leadModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('leadModal').classList.remove('flex');
        document.getElementById('leadModal').classList.add('hidden');
    }
</script>
<script>
    function saveLeadAssignment() {
        const leadId = document.getElementById('current_lead_id').value;

        fetch(`/leads/${leadId}/assign`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    designer_id: document.getElementById('assigned_designer').value,
                    sales_executive_id: document.getElementById('assigned_sales').value
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Lead updated successfully');
                    location.reload(); // reload table to reflect changes
                } else {
                    alert('Something went wrong');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error saving lead assignment');
            });
    }
</script>

<script>
    function openMarketingModal() {
        const modal = document.getElementById('marketingModal');
        const form = document.getElementById('marketingForm');

        const checked = document.querySelectorAll('.table-checkbox:checked');

        // Reset form
        form.reset();
        form.querySelector('[name="sales_executive_id"]').value = '';


        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

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

        // 3. Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });

        // 4. Show the specific tab content
        const targetContent = document.getElementById(tabId);
        if (targetContent) {
            targetContent.classList.remove('hidden');

            targetContent.classList.remove('fade-in');
            void targetContent.offsetWidth;
            targetContent.classList.add('fade-in');
        }
    }

    function saveMarketer() {
        const form = document.getElementById('marketingForm');

        // Detect active tab
        const activeTabBtn = document.querySelector('.tab-btn.border-blue-600');
        const type = activeTabBtn.dataset.target === 'designer-profile' ?
            'designer' :
            'marketing';

        const formData = new FormData(form);
        formData.append('type', type);

        fetch("{{ route('marketing.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            })

            .then(res => res.json())
            .then(data => {
        if (data.success) {
            alert('Saved successfully');
            closeMarketingModal();
            location.reload();
        } else {
            alert('Validation error');
        }
    })
            .catch(err => {
                console.error(err);
                alert('Something went wrong');
            });
    }

    function closeMarketingModal() {
        const modal = document.getElementById('marketingModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
<script>
    function openLeadDrawer(id) {

    document.getElementById('current_lead_id').value = id;

    fetch(`/lead/${id}`)
        .then(res => res.json())
        .then(data => {

            if (!data) {
                alert('Lead not found');
                return;
            }

            document.getElementById('client_name').innerText = data.client_name;
            document.getElementById('email').innerText = data.email ?? '—';

            const nameParts = data.client_name.trim().split(' ');
            document.getElementById('first_name').value = nameParts[0] ?? '';
            document.getElementById('last_name').value = nameParts.slice(1).join(' ') ?? '';

            document.getElementById('phone').value = data.phone ?? '';
            document.getElementById('email_input').value = data.email ?? '';
            document.getElementById('address').value = data.location ?? '';

            document.getElementById('project_type').value = data.project_type ?? '';
            document.getElementById('lead_source').value = data.lead_source ?? '';
            document.getElementById('budget_range').value = data.budget_range ?? '';
            document.getElementById('expected_start_date').value = data.expected_start_date ?? '';

            document.getElementById('assigned_designer').value = data.designer_id ?? '';
            document.getElementById('assigned_sales').value = data.sales_executive_id ?? '';

            setAvatarInitials(data.client_name);

            document.getElementById('leadDrawer').classList.remove('hidden');
        })
        .catch(err => {
            console.error(err);
            alert('Error loading lead data');
        });
}

function setAvatarInitials(name) {
        const initials = name
            .split(' ')
            .map(n => n[0])
            .join('')
            .substring(0, 2)
            .toUpperCase();

        document.getElementById('avatar_initials').innerText = initials;
    }

    function closeLeadDrawer() {
        document.getElementById('leadDrawer').classList.add('hidden');
    }

    function openTaskModal() {
        document.getElementById('taskModal').classList.remove('hidden');
    }

    function closeTaskModal() {
        document.getElementById('taskModal').classList.add('hidden');
    }
    function saveTask() {
    const leadId = document.getElementById('current_lead_id').value;
    const title = document.getElementById('task_title').value;
    const date = document.getElementById('task_date').value;

    fetch('/tasks/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            lead_id: leadId,
            title: title,
            followup_date: date,

        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Task added successfully');
            closeTaskModal();

            document.getElementById('task_title').value = '';
            document.getElementById('task_type').value = '';
            document.getElementById('task_date').value = '';
            document.getElementById('task_notes').value = '';

            openLeadDrawer(leadId);
        }
    });
}
</script>
@endsection
