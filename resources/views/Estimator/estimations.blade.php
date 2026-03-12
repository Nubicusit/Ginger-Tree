@extends('Estimator.layout.app')

@section('title', 'Estimations')

@section('content')

<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr style="background-color: #9b9b9b; color: beige;" class="text-sm font-semibold">
                    <th class="px-6 py-4">Client Name</th>
                    <th class="px-6 py-4">Project Type</th>
                    <th class="px-6 py-4">Site Visit Date</th>
                    <th class="px-6 py-4">Budget Sensitivity</th>
                    <th class="px-6 py-4">Site Notes</th>
                    <th class="px-6 py-4">Initial Cost Estimate</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50 text-sm">
                    <td class="px-6 py-4">{{ $lead->client_name }}</td>
                    <td class="px-6 py-4">{{ $lead->project_type }}</td>
                    <td class="px-6 py-4">
                        @if($lead->siteVisit && $lead->siteVisit->visit_datetime)
                        {{ \Carbon\Carbon::parse($lead->siteVisit->visit_datetime)->format('d M Y, h:i A') }}
                        @else - @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lead->siteVisit && $lead->siteVisit->budget_sensitivity)
                        @php $budget = strtolower($lead->siteVisit->budget_sensitivity); @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($budget == 'low') bg-green-50 text-green-700 border border-green-200
                                @elseif($budget == 'medium') bg-yellow-50 text-yellow-700 border border-yellow-200
                                @elseif($budget == 'high') bg-red-50 text-red-700 border border-red-200
                                @endif">
                            {{ ucfirst($budget) }}
                        </span>
                        @else <span class="text-gray-400 text-xs">-</span> @endif
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($lead->siteVisit->site_condition_notes ?? '-', 30) }}</td>
                    <td class="px-6 py-4">{{ $lead->siteVisit->initial_cost_estimate ?? '-' }}</td>
                         <td class="px-6 py-4">
@if($lead->latestQuotation && $lead->latestQuotation->status)

@php
$status = $lead->latestQuotation->status;
@endphp

<span class="px-3 py-1 rounded-full text-xs font-semibold
@if($status == 'Submitted') bg-gray-100 text-gray-700
@elseif($status == 'Negotiation') bg-blue-100 text-blue-700
@elseif($status == 'Approved') bg-green-100 text-green-700
@elseif($status == 'Rejected') bg-red-100 text-red-700
@endif">
{{ $status }}
</span>

@else
<span class="text-gray-400 text-xs">Not Created</span>
@endif
</td>

                    <td class="px-6 py-4">
                        <button onclick="openDetailModal({{ $lead->id }})"
                            class="bg-blue-600 text-white text-[10px] font-bold py-2 px-4 rounded uppercase">
                            View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">No assigned site visits</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Site Visit Detail Modal -->
<div id="detailModal" class="fixed inset-0 hidden z-50 flex items-center justify-center p-4" style="background: rgba(15,23,42,0.7); backdrop-filter: blur(6px);">
    <div class="relative w-full max-w-3xl max-h-[92vh] flex flex-col rounded-2xl overflow-hidden" style="background: #fff; box-shadow: 0 32px 80px rgba(0,0,0,0.25);">

        <!-- Top accent bar -->
        <div class="h-1 w-full" style="background: linear-gradient(90deg, #2563eb, #06b6d4, #10b981);"></div>

        <!-- Header -->
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #2563eb, #06b6d4);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-base leading-tight">Site Visit Details</h3>
                    <p class="text-xs text-gray-400">Full inspection report</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="goToQuotationPage()"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all"
                    style="background: linear-gradient(135deg, #16a34a, #15803d); box-shadow: 0 2px 8px rgba(22,163,74,0.3);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Make Quotation
                </button>
                <button onclick="closeDetailModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all text-lg font-light">✕</button>
            </div>
        </div>
        <!-- Scrollable Body -->
        <div class="overflow-y-auto flex-1 px-7 py-6 space-y-6">

            <!-- Client Card -->
            <div class="flex items-center gap-4 p-4 rounded-2xl" style="background: linear-gradient(135deg, #eff6ff, #ecfeff);">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0" style="background: linear-gradient(135deg, #2563eb, #06b6d4);">
                    <span id="modal_initials">--</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 text-base truncate" id="modal_client_name">-</p>
                    <p class="text-sm text-gray-500 truncate" id="modal_email">-</p>
                </div>
                <span class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">{{ $lead->latestQuotation?->status ?? 'Pending' }}</span>
            </div>
            <!-- Key Info Grid -->
            <div class="grid grid-cols-3 gap-3">
                <div class="rounded-xl p-4 border border-gray-100" style="background:#f8fafc;">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background:#dbeafe;">
                            <svg class="w-3.5 h-3.5" style="color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Visit Date</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800" id="modal_visit_datetime">-</p>
                </div>

                <div class="rounded-xl p-4 border border-gray-100" style="background:#f8fafc;">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background:#fef9c3;">
                            <svg class="w-3.5 h-3.5" style="color:#ca8a04;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Budget</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800" id="modal_budget_sensitivity">-</p>
                </div>

                <div class="rounded-xl p-4 border border-gray-100" style="background:#f8fafc;">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background:#dcfce7;">
                            <svg class="w-3.5 h-3.5" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Cost Estimate</p>
                    </div>
                    <p class="text-sm font-semibold text-gray-800" id="modal_initial_cost_estimate">-</p>
                </div>
            </div>

            <!-- Details Section -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Site Information</p>
                <div class="space-y-2">

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Site Condition Notes</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_site_condition_notes">-</p>
                        </div>
                    </details>

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Space Details</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_space_details">-</p>
                        </div>
                    </details>

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Materials & Finishes</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_materials_finishes">-</p>
                        </div>
                    </details>

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Style Preferences</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_style_preferences">-</p>
                        </div>
                    </details>

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Appliances & Accessories</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_appliances_accessories">-</p>
                        </div>
                    </details>

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Brand Preferences</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_brand_preferences">-</p>
                        </div>
                    </details>

                    <details class="group rounded-xl border border-gray-100 overflow-hidden" style="background:#f8fafc;">
                        <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none list-none">
                            <span class="text-sm font-semibold text-gray-700">Finish Preferences</span>
                            <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-4 pb-3 pt-1 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed" id="modal_finish_preferences">-</p>
                        </div>
                    </details>

                </div>
            </div>

            <!-- Measurement Files -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Measurement Files</p>
                <div id="modal_measurement_files" class="grid grid-cols-3 gap-3">
                    <p class="text-gray-400 text-xs col-span-3">No files uploaded</p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-7 py-4 border-t border-gray-100 flex justify-end" style="background:#f8fafc;">
            <button onclick="closeDetailModal()"
                class="px-6 py-2 rounded-xl text-sm font-semibold text-gray-600 hover:text-gray-800 border border-gray-200 hover:border-gray-300 hover:bg-white transition-all">
                Close
            </button>
        </div>

    </div>
</div>

<!-- Media Viewer -->
<div id="mediaViewer" class="fixed inset-0 hidden z-[999] flex items-center justify-center" style="background:rgba(0,0,0,0.9); backdrop-filter:blur(8px);">
    <button onclick="closeMediaViewer()" class="absolute top-5 right-6 text-white/70 hover:text-white text-3xl font-light transition-colors">✕</button>
    <div id="mediaViewerContent" class="max-w-[90vw] max-h-[90vh] flex items-center justify-center"></div>
</div>

<script>
    let currentLeadId = null;
    function openDetailModal(leadId) {
        currentLeadId = leadId;
        document.getElementById('detailModal').classList.remove('hidden');

        fetch(`/sale-executive/site-visit/${leadId}`)
            .then(res => res.json())
            .then(data => {
                // Client info
                document.getElementById('modal_client_name').innerText = data.client_name ?? '-';
                document.getElementById('modal_email').innerText = data.email ?? '-';
                // document.getElementById('modal_project_type').innerText = data.project_type ?? '-';

                const nameParts = (data.client_name ?? '').trim().split(' ');
                const initials = `${(nameParts[0] ?? ' ')[0]}${(nameParts[1] ?? ' ')[0]}`.toUpperCase();
                document.getElementById('modal_initials').innerText = initials;

                if (data.site_visit) {
                    const sv = data.site_visit;

                    // Format datetime
                    if (sv.visit_datetime) {
                        const dt = new Date(sv.visit_datetime.replace(' ', 'T'));
                        document.getElementById('modal_visit_datetime').innerText = dt.toLocaleString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } else {
                        document.getElementById('modal_visit_datetime').innerText = '-';
                    }

                    document.getElementById('modal_budget_sensitivity').innerText = sv.budget_sensitivity ?? '-';
                    document.getElementById('modal_initial_cost_estimate').innerText = sv.initial_cost_estimate ?? '-';
                    document.getElementById('modal_site_condition_notes').innerText = sv.site_condition_notes ?? '-';
                    document.getElementById('modal_space_details').innerText = sv.space_details ?? '-';
                    document.getElementById('modal_materials_finishes').innerText = sv.materials_finishes ?? '-';
                    document.getElementById('modal_style_preferences').innerText = sv.style_preferences ?? '-';
                    document.getElementById('modal_appliances_accessories').innerText = sv.appliances_accessories ?? '-';
                    document.getElementById('modal_brand_preferences').innerText = sv.brand_preferences ?? '-';
                    document.getElementById('modal_finish_preferences').innerText = sv.finish_preferences ?? '-';

                    // Measurement files
                    const filesDiv = document.getElementById('modal_measurement_files');
                    filesDiv.innerHTML = '';

                    if (Array.isArray(sv.measurement_files) && sv.measurement_files.length > 0) {
                        sv.measurement_files.forEach(file => {
                            const ext = file.split('.').pop().toLowerCase();
                            if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                                filesDiv.innerHTML += `
                                    <div class="cursor-pointer" onclick="openMediaViewer('/${file}', 'image')">
                                        <img src="/${file}" class="w-full h-28 object-cover rounded border hover:opacity-80" />
                                    </div>`;
                            } else {
                                filesDiv.innerHTML += `
                                    <div class="cursor-pointer" onclick="openMediaViewer('/${file}', 'video')">
                                        <video class="w-full h-28 rounded border">
                                            <source src="/${file}">
                                        </video>
                                    </div>`;
                            }
                        });
                    } else {
                        filesDiv.innerHTML = '<p class="text-gray-400 text-xs col-span-3">No files uploaded</p>';
                    }
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to load site visit details');
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function openMediaViewer(src, type) {
        const content = document.getElementById('mediaViewerContent');
        content.innerHTML = type === 'image' ?
            `<img src="${src}" class="max-w-full max-h-[90vh] rounded shadow-lg" />` :
            `<video src="${src}" controls autoplay class="max-w-full max-h-[90vh] rounded shadow-lg"></video>`;
        document.getElementById('mediaViewer').classList.remove('hidden');
    }

    function closeMediaViewer() {
        document.getElementById('mediaViewerContent').innerHTML = '';
        document.getElementById('mediaViewer').classList.add('hidden');
    }
   function goToQuotationPage() {
    if (currentLeadId) {
        window.open(`/estimator/quotation/create/${currentLeadId}`, '_blank');
    }
}
</script>

@endsection
