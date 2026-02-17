@extends('sales_executive.layout.app')

@section('title', 'Sales Dashboard')

@section('content')

<div class="p-6">
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

        <!-- Arrived Leads Card -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9a3 3 0 11-6 0 3 3 0 016 0zM21 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2M16 11h6m-3-3v6" />
                        </svg>
                    </div>

                    <span class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Arrived Leads</span>
                </div>
                <span class="text-3xl font-bold text-yellow-700">
                    {{ $totalarrivedLeads }}
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
                        <th class="px-6 py-4">View Details</th>
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
                                Show
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
                <label class="label">Location</label>
                <input id="location" class="input" readonly>
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

                <div id="task_list" class="space-y-2 text-sm"></div>
            </div>
        </div>

        <!-- PROJECT INFORMATION -->
        <div class="w-1/2 bg-white rounded-lg p-5 shadow-sm flex flex-col h-full .pb-6">

            <input type="hidden" id="current_lead_id">
            <input type="hidden" id="assigned_designer" value="{{ $lead->designer_id ?? '' }}">
            <input type="hidden" id="assigned_sales" value="{{ $lead->sales_executive_id ?? '' }}">
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

            <!-- NOTES -->
            <div class="mt-2">
                <label class="label font-semibold text-sm">Notes</label>
                <textarea id="lead_notes" class="input w-full h-24 resize-none" placeholder="Add notes here..."></textarea>
            </div>

            <div class="mt-3 flex items-center gap-2">
                <input type="checkbox" id="site_visit" class="w-4 h-4" onchange="handleSiteVisitCheck(this)">

                <label for="site_visit" class="text-sm font-medium text-gray-700">
                    Site Visit Required
                </label>
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
<div id="taskModal"
    class="fixed inset-0 bg-black/40 hidden z-60 flex items-center justify-center">
    <div class="bg-white w-[400px] rounded-lg shadow-xl p-4">

        <h3 class="font-bold mb-3" id="taskModalTitle">Add New Task</h3>
        <input type="hidden" id="editing_task_id">

        <!-- Task Title -->
        <input id="task_title" class="input mb-3" placeholder="Task Title">

        <!-- Followup Date -->
        <input type="date" id="task_date" class="input mb-3">

        <div class="flex justify-end gap-2 mt-3">
            <button onclick="closeTaskModal()">Cancel</button>
            <button onclick="saveTask()" class="bg-blue-600 text-white px-3 py-1 rounded">
                ADD TO TASK
            </button>
        </div>
    </div>
</div>
<!-- SITE VISIT MODAL -->
<div id="siteVisitModal"
    class="fixed inset-0 bg-black/40 hidden z-60 flex items-center justify-center">

    <div class="bg-white w-[400px] rounded-lg shadow-xl p-5">

        <h3 class="font-bold mb-4">Schedule Site Visit</h3>

        <!-- Visit Date -->
        <div class="mb-3">
            <label class="text-sm font-medium">Select Date</label>
            <input type="date" id="site_visit_date" class="input w-full mt-1">
        </div>

        <!-- Visit Time -->
        <div class="mb-4">
            <label class="text-sm font-medium">Select Time</label>
            <input type="time" id="site_visit_time" class="input w-full mt-1">
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeSiteVisitModal()">Cancel</button>
            <button onclick="saveSiteVisitSchedule()"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Save
            </button>
        </div>
    </div>
</div>

<script>
    function openLeadDrawer(leadId) {
        document.getElementById('leadDrawer').classList.remove('hidden');
        document.getElementById('current_lead_id').value = leadId;

        fetch(`/leads/${leadId}`)
            .then(res => res.json())
            .then(lead => {
                document.getElementById('client_name').textContent = lead.client_name;
                document.getElementById('email').textContent = lead.email;

                const nameParts = lead.client_name.split(' ');
                document.getElementById('first_name').value = nameParts[0] || '';
                document.getElementById('last_name').value = nameParts.slice(1).join(' ') || '';

                document.getElementById('avatar_initials').textContent = lead.client_name.split(' ').map(n => n[0]).join('');

                document.getElementById('phone').value = lead.phone;
                document.getElementById('email_input').value = lead.email;
                document.getElementById('location').value = lead.location;
                document.getElementById('project_type').value = lead.project_type;
                document.getElementById('budget_range').value = lead.budget_range;
                document.getElementById('lead_source').value = lead.lead_source;
                document.getElementById('expected_start_date').value = lead.expected_start_date;

                document.getElementById('lead_notes').value = lead.notes || '';
                document.getElementById('site_visit').checked = lead.site_visit == 1;


                renderTasks(lead.tasks);
            })
            .catch(err => console.error(err));
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

    function saveLeadAssignment() {
        const leadId = document.getElementById('current_lead_id').value;
        const designerId = document.getElementById('assigned_designer')?.value || null;
        const salesId = document.getElementById('assigned_sales')?.value || null;
        const notes = document.getElementById('lead_notes').value;
        const siteVisit = document.getElementById('site_visit').checked ? 1 : 0;


        fetch(`/leads/${leadId}/update`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    designer_id: designerId,
                    sales_executive_id: salesId,
                    notes: notes,
                    site_visit: siteVisit
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Lead updated successfully!');
                    closeLeadDrawer();
                    location.reload();
                } else {
                    alert('Something went wrong.');
                }
            })
            .catch(err => console.error(err));
    }

    function saveTask() {
        const leadId = document.getElementById('current_lead_id').value;
        const title = document.getElementById('task_title').value;
        const date = document.getElementById('task_date').value;
        const taskId = document.getElementById('editing_task_id').value;

        let url = '/tasks/store';
        let method = 'POST';

        if (taskId) {
            url = `/tasks/${taskId}/update`;
        }

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    lead_id: leadId,
                    title: title,
                    followup_date: date
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Task saved successfully');
                    closeTaskModal();

                    document.getElementById('editing_task_id').value = '';
                    document.getElementById('task_title').value = '';
                    document.getElementById('task_date').value = '';

                    openLeadDrawer(leadId);
                }
            });
    }

    function editTask(id, title, date) {
        document.getElementById('editing_task_id').value = id;
        document.getElementById('task_title').value = title;
        document.getElementById('task_date').value = date;
        document.getElementById('taskModalTitle').innerText = "Edit Task";
        openTaskModal();
    }

    function renderTasks(tasks) {
        const taskList = document.getElementById('task_list');
        taskList.innerHTML = '';

        if (!tasks || tasks.length === 0) {
            taskList.innerHTML = '<p class="text-xs text-gray-400">No follow-ups yet</p>';
            return;
        }

        let today = new Date().toISOString().split('T')[0];
        let grouped = {};

        // Group by CREATED DATE
        tasks.forEach(task => {
            let createdDate = task.created_at.split('T')[0];

            if (!grouped[createdDate]) grouped[createdDate] = [];
            grouped[createdDate].push(task);
        });

        Object.keys(grouped).sort().reverse().forEach(date => {
            let formattedDate = formatDate(date);
            let label = (date === today) ?
                `Today, ${formattedDate}` :
                formattedDate;

            taskList.innerHTML += `
            <div class="text-xs text-gray-400 mt-3 mb-1 font-semibold">
                ${label}
            </div>
        `;

            grouped[date].forEach(task => {
                let followUp = new Date(task.followup_date);

                let formattedFollowUp = followUp.toLocaleDateString('en-US', {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                taskList.innerHTML += `
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <div class="font-semibold text-sm">${task.title}</div>
                        <div class="text-xs text-gray-500">${formattedFollowUp}</div>
                    </div>

                    <div class="flex gap-2">
                        <button onclick="editTask(${task.id})" class="text-blue-600">✏️</button>
                        <button onclick="deleteTask(${task.id})" class="text-red-600">🗑️</button>
                    </div>
                </div>
            `;
            });
        });
    }

    function formatDate(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function formatDate(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function deleteTask(id) {
        if (!confirm('Delete this task?')) return;

        fetch(`/tasks/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(() => {
                openLeadDrawer(document.getElementById('current_lead_id').value);
            });
    }

    function editTask(id) {
        let newTitle = prompt("Edit task title:");
        if (!newTitle) return;

        fetch(`/tasks/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: newTitle
                })
            })
            .then(res => res.json())
            .then(() => {
                openLeadDrawer(document.getElementById('current_lead_id').value);
            });
    }

    function handleSiteVisitCheck(checkbox) {
        if (checkbox.checked) {
            document.getElementById('siteVisitModal').classList.remove('hidden');
        }
    }

    function closeSiteVisitModal() {
        document.getElementById('siteVisitModal').classList.add('hidden');

        // Optional: uncheck if cancelled
        document.getElementById('site_visit').checked = false;
    }

    function saveSiteVisitSchedule() {
        const leadId = document.getElementById('current_lead_id').value;
        const date = document.getElementById('site_visit_date').value;
        const time = document.getElementById('site_visit_time').value;

        if (!date || !time) {
            alert("Please select both date and time");
            return;
        }

        fetch(`/leads/${leadId}/site-visit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    date: date,
                    time: time
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Site visit scheduled successfully");

                    document.getElementById('site_visit').checked = true;
                    closeSiteVisitModal();
                    location.reload();
                }
            });
    }
</script>
@endsection
