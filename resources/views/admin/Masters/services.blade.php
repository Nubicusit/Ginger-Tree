@extends('layout.app')
@section('title', 'Services')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700&display=swap');
.sw{font-family:'DM Sans',sans-serif;}
.tf{font-family:'Syne',sans-serif;}
.exp-icon{display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:6px;background:#f1f5f9;color:#64748b;transition:background .15s,transform .2s;font-size:10px;font-weight:700;flex-shrink:0;cursor:pointer;}
.exp-icon.open{background:#e0e7ff;color:#4f46e5;transform:rotate(90deg);}
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:500;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;}
.gchip{display:inline-flex;align-items:center;padding:2px 8px;border-radius:6px;background:#fef9c3;color:#854d0e;font-size:12px;font-weight:600;}
.abtn{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;transition:background .15s,transform .12s;cursor:pointer;border:none;}
.abtn:hover{transform:scale(1.08);}
.inp{width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 13px;font-size:14px;color:#1e293b;background:#f8fafc;transition:border-color .15s,background .15s;font-family:'DM Sans',sans-serif;}
.inp:focus{outline:none;border-color:#6366f1;background:#fff;}
.inp[readonly]{background:#f1f5f9;color:#94a3b8;cursor:not-allowed;}
.mbdrop{backdrop-filter:blur(4px);}
.mcard{box-shadow:0 25px 60px rgba(0,0,0,.18);}
.stbl th{font-size:11px;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;font-weight:500;}
.stbl td{font-size:13px;color:#475569;}
</style>

<div x-data="{
    openStockModal:false,openItemsModal:false,
    formAction:'{{ route('services.store') }}',formMethod:'POST',
    service_name:'',category_service:'',price:'',gst_percentage:'',service_tax:'',
    selectedServiceId:'',selectedServiceName:'',
    items:[{name:'',price:'',unit:''}]
}" class="sw">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="tf text-2xl font-bold text-slate-900 tracking-tight">Services</h1>
            <p class="text-sm text-slate-400 mt-0.5">Manage service catalogue, pricing &amp; items</p>
        </div>
        <button @click="openStockModal=true;formAction='{{ route('services.store') }}';formMethod='POST';service_name='';category_service='';price='';gst_percentage='';service_tax='';"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
            style="background:linear-gradient(135deg,#4f46e5,#6366f1);box-shadow:0 4px 14px rgba(99,102,241,.35);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Service
        </button>
    </div>

    <!-- <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Total Services</p>
            <p class="text-2xl font-bold text-slate-800 tf">{{ $stocks->count() }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Avg. Price</p>
            <p class="text-2xl font-bold text-slate-800 tf">&#8377;{{ $stocks->count() ? number_format($stocks->avg('price'),0) : 0 }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-100 px-5 py-4" style="box-shadow:0 2px 12px rgba(0,0,0,.05);">
            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Categories</p>
            <p class="text-2xl font-bold text-slate-800 tf">{{ $stocks->pluck('category_service')->unique()->filter()->count() }}</p>
        </div>
    </div> -->

    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden" style="box-shadow:0 4px 24px rgba(0,0,0,.07);">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                <span class="text-sm font-semibold text-slate-700">Service List</span>
                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $stocks->count() }} records</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="px-5 py-3.5 w-10"></th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Service</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">GST</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Tax Amt</th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                @forelse($stocks as $stock)
                <tbody x-data="{ open: false }">
                    <tr class="border-t border-slate-100 hover:bg-indigo-50/30 transition-colors cursor-pointer" @click="open=!open">
                        <td class="pl-5 py-4">
                            <span class="exp-icon" :class="{'open':open}">&#9654;</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-semibold text-slate-800 leading-tight">{{ $stock->service_name }}</div>
                            @if($stock->items->count())
                            <div class="text-xs text-slate-400 mt-0.5">{{ $stock->items->count() }} item{{ $stock->items->count()>1?'s':'' }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-4"><span class="badge">{{ $stock->category_service ?: '&mdash;' }}</span></td>
                        <td class="px-4 py-4 text-right font-semibold text-slate-800 tabular-nums">&#8377;{{ number_format($stock->price,2) }}</td>
                        <td class="px-4 py-4 text-center"><span class="gchip">{{ $stock->gst_percentage }}%</span></td>
                        <td class="px-4 py-4 text-right text-slate-500 tabular-nums">&#8377;{{ number_format(($stock->price*$stock->gst_percentage)/100,2) }}</td>
                        <td class="px-4 py-4" @click.stop>
                            <div class="flex justify-center items-center gap-1.5">
                                <button type="button"
                                    @click="openItemsModal=true;selectedServiceId={{ $stock->id }};selectedServiceName='{{ addslashes($stock->service_name) }}';items=[{name:'',price:'',unit:''}];"
                                    class="abtn bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="Manage Items">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10"/></svg>
                                </button>
                                <button type="button"
                                    @click="openStockModal=true;formAction='{{ route('services.update',$stock->id) }}';formMethod='PUT';service_name='{{ addslashes($stock->service_name) }}';category_service='{{ addslashes($stock->category_service) }}';price='{{ $stock->price }}';gst_percentage='{{ $stock->gst_percentage }}';service_tax=(parseFloat('{{ $stock->price }}')*parseFloat('{{ $stock->gst_percentage }}'))/100;"
                                    class="abtn bg-blue-50 text-blue-600 hover:bg-blue-100" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('services.destroy',$stock->id) }}" method="POST" onsubmit="return confirm('Delete this service?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="abtn bg-red-50 text-red-500 hover:bg-red-100" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <tr x-show="open"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="bg-slate-50/60">
                        <td colspan="7" class="px-6 py-4">
                            @if($stock->items->isEmpty())
                                <div class="flex items-center gap-2 text-sm text-slate-400 italic">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    No items added yet
                                </div>
                            @else
                                <div class="rounded-xl border border-slate-200 overflow-hidden bg-white">
                                    <table class="w-full stbl">
                                        <thead class="bg-slate-100">
                                            <tr>
                                                <th class="px-4 py-2.5 text-left">Item Name</th>
                                                <th class="px-4 py-2.5 text-left">Price</th>
                                                <th class="px-4 py-2.5 text-left">Unit</th>
                                                <th class="px-4 py-2.5 text-left">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($stock->items as $item)
                                            <tr class="hover:bg-indigo-50/20 transition-colors">
                                                <td class="px-4 py-2.5">{{ $item->item_name }}</td>

<td class="px-4 py-2.5 font-medium text-slate-700">
    &#8377;{{ number_format($item->default_price,2) }}
</td>

<td class="px-4 py-2.5">
    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded">
        {{ $item->unit ?: '—' }}
    </span>
</td>

<td class="px-4 py-2.5">
    <div class="flex gap-1.5">

        <!-- EDIT -->
        <button
            @click.stop="
                openItemsModal = true;
                selectedServiceId = {{ $stock->id }};
                selectedServiceName = '{{ addslashes($stock->service_name) }}';
                items = [{
                    name: '{{ addslashes($item->item_name) }}',
                    price: '{{ $item->default_price }}',
                    unit: '{{ $item->unit }}',
                    id: '{{ $item->id }}'
                }];
            "
            class="abtn bg-blue-50 text-blue-600 hover:bg-blue-100"
            title="Edit Item">
            ✏️
        </button>

        <!-- DELETE -->
        <form action="{{ route('service-items.destroy', $item->id) }}"
      method="POST"
      @click.stop
      onsubmit="return confirm('Delete this item?')">
    @csrf
    @method('DELETE')

    <button type="submit" class="abtn bg-red-50 text-red-500 hover:bg-red-100">
        🗑
    </button>
</form>

    </div>
</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </td>
                    </tr>
                </tbody>

                @empty
                <tbody>
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="text-sm font-medium">No services yet</p>
                                <p class="text-xs">Click "Add Service" to get started</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
                @endforelse
            </table>
        </div>
    </div>

    {{-- SERVICE MODAL --}}
    <div x-show="openStockModal"
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="mbdrop fixed inset-0 flex items-center justify-center bg-black/40 z-50" @click.self="openStockModal=false">
        <div class="mcard bg-white rounded-2xl w-full max-w-md mx-4"
            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="font-semibold text-slate-800 tf" x-text="formMethod==='PUT'?'Edit Service':'New Service'"></h3>
                </div>
                <button @click="openStockModal=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form :action="formAction" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <template x-if="formMethod=='PUT'"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Service Name</label>
                    <input type="text" name="service_name" x-model="service_name" placeholder="e.g. Interior Design" class="inp">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Category</label>
                    <input type="text" name="category_service" x-model="category_service" placeholder="e.g. Design, Civil" class="inp">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Price (&#8377;)</label>
                        <input type="number" name="price" x-model="price" @input="service_tax=(parseFloat(price||0)*parseFloat(gst_percentage||0))/100" placeholder="0.00" class="inp">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">GST %</label>
                        <input type="number" name="gst_percentage" x-model="gst_percentage" @input="service_tax=(parseFloat(price||0)*parseFloat(gst_percentage||0))/100" placeholder="18" class="inp">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tax Amount (auto)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">&#8377;</span>
                        <input type="number" name="service_tax" x-model="service_tax" readonly class="inp pl-8">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="openStockModal=false" class="px-5 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2 text-sm font-semibold text-white rounded-xl" style="background:linear-gradient(135deg,#4f46e5,#6366f1);box-shadow:0 4px 12px rgba(99,102,241,.3);">Save Service</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ITEMS MODAL --}}
    <div x-show="openItemsModal"
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="mbdrop fixed inset-0 flex items-center justify-center bg-black/40 z-50" @click.self="openItemsModal=false">
        <div class="mcard bg-white rounded-2xl w-full max-w-2xl mx-4"
            x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div>
                    <h3 class="font-semibold text-slate-800 tf">Manage Items</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Service: <span class="font-medium text-indigo-600" x-text="selectedServiceName"></span></p>
                </div>
                <button @click="openItemsModal=false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('service-items.store') }}" class="px-6 py-5">
                @csrf
                <input type="hidden" name="service_id" :value="selectedServiceId">
                <div class="grid grid-cols-12 gap-2 mb-2 px-1">
                    <div class="col-span-5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Item Name</div>
                    <div class="col-span-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</div>
                    <div class="col-span-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Unit</div>
                    <div class="col-span-1"></div>
                </div>
                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                    <template x-for="(item,index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <div class="col-span-5">
                                <input type="hidden" :name="'item_id['+index+']'" :value="item.id">
                                <input type="text" :name="'item_name['+index+']'" x-model="item.name" placeholder="Item name" class="inp text-sm py-2"></div>
                            <div class="col-span-3"><input type="number" :name="'price['+index+']'" x-model="item.price" placeholder="0.00" class="inp text-sm py-2"></div>
                            <div class="col-span-3"><input type="text" :name="'unit['+index+']'" x-model="item.unit" placeholder="Nos / Sqft" class="inp text-sm py-2"></div>
                            <div class="col-span-1 flex justify-center">
                                <button type="button" @click="items.splice(index,1)" class="w-7 h-7 rounded-lg flex items-center justify-center text-red-400 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <button type="button" @click="items.push({name:'',price:'',unit:''})"
                    class="mt-3 w-full border-2 border-dashed border-slate-200 hover:border-indigo-400 text-slate-400 hover:text-indigo-500 py-2.5 rounded-xl text-sm font-medium transition-all">
                    + Add Row
                </button>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-100">
                    <button type="button" @click="openItemsModal=false" class="px-5 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-6 py-2 text-sm font-semibold text-white rounded-xl" style="background:linear-gradient(135deg,#059669,#10b981);box-shadow:0 4px 12px rgba(16,185,129,.3);">Save Items</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
