@extends('sale_executive.layout.app')

@section('title', 'Sales Dashboard')

@section('content')

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-500 text-sm font-semibold bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Project Type</th>
                    <th class="px-6 py-4">Site Visit Date and Time</th>
                    <th class="px-6 py-4">Assigned staff</th>
                    <th class="px-6 py-4">Measurements</th>
                    <th class="px-6 py-4">Current Site notes</th>
                    <th class="px-6 py-4">Approval to proceed</th>
                    <th class="px-6 py-4">View Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50 text-sm">

                    <!-- Client Name -->
                    <td class="px-6 py-4">
                        {{ $lead->client_name }}
                    </td>
                    <!-- Project Type -->
                    <td class="px-6 py-4">
                        {{ $lead->project_type }}
                    </td>

                    <!-- Site Visit Date & Time -->
                    <td class="px-6 py-4">
                        @if($lead->siteVisit)
                        {{ \Carbon\Carbon::parse($lead->siteVisit->visit_datetime)->format('d M Y, h:i A') }}
                        @else
                        -
                        @endif
                    </td>

                    <!-- Assigned Staff -->
                    <td class="px-6 py-4">
                        {{ $lead->siteVisit->assigned_staff ?? '-' }}
                    </td>

                    <!-- Measurements -->
                    <td class="px-6 py-4">
                        @if($lead->siteVisit && is_array($lead->siteVisit->measurement_files))
                        @php
                        $files = $lead->siteVisit->measurement_files;
                        @endphp

                        <button
                            onclick="openSitevisitDrawer({{ $lead->id }}, true)"
                            class="text-blue-600 underline hover:text-blue-800 text-sm">
                            {{ count($files) }} files
                        </button>

                        @else
                        -
                        @endif
                    </td>

                    <!-- Site Notes -->
                    <td class="px-6 py-4">
                        {{ Str::limit($lead->siteVisit->site_condition_notes ?? '-', 30) }}
                    </td>

                    <!-- Approval -->
                    <td class="px-6 py-4">
                        @if($lead->siteVisit)
                        <span class="px-2 py-1 rounded text-xs
                    {{ $lead->siteVisit->approval_status === 'Yes'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($lead->siteVisit->approval_status) }}
                        </span>
                        @else
                        -
                        @endif
                    </td>

                    <!-- View -->
                    <td class="px-6 py-4">
                        <button
                            onclick="openSitevisitDrawer({{ $lead->id }})"
                            class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase">
                            View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">
                        No site visits scheduled
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="SitevisitDrawer"
        class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center">
        <!-- Drawer -->
        <div class="relative w-[900px] h-[90vh] bg-gray-100 shadow-2xl
            flex gap-4 p-4 rounded-lg overflow-y-auto items-stretch ">

            <!-- CLOSE -->
            <button onclick="closeSitevisitDrawer()"
                class="absolute top-4 right-4 text-xl text-gray-400 hover:text-black">
                ✕
            </button>

            <!-- CLIENT INFORMATION -->
            <div class="w-1/2 bg-white rounded-lg p-5 shadow-sm flex flex-col h-full">

                <h3 class="font-semibold mb-4">Site Visit Information</h3>

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
                    <label class="label">Assigned Staff</label>
                    <input id="assigned_staff" class="input">
                </div>

                <!-- Email -->
                <div class="mt-3">
                    <label class="label">Site visit Date & Time</label>
                    <input id="visit_datetime" type="datetime-local" class="input">
                </div>

                <!-- Address -->
                <div class="mt-3">
                    <label class="label">Measurements</label>
                    <input
                        type="file"
                        id="measurement_files"
                        class="hidden"
                        multiple
                        accept="image/*,video/*"
                        onchange="previewMeasurements(event)" />

                    <button
                        type="button"
                        onclick="document.getElementById('measurement_files').click()"
                        class="mt-2 bg-gray-200 hover:bg-gray-300 text-sm px-3 py-1 rounded">
                        + Add files
                    </button>

                </div>

                <div class="mt-3">
                    <label class="label">Uploaded Measurements</label>
                    <div id="measurement_preview" class="grid grid-cols-3 gap-2"></div>
                </div>
            </div>

            <!-- Right Side (SiteVisit Details) -->
            <div class="w-1/2 bg-white rounded-lg p-5 shadow-sm flex flex-col h-full overflow-y-auto">

                <input type="hidden" id="current_lead_id">

                <!-- Site Condition Notes -->
                <div class="mb-3">
                    <label class="label">Site Condition Notes</label>
                    <textarea id="site_condition_notes" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Space Details</label>
                    <textarea id="space_details" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Materials & Finishes</label>
                    <textarea id="materials_finishes" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Style Preferences</label>
                    <textarea id="style_preferences" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Appliances & Accessories</label>
                    <textarea id="appliances_accessories" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Brand Preferences</label>
                    <textarea id="brand_preferences" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Finish Preferences</label>
                    <textarea id="finish_preferences" class="input w-full h-24 resize-none"></textarea>
                </div>

                <div class="mb-3">
                    <label class="label">Budget Sensitivity</label>
                    <select id="budget_sensitivity" class="input">
                        <option value="">-- Select Budget Sensitivity --</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="label">Initial Cost Estimate</label>
                    <textarea id="initial_cost_estimate" class="input w-full h-24 resize-none"></textarea>
                </div>

                <!-- Approval Status -->
                <div class="mb-3">
                    <label class="label">Approval Status</label>
                    <select id="approval_status" class="input">
                        <option value="">-- Select Status --</option>
                        <option value="Yes">Approved</option>
                        <option value="Hold">Hold</option>
                    </select>

                </div>

                <!-- Quotation Action (JS controlled) -->
<div id="quotationAction" class="mt-3 hidden">
    <a id="manageQuotationBtn"
       href="#"
       class="inline-block bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded">
         Quotation
    </a>
</div>

                <div class="mt-4 flex justify-end">
                    <button
                        onclick="saveSiteVisit()"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded">
                        SAVE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Preview Modal -->
    <div id="mediaViewer"
        class="fixed inset-0 bg-black/80 hidden z-[999] flex items-center justify-center">

        <button onclick="closeMediaViewer()"
            class="absolute top-5 right-6 text-white text-3xl font-bold">
            ✕
        </button>

        <div id="mediaViewerContent"
            class="max-w-[90vw] max-h-[90vh] flex items-center justify-center">
        </div>
    </div>

    <script>
        const drawer = document.getElementById('SitevisitDrawer');

        function openSitevisitDrawer(leadId, scrollToFiles = false) {
            drawer.classList.remove('hidden');
            document.getElementById('current_lead_id').value = leadId;

            fetch(`/sale-executive/site-visit/${leadId}`)
                .then(res => res.json())
                .then(data => {

                    // Client Info
                    document.getElementById('client_name').innerText = data.client_name ?? '-';
                    document.getElementById('email').innerText = data.email ?? '-';

                    const nameParts = (data.client_name ?? '').trim().split(' ');
                    document.getElementById('first_name').value = nameParts[0] ?? '';
                    document.getElementById('last_name').value = nameParts.slice(1).join(' ') ?? '';

                    const initials = `${(nameParts[0] ?? ' ')[0]}${(nameParts[1] ?? ' ')[0]}`.toUpperCase();
                    document.getElementById('avatar_initials').innerText = initials;

                    if (data.site_visit) {
                        document.getElementById('assigned_staff').value = data.site_visit.assigned_staff ?? '';
                        document.getElementById('visit_datetime').value =
                            data.site_visit.visit_datetime ?
                            data.site_visit.visit_datetime.replace(' ', 'T') :
                            '';

                        document.getElementById('site_condition_notes').value = data.site_visit.site_condition_notes ?? '';
                        document.getElementById('space_details').value = data.site_visit.space_details ?? '';
                        document.getElementById('materials_finishes').value = data.site_visit.materials_finishes ?? '';
                        document.getElementById('style_preferences').value = data.site_visit.style_preferences ?? '';
                        document.getElementById('appliances_accessories').value = data.site_visit.appliances_accessories ?? '';
                        document.getElementById('brand_preferences').value = data.site_visit.brand_preferences ?? '';
                        document.getElementById('finish_preferences').value = data.site_visit.finish_preferences ?? '';
                        document.getElementById('budget_sensitivity').value = data.site_visit.budget_sensitivity ?? '';
                        document.getElementById('initial_cost_estimate').value = data.site_visit.initial_cost_estimate ?? '';
                        document.getElementById('approval_status').value = data.site_visit.approval_status ?? '';
                        const quotationBox = document.getElementById('quotationAction');
const quotationBtn = document.getElementById('manageQuotationBtn');

if (data.site_visit && data.site_visit.approval_status === 'Yes') {
    quotationBtn.href = `/sale-executive/quotations/${leadId}`;
    quotationBtn.target = "_blank"; // optional: open PDF in new tab
    quotationBox.classList.remove('hidden');
} else {
    quotationBox.classList.add('hidden');
}

                        // ✅ Measurement preview
                        const preview = document.getElementById('measurement_preview');
                        preview.innerHTML = '';

                        if (Array.isArray(data.site_visit.measurement_files)) {
                            data.site_visit.measurement_files.forEach(file => {
                                const ext = file.split('.').pop().toLowerCase();

                                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                                    preview.innerHTML += `
                                <div class="relative">
                                    <img src="/${file}"
                                        onclick="openMediaViewer('/${file}', 'image')"
                                        class="w-full h-24 object-cover rounded border cursor-pointer hover:opacity-80" />
                                        <button
                                onclick="removeExistingFile('${file}', event)"
                                class="absolute top-1 right-1 bg-red-600 text-white text-xs px-1 rounded hidden group-hover:block">
                                ✕
                            </button>
                                </div>
                            `;
                                } else {
                                    preview.innerHTML += `
                                <div class="relative">
                                    <video class="w-full h-24 rounded border cursor-pointer"
                                        onclick="openMediaViewer('/${file}', 'video')">
                                        <source src="/${file}">
                                        <button
                                onclick="removeExistingFile('${file}', event)"
                                class="absolute top-1 right-1 bg-red-600 text-white text-xs px-1 rounded hidden group-hover:block">
                                ✕
                            </button>
                                    </video>
                                </div>
                            `;
                                }
                            });
                        }
                        if (scrollToFiles) {
                            setTimeout(() => {
                                document
                                    .getElementById('measurement_preview')
                                    .scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                            }, 300);
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to load site visit details');
                });
        }

        function closeSitevisitDrawer() {
            drawer.classList.add('hidden');
        }

        function saveSiteVisit() {
            const leadId = document.getElementById('current_lead_id').value;

            const formData = new FormData();
            formData.append('lead_id', leadId);
            formData.append('assigned_staff', document.getElementById('assigned_staff').value);
            formData.append('visit_datetime', document.getElementById('visit_datetime').value);
            formData.append('site_condition_notes', document.getElementById('site_condition_notes').value);
            formData.append('space_details', document.getElementById('space_details').value);
            formData.append('materials_finishes', document.getElementById('materials_finishes').value);
            formData.append('style_preferences', document.getElementById('style_preferences').value);
            formData.append('appliances_accessories', document.getElementById('appliances_accessories').value);
            formData.append('brand_preferences', document.getElementById('brand_preferences').value);
            formData.append('finish_preferences', document.getElementById('finish_preferences').value);
            formData.append('budget_sensitivity', document.getElementById('budget_sensitivity').value);
            formData.append('initial_cost_estimate', document.getElementById('initial_cost_estimate').value);
            formData.append('approval_status', document.getElementById('approval_status').value);



            selectedFiles.forEach(file => {
                formData.append('measurement_files[]', file);
            });

            fetch('/sale-executive/site-visit/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        alert('Site visit updated successfully');
                        // document.getElementById('measurement_files').value = '';
                        location.reload();
                    } else {
                        alert('Something went wrong');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error saving data');
                });
        }
    </script>
    <script>
        let selectedFiles = [];

        function previewMeasurements(event) {
            const preview = document.getElementById('measurement_preview');
            const files = Array.from(event.target.files);

            files.forEach((file, index) => {
                selectedFiles.push(file);
                const fileIndex = selectedFiles.length - 1;
                const url = URL.createObjectURL(file);

                if (file.type.startsWith('image/')) {
                    preview.innerHTML += `
                <div class="relative group">
                    <img src="${url}"
                         class="w-full h-24 object-cover rounded border cursor-pointer"
                         onclick="openMediaViewer('${url}', 'image')" />
                    <button
                        onclick="removeNewFile(${fileIndex}, event)"
                        class="abso
                        lute top-1 right-1 bg-red-600 text-white text-xs px-1 rounded">
                        ✕
                    </button>
                </div>
            `;
                } else {
                    preview.innerHTML += `
                <div class="relative group">
                    <video class="w-full h-24 rounded border cursor-pointer"
                           onclick="openMediaViewer('${url}', 'video')">
                        <source src="${url}">
                    </video>
                    <button
                        onclick="removeNewFile(${fileIndex}, event)"
                        class="absolute top-1 right-1 bg-red-600 text-white text-xs px-1 rounded">
                        ✕
                    </button>
                </div>
            `;
                }
            });

            event.target.value = '';
        }

        function removeNewFile(index, event) {
            event.stopPropagation();
            selectedFiles.splice(index, 1);
            event.target.closest('div').remove();
        }

        function removeExistingFile(file, event) {
            event.stopPropagation();

            if (!confirm('Delete this file?')) return;

            fetch('/sale-executive/site-visit/delete-file', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        file
                    })
                })

                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        event.target.closest('div').remove();
                    } else {
                        alert('Failed to delete file');
                    }
                });
        }
    </script>

    <script>
        function openMediaViewer(src, type) {
            const viewer = document.getElementById('mediaViewer');
            const content = document.getElementById('mediaViewerContent');

            content.innerHTML = '';

            if (type === 'image') {
                content.innerHTML = `
            <img src="${src}"
                 class="max-w-full max-h-[90vh] rounded shadow-lg" />
        `;
            } else {
                content.innerHTML = `
            <video src="${src}"
                   controls autoplay
                   class="max-w-full max-h-[90vh] rounded shadow-lg"></video>
        `;
            }
            viewer.classList.remove('hidden');
        }

        function closeMediaViewer() {
            const viewer = document.getElementById('mediaViewer');
            const content = document.getElementById('mediaViewerContent');

            content.innerHTML = '';
            viewer.classList.add('hidden');
        }
    </script>
    @endsection
