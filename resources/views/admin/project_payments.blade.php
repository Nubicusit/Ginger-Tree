@extends('layout.app')

@section('title', 'Payment Status')
@section('page-title', 'Payment Status')

@section('content')

<style>
.badge-active   { background:#dbeafe; color:#1d4ed8; }
.badge-completed{ background:#dcfce7; color:#15803d; }
.badge-onhold   { background:#fef9c3; color:#854d0e; }
</style>

<div class="p-6">

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Projects</p>
            <p class="text-2xl font-bold text-gray-800">{{ $projects->count() }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Value</p>
            <p class="text-2xl font-bold text-gray-800">₹{{ number_format($projects->sum('total_value')) }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Received</p>
            <p class="text-2xl font-bold text-green-600">₹{{ number_format($projects->sum('received')) }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-4">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Balance</p>
            <p class="text-2xl font-bold text-red-500">₹{{ number_format($projects->sum('balance')) }}</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-4 mb-4 flex flex-wrap gap-3 items-center">
        <input type="text" id="searchInput" placeholder="Search project / client..."
            class="border border-gray-300 rounded px-3 py-2 text-sm w-56 focus:ring-1 focus:ring-blue-500">
        <button onclick="resetFilters()"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm px-4 py-2 rounded">Reset</button>
        <span class="text-xs text-gray-400 ml-auto" id="rowCount">{{ $projects->count() }} project(s)</span>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Project</th>
                        <th class="px-4 py-3">Client</th>
                        <th class="px-4 py-3">Total Value</th>
                        <th class="px-4 py-3">Received</th>
                        <th class="px-4 py-3">Balance</th>
                        <th class="px-4 py-3">Payments</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Designer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    @forelse($projects as $i => $proj)
                    @php
                        $lead = $proj->lead;
                        $assignedDesignerId = $lead?->designer_id;

                        $designerDept = \App\Models\Department::where('slug', 'designer')->first();
                        $designers    = $designerDept
                            ? \App\Models\User::where('department_id', $designerDept->id)->get()
                            : collect();

                        $assignedDesigner = $designers->firstWhere('id', $assignedDesignerId);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors proj-row"
                        data-search="{{ strtolower($proj->name . ' ' . $proj->client) }}">

                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>

                        {{-- Project Name --}}
                        <td class="px-4 py-3 font-semibold text-gray-800 text-sm">
                            {{ $proj->name }}
                        </td>

                        {{-- Client — clickable if payments cleared --}}
                        <td class="px-4 py-3 text-xs">
                            @if($proj->cleared_count > 0 && $lead)
                                <button
                                    onclick="openDesignerModal(
                                        {{ $lead->id }},
                                        '{{ addslashes($proj->client ?? $lead->client_name) }}',
                                        {{ $assignedDesignerId ?? 'null' }}
                                    )"
                                    class="font-semibold text-blue-600 hover:underline hover:text-blue-800 transition-colors text-left">
                                    {{ $proj->client ?? '—' }}
                                </button>
                            @else
                                <span class="text-gray-600">{{ $proj->client ?? '—' }}</span>
                            @endif
                        </td>

                        {{-- Total Value --}}
                        <td class="px-4 py-3 font-semibold text-gray-800 text-sm">
                            ₹{{ number_format($proj->total_value) }}
                        </td>

                        {{-- Received --}}
                        <td class="px-4 py-3 font-semibold text-green-600 text-sm">
                            ₹{{ number_format($proj->received) }}
                        </td>

                        {{-- Balance --}}
                        <td class="px-4 py-3 font-semibold text-sm {{ $proj->balance > 0 ? 'text-red-500' : 'text-green-600' }}">
                            ₹{{ number_format($proj->balance) }}
                        </td>

                        {{-- Payment summary chips --}}
                        <td class="px-4 py-3 text-xs space-x-1 whitespace-nowrap">
                            @if($proj->cleared_count > 0)
                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold">
                                    ✔ {{ $proj->cleared_count }} Cleared
                                </span>
                            @endif
                            @if($proj->pending_count > 0)
                                <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                    ⏳ {{ $proj->pending_count }} Pending
                                </span>
                            @endif
                            @if($proj->rejected_count > 0)
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 font-semibold">
                                    ✗ {{ $proj->rejected_count }} Rejected
                                </span>
                            @endif
                            @if($proj->cleared_count == 0 && $proj->pending_count == 0 && $proj->rejected_count == 0)
                                <span class="text-gray-300">No payments</span>
                            @endif
                        </td>

                        {{-- Project Status --}}
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold badge-{{ strtolower($proj->status ?? 'active') }}">
                                {{ $proj->status ?? 'Active' }}
                            </span>
                        </td>

                        {{-- Assigned Designer chip --}}
                        <td class="px-4 py-3 text-xs">
                            @if($assignedDesigner)
                                <span id="designer-chip-{{ $lead?->id }}"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    {{ $assignedDesigner->name }}
                                </span>
                            @else
                                <span id="designer-chip-{{ $lead?->id }}"
                                    class="text-gray-300 text-[11px]">
                                    {{ $proj->cleared_count > 0 ? '— tap client to assign' : '—' }}
                                </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-gray-400">No projects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- DESIGNER ASSIGNMENT MODAL                                    --}}
{{-- ============================================================ --}}
<div id="designerModal"
    class="fixed inset-0 hidden z-50 flex items-center justify-center p-4"
    style="background: rgba(15,23,42,0.65); backdrop-filter: blur(6px);">

    <div class="relative w-full max-w-sm rounded-2xl overflow-hidden bg-white shadow-2xl">

        {{-- Top accent bar --}}
        <div class="h-1 w-full" style="background: linear-gradient(90deg, #2563eb, #06b6d4, #10b981);"></div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                    style="background: linear-gradient(135deg, #2563eb, #06b6d4);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm leading-tight">Assign Designer</h3>
                    <p class="text-xs text-gray-400" id="modal_client_label">—</p>
                </div>
            </div>
            <button onclick="closeDesignerModal()"
                class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all text-lg font-light">
                ✕
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">

            {{-- Current assignment chip --}}
            <div id="current_designer_wrap" class="hidden mb-4 flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-2.5">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400 mb-0.5">Currently Assigned</p>
                    <p class="text-sm font-semibold text-indigo-700 truncate" id="current_designer_name"></p>
                </div>
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            {{-- Dropdown --}}
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                Select Designer
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 rounded-full flex items-center justify-center z-10"
                    style="background: linear-gradient(135deg, #2563eb, #06b6d4);">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
                <select id="designer_select"
                    class="w-full text-sm border border-gray-200 rounded-xl pl-10 pr-8 py-2.5 bg-gray-50
                           focus:ring-2 focus:ring-blue-300 focus:outline-none appearance-none cursor-pointer
                           text-gray-700 transition hover:border-blue-300">
                    <option value="">— Select a designer —</option>
                    @php
                        $designerDept = \App\Models\Department::where('slug', 'designer')->first();
                        $allDesigners = $designerDept
                            ? \App\Models\User::where('department_id', $designerDept->id)->get()
                            : collect();
                    @endphp
                    @foreach($allDesigners as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-5 flex gap-2 justify-end">
            <button onclick="closeDesignerModal()"
                class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-500 border border-gray-200
                       hover:bg-gray-50 transition-all">
                Cancel
            </button>
            <button onclick="saveDesigner()"
                class="px-5 py-2 rounded-xl text-sm font-semibold text-white transition-all"
                style="background: linear-gradient(135deg, #2563eb, #06b6d4);
                       box-shadow: 0 2px 8px rgba(37,99,235,0.3);">
                Assign
            </button>
        </div>

    </div>
</div>

{{-- Toast --}}
<div id="toast"
    class="fixed bottom-5 right-5 z-[9999] hidden items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-semibold text-white"
    style="background: linear-gradient(135deg, #059669, #0d9488);">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    <span id="toast_msg">Designer assigned successfully!</span>
</div>

<script>
// ── All designers map for chip update ──
const designerMap = {
    @foreach($allDesigners as $d)
    {{ $d->id }}: "{{ addslashes($d->name) }}",
    @endforeach
};

let _currentLeadId = null;

function openDesignerModal(leadId, clientName, currentDesignerId) {
    _currentLeadId = leadId;

    document.getElementById('modal_client_label').textContent = clientName;

    // Show current designer if already assigned
    const wrap = document.getElementById('current_designer_wrap');
    if (currentDesignerId && designerMap[currentDesignerId]) {
        document.getElementById('current_designer_name').textContent = designerMap[currentDesignerId];
        wrap.classList.remove('hidden');
        wrap.classList.add('flex');
    } else {
        wrap.classList.add('hidden');
        wrap.classList.remove('flex');
    }

    // Pre-select in dropdown
    const sel = document.getElementById('designer_select');
    sel.value = currentDesignerId ?? '';

    document.getElementById('designerModal').classList.remove('hidden');
}

function closeDesignerModal() {
    document.getElementById('designerModal').classList.add('hidden');
    _currentLeadId = null;
}

function saveDesigner() {
    const designerId = document.getElementById('designer_select').value;
    if (!designerId) {
        alert('Please select a designer.');
        return;
    }
    if (!_currentLeadId) return;

    fetch(`/admin/leads/${_currentLeadId}/assign-designer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ designer_id: designerId })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) { alert('Something went wrong.'); return; }

        // Update chip in table row
        const chip = document.getElementById(`designer-chip-${_currentLeadId}`);
        if (chip) {
            chip.outerHTML = `
                <span id="designer-chip-${_currentLeadId}"
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                           bg-indigo-50 text-indigo-700 border border-indigo-200 font-semibold text-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    ${designerMap[designerId] ?? ''}
                </span>`;
        }

        closeDesignerModal();
        showToast('Designer assigned successfully!');
    })
    .catch(() => alert('Network error. Please try again.'));
}

function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toast_msg').textContent = msg;
    t.classList.remove('hidden');
    t.classList.add('flex');
    t.style.opacity = '0';
    t.style.transform = 'translateY(8px)';
    t.style.transition = 'opacity .3s ease, transform .3s ease';
    setTimeout(() => { t.style.opacity = '1'; t.style.transform = 'translateY(0)'; }, 10);
    setTimeout(() => {
        t.style.opacity = '0';
        t.style.transform = 'translateY(8px)';
        setTimeout(() => { t.classList.add('hidden'); t.classList.remove('flex'); }, 300);
    }, 3000);
}

// ── Search ──
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows   = document.querySelectorAll('.proj-row');
    let count    = 0;
    rows.forEach(row => {
        const ok = !search || row.dataset.search.includes(search);
        row.style.display = ok ? '' : 'none';
        if (ok) count++;
    });
    document.getElementById('rowCount').textContent = count + ' project(s)';
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('.proj-row').forEach(r => r.style.display = '');
    document.getElementById('rowCount').textContent = '{{ $projects->count() }} project(s)';
}
</script>
@endsection
