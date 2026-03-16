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
                        <!-- <th class="px-4 py-3">Progress</th> -->
                        <th class="px-4 py-3">Payments</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    @forelse($projects as $i => $proj)
                    <tr class="hover:bg-gray-50 transition-colors proj-row"
                        data-search="{{ strtolower($proj->name . ' ' . $proj->client) }}">

                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i + 1 }}</td>

                        {{-- Project Name --}}
                        <td class="px-4 py-3">
                            <!-- <a href="{{ route('accounts.projects.show', $proj->id) }}"
                               class="font-semibold text-blue-600 hover:underline text-sm"> -->
                                {{ $proj->name }}
                            <!-- </a> -->
                        </td>

                        {{-- Client --}}
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $proj->client ?? '—' }}</td>

                        {{-- Total Value --}}
                        <td class="px-4 py-3 font-semibold text-gray-800 text-sm">
                            ₹{{ number_format($proj->total_value) }}
                        </td>

                        {{-- Received (Cleared only) --}}
                        <td class="px-4 py-3 font-semibold text-green-600 text-sm">
                            ₹{{ number_format($proj->received) }}
                        </td>

                        {{-- Balance --}}
                        <td class="px-4 py-3 font-semibold text-sm {{ $proj->balance > 0 ? 'text-red-500' : 'text-green-600' }}">
                            ₹{{ number_format($proj->balance) }}
                        </td>

                        <!-- {{-- Progress Bar --}}
                        <td class="px-4 py-3" style="min-width:110px;">
                            <div class="text-xs text-gray-500 mb-1">{{ $proj->pct }}%</div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden w-24">
                                <div class="h-full rounded-full {{ $proj->pct >= 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                     style="width:{{ $proj->pct }}%"></div>
                            </div>
                        </td> -->

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

<script>
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
