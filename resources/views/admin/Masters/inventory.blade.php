@extends('layout.app')
@section('title', 'Inventory Stocks')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700&display=swap');
.iw { font-family: 'DM Sans', sans-serif; }
.tf { font-family: 'Syne', sans-serif; }

.abtn { display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;transition:background .15s,transform .12s;cursor:pointer;border:none; }
.abtn:hover { transform:scale(1.08); }

.status-in  { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0; }
.status-out { display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;background:#fef2f2;color:#b91c1c;border:1px solid #fecaca; }
.status-dot { width:6px;height:6px;border-radius:50%;flex-shrink:0; }
.status-in  .status-dot { background:#16a34a; }
.status-out .status-dot { background:#dc2626; }

.cat-badge { display:inline-flex;align-items:center;padding:2px 9px;border-radius:6px;font-size:11px;font-weight:500;background:#f1f5f9;color:#475569; }

.inp { width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 13px;font-size:14px;color:#1e293b;background:#f8fafc;transition:border-color .15s,background .15s;font-family:'DM Sans',sans-serif; }
.inp:focus { outline:none;border-color:#ef4444;background:#fff; }

.mbdrop { backdrop-filter:blur(4px); }
.mcard  { box-shadow:0 25px 60px rgba(0,0,0,.18); }

.th {     font-size: 13px;
    font-weight: 700;
    color: #060606;
    text-transform: uppercase;
    letter-spacing: .07em; }
</style>

<div x-data="{
    openStockModal: false,
    formAction: '{{ route('inventory.store') }}',
    formMethod: 'POST',
    item_name: '', category: '', unit: '', price: '', gst_percentage: '', quantity: ''
}" class="iw">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="tf text-2xl font-bold text-slate-900 tracking-tight">Inventory</h1>
            <p class="text-sm text-slate-400 mt-0.5">Manage stock items, pricing &amp; availability</p>
        </div>
        <button
            @click="openStockModal=true;formAction='{{ route('inventory.store') }}';formMethod='POST';item_name='';category='';unit='';price='';gst_percentage='';quantity='';"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
            style="background:linear-gradient(135deg,#dc2626,#ef4444);box-shadow:0 4px 14px rgba(220,38,38,.35);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Stock
        </button>
    </div>

    {{-- STATS ROW --}}
    <!-- <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Total Items</p>
            <p class="text-2xl font-bold text-slate-800 tf">{{ $stocks->total() }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">In Stock</p>
            <p class="text-2xl font-bold text-green-600 tf">{{ $stocks->where('quantity','>',0)->count() }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Out of Stock</p>
            <p class="text-2xl font-bold text-red-500 tf">{{ $stocks->where('quantity','<=',0)->count() }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Categories</p>
            <p class="text-2xl font-bold text-slate-800 tf">{{ $stocks->pluck('category')->unique()->filter()->count() }}</p>
        </div>
    </div> -->

    {{-- MAIN CARD --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 4px 24px rgba(0,0,0,.07);">

        {{-- card top bar with search --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                <span class="text-sm font-semibold text-slate-700">Stock List</span>
                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $stocks->total() }} records</span>
            </div>
            <form method="GET" action="{{ route('inventory.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search items…"
                        class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-red-300 w-56">
                </div>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white rounded-xl transition"
                    style="background:linear-gradient(135deg,#dc2626,#ef4444);">Search</button>
                @if(request('search'))
                <a href="{{ route('inventory.index') }}"
                    class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50 transition">Reset</a>
                @endif
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="px-6 py-3.5 text-left th">#</th>
                        <th class="px-4 py-3.5 text-left th">Item Name</th>
                        <th class="px-4 py-3.5 text-left th">Category</th>
                        <th class="px-4 py-3.5 text-center th">Unit</th>
                        <th class="px-4 py-3.5 text-right th">Unit Price</th>
                        <th class="px-4 py-3.5 text-center th">GST %</th>
                        <th class="px-4 py-3.5 text-center th">Qty</th>
                        <th class="px-4 py-3.5 text-center th">Status</th>
                        <th class="px-4 py-3.5 text-center th">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($stocks as $i => $stock)
                    <tr class="hover:bg-red-50/20 transition-colors group">

                        {{-- row number --}}
                        <td class="px-6 py-4 text-xs text-slate-400 tabular-nums">
                            {{ $stocks->firstItem() + $i }}
                        </td>

                        {{-- item name --}}
                        <td class="px-4 py-4">
                            <div class="font-semibold text-slate-800 leading-tight">{{ $stock->item_name }}</div>
                        </td>

                        {{-- category --}}
                        <td class="px-4 py-4">
                            <span class="cat-badge">{{ $stock->category ?? '—' }}</span>
                        </td>

                        {{-- unit --}}
                        <td class="px-4 py-4 text-center">
                            <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2 py-0.5 rounded">
                                {{ $stock->unit ?? '—' }}
                            </span>
                        </td>

                        {{-- price --}}
                        <td class="px-4 py-4 text-right font-semibold text-slate-800 tabular-nums">
                            &#8377;{{ number_format($stock->price, 2) }}
                        </td>

                        {{-- gst --}}
                        <td class="px-4 py-4 text-center">
                            <span class="text-xs bg-amber-50 text-amber-700 font-semibold px-2 py-0.5 rounded">
                                {{ number_format($stock->gst_percentage, 0) }}%
                            </span>
                        </td>

                        {{-- quantity --}}
                        <td class="px-4 py-4 text-center font-semibold tabular-nums
                            {{ $stock->quantity > 0 ? 'text-slate-700' : 'text-red-400' }}">
                            {{ $stock->quantity }}
                        </td>

                        {{-- status --}}
                        <td class="px-4 py-4 text-center">
                            @if($stock->quantity > 0)
                                <span class="status-in"><span class="status-dot"></span>In Stock</span>
                            @else
                                <span class="status-out"><span class="status-dot"></span>Out of Stock</span>
                            @endif
                        </td>

                        {{-- actions --}}
                        <td class="px-4 py-4">
                            <div class="flex justify-center items-center gap-1.5">
                                <button type="button"
                                    @click="openStockModal=true;formAction='{{ route('inventory.update',$stock->id) }}';formMethod='PUT';item_name='{{ addslashes($stock->item_name) }}';category='{{ addslashes($stock->category) }}';unit='{{ addslashes($stock->unit) }}';price='{{ $stock->price }}';gst_percentage='{{ $stock->gst_percentage }}';quantity='{{ $stock->quantity }}';"
                                    class="abtn bg-blue-50 text-blue-600 hover:bg-blue-100" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('inventory.destroy',$stock->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this stock item?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="abtn bg-red-50 text-red-500 hover:bg-red-100" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-sm font-medium">No inventory records found</p>
                                @if(request('search'))
                                    <p class="text-xs">No results for "{{ request('search') }}"</p>
                                    <a href="{{ route('inventory.index') }}" class="text-xs text-red-500 hover:underline">Clear search</a>
                                @else
                                    <p class="text-xs">Click "Add Stock" to get started</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($stocks->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">
                Showing {{ $stocks->firstItem() }}–{{ $stocks->lastItem() }} of {{ $stocks->total() }} records
            </p>
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if($stocks->onFirstPage())
                    <span class="px-3 py-1.5 text-xs text-slate-300 border border-slate-100 rounded-lg cursor-not-allowed">&#8592; Prev</span>
                @else
                    <a href="{{ $stocks->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition">&#8592; Prev</a>
                @endif

                {{-- Page numbers --}}
                @foreach($stocks->getUrlRange(max(1,$stocks->currentPage()-2), min($stocks->lastPage(),$stocks->currentPage()+2)) as $page => $url)
                    @if($page == $stocks->currentPage())
                        <span class="px-3 py-1.5 text-xs font-semibold text-white rounded-lg" style="background:linear-gradient(135deg,#dc2626,#ef4444);">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-xs text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($stocks->hasMorePages())
                    <a href="{{ $stocks->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition">Next &#8594;</a>
                @else
                    <span class="px-3 py-1.5 text-xs text-slate-300 border border-slate-100 rounded-lg cursor-not-allowed">Next &#8594;</span>
                @endif
            </div>
        </div>
        @endif

    </div>{{-- /main card --}}


    {{-- ADD / EDIT MODAL --}}
    <div x-show="openStockModal"
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="mbdrop fixed inset-0 flex items-center justify-center bg-black/40 z-50" @click.self="openStockModal=false">

        <div class="mcard bg-white rounded-2xl w-full max-w-lg mx-4"
            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            {{-- modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 tf" x-text="formMethod==='PUT'?'Edit Stock Item':'Add Stock Item'"></h3>
                </div>
                <button @click="openStockModal=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- modal form --}}
            <form :action="formAction" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <template x-if="formMethod==='PUT'"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Item Name <span class="text-red-400">*</span></label>
                    <input type="text" name="item_name" x-model="item_name" required placeholder="e.g. Granite Tile" class="inp">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Category</label>
                    <input type="text" name="category" x-model="category" placeholder="e.g. Flooring, Walls" class="inp">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Unit</label>
                        <input type="text" name="unit" x-model="unit" placeholder="e.g. Sqft, Nos" class="inp">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Unit Price (&#8377;)</label>
                        <input type="number" step="0.01" name="price" x-model="price" placeholder="0.00" class="inp">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">GST %</label>
                        <input type="number" step="0.01" name="gst_percentage" x-model="gst_percentage" placeholder="18" class="inp">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Quantity</label>
                        <input type="number" name="quantity" x-model="quantity" placeholder="0" class="inp">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="openStockModal=false"
                        class="px-5 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 text-sm font-semibold text-white rounded-xl"
                        style="background:linear-gradient(135deg,#dc2626,#ef4444);box-shadow:0 4px 12px rgba(220,38,38,.3);"
                        x-text="formMethod==='PUT'?'Update Stock':'Save Stock'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
