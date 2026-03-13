@extends('Estimator.layout.app')

@section('title', 'Create Quotation')

@section('content')

<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ url()->previous() }}"
            class="w-9 h-9 rounded-xl flex items-center justify-center border border-gray-200 hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Create Quotation</h1>
            <p class="text-sm text-gray-400">for {{ $lead->client_name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">

        <!-- Left: Client & Site Info -->
        <div class="col-span-1 space-y-4">

            <!-- Client Card -->
            <div class="rounded-2xl overflow-hidden border border-gray-100" style="box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <div class="h-1" style="background: linear-gradient(90deg, #2563eb, #06b6d4);"></div>
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                            style="background: linear-gradient(135deg, #2563eb, #06b6d4);">
                            {{ strtoupper(substr($lead->client_name, 0, 1)) }}{{ strtoupper(substr(strstr($lead->client_name, ' '), 1, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $lead->client_name }}</p>
                            <p class="text-xs text-gray-400">{{ $lead->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Project Type</span>
                            <span class="font-semibold text-gray-700">{{ $lead->project_type ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Budget</span>
                            <span class="font-semibold text-gray-700">{{ $siteVisit->budget_sensitivity ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Visit Date</span>
                            <span class="font-semibold text-gray-700">
                                {{ $siteVisit && $siteVisit->visit_datetime ? \Carbon\Carbon::parse($siteVisit->visit_datetime)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quotation Number Card -->
            <div class="rounded-2xl border border-gray-100 p-5" style="background:#f8fafc; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Quotation No.</p>
                <p class="text-2xl font-bold text-blue-600 tracking-wider">{{ $quotationNo }}</p>
                <input type="hidden" id="quotationNumber" value="{{ $quotationNo }}">
                <input type="hidden" id="quotationId" value="{{ $existingQuotation->id ?? '' }}">
            </div>

            <!-- Site Notes Summary -->
            @if($siteVisit && $siteVisit->site_condition_notes)
            <div class="rounded-2xl border border-gray-100 p-5" style="background:#fffbeb;">
                <p class="text-xs font-semibold text-yellow-600 uppercase tracking-widest mb-2">Site Notes</p>
                <p class="text-xs text-gray-600 leading-relaxed">{{ $siteVisit->site_condition_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Right: Quotation Items -->
        <div class="col-span-2">
            <div class="rounded-2xl overflow-hidden border border-gray-100" style="box-shadow: 0 4px 20px rgba(0,0,0,0.06);">
                <div class="h-1" style="background: linear-gradient(90deg, #16a34a, #06b6d4);"></div>
                <div class="p-6">

                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-bold text-gray-800">Quotation Items</h2>
                        <span id="item_count" class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">1 item</span>
                    </div>

                    <!-- Items Wrapper -->
                    <div id="quotation_items_wrapper" class="space-y-4">
                        <div class="quotation-item rounded-xl border border-gray-200 p-4 relative" style="background:#fafafa;">
                            <div class="flex items-center justify-between mb-3">
                                <span class="item-number text-xs font-bold text-gray-400 uppercase tracking-wide">Item #1</span>
                                <button type="button" onclick="removeQuotationItem(this)"
                                    class="remove-btn hidden text-xs font-semibold text-red-500 bg-red-50 border border-red-200 px-3 py-1 rounded-lg hover:bg-red-100 transition">
                                    Remove
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Item Name with Custom Toggle -->
                                <div class="col-span-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Item Name</label>
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" class="custom_toggle w-3.5 h-3.5 accent-blue-600" onchange="toggleCustomItem(this)">
                                            <span class="text-xs text-blue-600 font-semibold">+ Custom Item</span>
                                        </label>
                                    </div>
                                    <select class="q_item w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white" onchange="updatePrice(this)">
                                        <option value="">-- Select Item --</option>
                                        @foreach($inventoryItems as $item)
                                        <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-category="{{ $item->category }}" data-gst="{{ $item->gst_percentage }}">{{ $item->item_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="q_category_row mt-2 hidden">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Category</label>
                                        <input type="text" class="q_category_display w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-600" readonly placeholder="Category">
                                    </div>
                                    <div class="custom_fields hidden mt-2 grid grid-cols-2 gap-2">
                                        <input type="text" class="q_custom_name col-span-2 w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Enter custom item name">
                                        <select class="q_custom_category col-span-2 w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                            <option value="">-- Select Category --</option>
                                            <option value="Flooring">Flooring</option>
                                            <option value="Walls">Walls</option>
                                            <option value="Ceiling">Ceiling</option>
                                            <option value="Furniture">Furniture</option>
                                            <option value="Doors & Windows">Doors & Windows</option>
                                            <option value="Electrical">Electrical</option>
                                            <option value="Sanitary">Sanitary</option>
                                            <option value="Civil">Civil</option>
                                            <option value="Labour">Labour</option>
                                            <option value="Custom">Custom</option>
                                        </select>
                                        <input type="number" class="q_custom_price w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Unit price" oninput="syncCustomPrice(this)">
                                        <div class="flex items-center gap-1 text-xs text-green-600 font-semibold">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Saves to inventory
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Description</label>
                                    <textarea class="q_description w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 h-16"></textarea>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Quantity</label>
                                    <input type="number" class="q_quantity w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Unit</label>
                                    <select class="q_unit w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                        <option value="">-- Select Unit --</option>
                                        <optgroup label="Area">
                                            <option value="Sqft">Sqft – Square Feet</option>
                                            <option value="Sqm">Sqm – Square Metre</option>
                                        </optgroup>
                                        <optgroup label="Length">
                                            <option value="Rft">Rft – Running Feet</option>
                                            <option value="Rmt">Rmt – Running Metre</option>
                                        </optgroup>
                                        <optgroup label="Count">
                                            <option value="Nos">Nos – Numbers / Pieces</option>
                                            <option value="Sets">Sets</option>
                                            <option value="Lots">Lots</option>
                                            <option value="Pairs">Pairs</option>
                                        </optgroup>
                                        <optgroup label="Volume">
                                            <option value="Cft">Cft – Cubic Feet</option>
                                            <option value="Cum">Cum – Cubic Metre</option>
                                        </optgroup>
                                        <optgroup label="Time">
                                            <option value="Hours">Hours</option>
                                            <option value="Days">Days</option>
                                        </optgroup>
                                        <optgroup label="Weight">
                                            <option value="Kg">Kg – Kilogram</option>
                                            <option value="Ltr">Ltr – Litre</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Unit Price</label>
                                    <input type="number" class="q_price w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50" readonly>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">GST %</label>
                                    <input type="number"
                                        class="q_gst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50"
                                        placeholder="GST %"
                                        min="0"
                                        max="100">
                                </div>

                                <!-- Measurements -->
                                <div class="col-span-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Measurements <span class="text-gray-300 font-normal normal-case">(optional)</span></label>
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" class="measure_toggle w-3.5 h-3.5 accent-blue-600" onchange="toggleMeasurement(this)">
                                            <span class="text-xs text-blue-600 font-semibold">+ Add Measurements</span>
                                        </label>
                                    </div>
                                    <div class="measure_fields hidden rounded-xl border border-blue-100 p-3 mt-1" style="background:#f0f7ff;">
                                        <div class="grid grid-cols-5 gap-2 items-end">
                                            <div class="col-span-2">
                                                <label class="text-xs text-gray-400 block mb-1">Length (ft)</label>
                                                <input type="number" step="0.01" class="q_length w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="0.00" oninput="calcArea(this)">
                                            </div>
                                            <div class="flex items-center justify-center pb-2 text-gray-400 font-bold text-lg">×</div>
                                            <div class="col-span-2">
                                                <label class="text-xs text-gray-400 block mb-1">Breadth (ft)</label>
                                                <input type="number" step="0.01" class="q_breadth w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="0.00" oninput="calcArea(this)">
                                            </div>
                                        </div>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs text-gray-400">Area:</span>
                                            <span class="q_area text-sm font-bold text-blue-600">— Sqft</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Add More Items -->
                    <button type="button" onclick="addMoreQuotationItem()"
                        class="w-full mt-4 border-2 border-dashed border-gray-200 hover:border-blue-400 text-gray-400 hover:text-blue-500 py-3 rounded-xl text-sm font-medium transition-all">
                        + Add Another Item
                    </button>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- SERVICES SECTION — NEW ADDITION                    -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">Services</h3>
                                <p class="text-xs text-gray-400 mt-0.5">Add service charges to this quotation</p>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" id="services_toggle" class="w-4 h-4 accent-purple-600" onchange="toggleServicesSection(this)">
                                <span class="text-xs text-purple-600 font-bold">+ Add Services</span>
                            </label>
                        </div>

                        <!-- Services Rows Wrapper (hidden by default) -->
                        <div id="services_wrapper" class="hidden mt-4 space-y-3"></div>

                        <!-- Add Another Service Button (hidden by default) -->
                        <button type="button" id="add_service_btn" onclick="addServiceItem()"
                            class="hidden w-full mt-3 border-2 border-dashed border-purple-200 hover:border-purple-400 text-purple-400 hover:text-purple-600 py-2.5 rounded-xl text-sm font-medium transition-all">
                            + Add Another Service
                        </button>
                    </div>
                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- END SERVICES SECTION                               -->
                    <!-- ═══════════════════════════════════════════════════ -->

                    <!-- Submit -->
                    <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                        <a href="{{ url()->previous() }}"
                            class="px-6 py-2.5 rounded-xl text-sm font-semibold text-gray-500 border border-gray-200 hover:bg-gray-50 transition-all">
                            Cancel
                        </a>
                        <button onclick="submitQuotation()"
                            class="px-8 py-2.5 rounded-xl text-sm font-bold text-white transition-all"
                            style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 4px 12px rgba(37,99,235,0.3);">
                            Save Estimation
                        </button>
                        <a id="pdf_after_save"
                        href="{{ $estimation ? route('estimator.estimation.pdf', $estimation->id) : '#' }}"
                        target="_blank"
                        class="{{ $estimation ? '' : 'hidden' }} px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all items-center gap-2"
                        style="background: linear-gradient(135deg, #dc2626, #b91c1c); box-shadow: 0 4px 12px rgba(220,38,38,0.3);">
                        📄 Generate PDF
                        </a>
                        @if($existingQuotation && $existingQuotation->estimation)
                        <a href="{{ route('estimator.estimation.pdf', $existingQuotation->estimation->id) }}"
                            target="_blank"
                            class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all"
                            style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                            📄 Generate PDF
                        </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const existingItems = @json($items);
</script>

<script>
    const allInventoryItems = @json($inventoryItems);

    // ── All services from DB for JS use ──
const allServices = @json($services);
    // ════════════════════════════════════════════════════
    // EXISTING FUNCTIONS — UNTOUCHED
    // ════════════════════════════════════════════════════

    function updatePrice(select) {
        const item = select.closest('.quotation-item');
        const priceInput = item.querySelector('.q_price');
        const gstInput = item.querySelector('.q_gst');
        const selected = select.options[select.selectedIndex];
        const price = selected.dataset.price || '';
        const gst = selected.dataset.gst || 0;
        const category = selected.dataset.category || '';

        priceInput.value = price;
        if (gstInput) gstInput.value = gst;

        const categoryRow = item.querySelector('.q_category_row');
        const categoryDisplay = item.querySelector('.q_category_display');
        if (categoryDisplay) {
            categoryDisplay.value = category;
        }
        if (category) {
            categoryRow?.classList.remove('hidden');
        } else {
            categoryRow?.classList.add('hidden');
        }
    }

    function syncCustomPrice(input) {
        const item = input.closest('.quotation-item');
        item.querySelector('.q_price').value = input.value;
    }

    function toggleCustomItem(checkbox) {
        const item = checkbox.closest('.quotation-item');
        const dropdown = item.querySelector('.q_item');
        const customFields = item.querySelector('.custom_fields');
        const priceInput = item.querySelector('.q_price');

        if (checkbox.checked) {
            dropdown.classList.add('hidden');
            dropdown.value = '';
            customFields.classList.remove('hidden');
            priceInput.value = '';
        } else {
            dropdown.classList.remove('hidden');
            customFields.classList.add('hidden');
            item.querySelector('.q_custom_name').value = '';
            item.querySelector('.q_custom_price').value = '';
            priceInput.value = '';
        }
    }

    function buildItemNameHTML() {
        return `
        <div class="col-span-2">
            <div class="flex items-center justify-between mb-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Item Name</label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" class="custom_toggle w-3.5 h-3.5 accent-blue-600" onchange="toggleCustomItem(this)">
                    <span class="text-xs text-blue-600 font-semibold">+ Custom Item</span>
                </label>
            </div>
            <select class="q_item w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white" onchange="updatePrice(this)">
                <option value="">-- Select Item --</option>
                ${allInventoryItems.map(item => `<option value="${item.id}" data-price="${item.price}" data-category="${item.category ?? ''}" data-gst="${item.gst_percentage ?? 0}">${item.item_name}</option>`).join('')}
            </select>
            <div class="q_category_row mt-2 hidden">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Category</label>
                <input type="text" class="q_category_display w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-600" readonly placeholder="Category">
            </div>
            <div class="custom_fields hidden mt-2 grid grid-cols-2 gap-2">
                <input type="text" class="q_custom_name col-span-2 w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Enter custom item name">
                <select class="q_custom_category col-span-2 w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                    <option value="">-- Select Category --</option>
                    <option value="Flooring">Flooring</option>
                    <option value="Walls">Walls</option>
                    <option value="Ceiling">Ceiling</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Doors & Windows">Doors & Windows</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Sanitary">Sanitary</option>
                    <option value="Civil">Civil</option>
                    <option value="Labour">Labour</option>
                    <option value="Custom">Custom</option>
                </select>
                <input type="number" class="q_custom_price w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Unit price" oninput="syncCustomPrice(this)">
                <input type="number" class="q_custom_gst w-full border border-blue-200 rounded-lg px-3 py-2 text-sm" placeholder="GST %">
                <div class="flex items-center gap-1 text-xs text-green-600 font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Saves to inventory
                </div>
            </div>
        </div>`;
    }

    function addMoreQuotationItem() {
        const wrapper = document.getElementById('quotation_items_wrapper');
        const newIndex = wrapper.querySelectorAll('.quotation-item').length + 1;

        const div = document.createElement('div');
        div.className = 'quotation-item rounded-xl border border-gray-200 p-4 relative';
        div.style.background = '#fafafa';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <span class="item-number text-xs font-bold text-gray-400 uppercase tracking-wide">Item #${newIndex}</span>
                <button type="button" onclick="removeQuotationItem(this)"
                    class="remove-btn text-xs font-semibold text-red-500 bg-red-50 border border-red-200 px-3 py-1 rounded-lg hover:bg-red-100 transition">
                    Remove
                </button>
            </div>
            <div class="grid grid-cols-2 gap-3">
                ${buildItemNameHTML()}
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Description</label>
                    <textarea class="q_description w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-300 h-16"></textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Quantity</label>
                    <input type="number" class="q_quantity w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Unit</label>
                    <select class="q_unit w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                        <option value="">-- Select Unit --</option>
                        <optgroup label="Area">
                            <option value="Sqft">Sqft – Square Feet</option>
                            <option value="Sqm">Sqm – Square Metre</option>
                        </optgroup>
                        <optgroup label="Length">
                            <option value="Rft">Rft – Running Feet</option>
                            <option value="Rmt">Rmt – Running Metre</option>
                        </optgroup>
                        <optgroup label="Count">
                            <option value="Nos">Nos – Numbers / Pieces</option>
                            <option value="Sets">Sets</option>
                            <option value="Lots">Lots</option>
                            <option value="Pairs">Pairs</option>
                        </optgroup>
                        <optgroup label="Volume">
                            <option value="Cft">Cft – Cubic Feet</option>
                            <option value="Cum">Cum – Cubic Metre</option>
                        </optgroup>
                        <optgroup label="Time">
                            <option value="Hours">Hours</option>
                            <option value="Days">Days</option>
                        </optgroup>
                        <optgroup label="Weight">
                            <option value="Kg">Kg – Kilogram</option>
                            <option value="Ltr">Ltr – Litre</option>
                        </optgroup>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Unit Price</label>
                    <input type="number" class="q_price w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50" readonly>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">GST %</label>
                    <input type="number" class="q_gst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50" placeholder="GST %" min="0" max="100">
                </div>
                <div class="col-span-2">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Measurements <span class="text-gray-300 font-normal normal-case">(optional)</span></label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" class="measure_toggle w-3.5 h-3.5 accent-blue-600" onchange="toggleMeasurement(this)">
                            <span class="text-xs text-blue-600 font-semibold">+ Add Measurements</span>
                        </label>
                    </div>
                    <div class="measure_fields hidden rounded-xl border border-blue-100 p-3 mt-1" style="background:#f0f7ff;">
                        <div class="grid grid-cols-5 gap-2 items-end">
                            <div class="col-span-2">
                                <label class="text-xs text-gray-400 block mb-1">Length (ft)</label>
                                <input type="number" step="0.01" class="q_length w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="0.00" oninput="calcArea(this)">
                            </div>
                            <div class="flex items-center justify-center pb-2 text-gray-400 font-bold text-lg">×</div>
                            <div class="col-span-2">
                                <label class="text-xs text-gray-400 block mb-1">Breadth (ft)</label>
                                <input type="number" step="0.01" class="q_breadth w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="0.00" oninput="calcArea(this)">
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-gray-400">Area:</span>
                            <span class="q_area text-sm font-bold text-blue-600">— Sqft</span>
                        </div>
                    </div>
                </div>
            </div>`;

        wrapper.appendChild(div);
        updateItemCount();
    }

    function removeQuotationItem(button) {
        button.closest('.quotation-item').remove();
        document.querySelectorAll('.quotation-item').forEach((item, i) => {
            item.querySelector('.item-number').textContent = `Item #${i + 1}`;
        });
        updateItemCount();
    }

    function updateItemCount() {
        const count = document.querySelectorAll('.quotation-item').length;
        document.getElementById('item_count').textContent = `${count} item${count > 1 ? 's' : ''}`;
    }

    function submitQuotation() {
        const quotationNo = document.getElementById('quotationNumber').value;
        const quotationId = document.getElementById('quotationId').value;
        const validItems = [];

        document.querySelectorAll('.quotation-item').forEach(row => {
            const isCustom = row.querySelector('.custom_toggle')?.checked;
            const itemSelect = row.querySelector('.q_item');

            if (isCustom) {
                const customName = row.querySelector('.q_custom_name').value.trim();
                if (!customName) return;
                validItems.push({
                    item_id: itemSelect?.value || '',
                    custom_name: row.querySelector('.q_custom_name')?.value || '',
                    description: row.querySelector('.q_description')?.value || '',
                    category: row.querySelector('.q_custom_category')?.value || row.querySelector('.q_category_display')?.value || '',
                    gst_percentage: row.querySelector('.q_custom_gst')?.value || 0,
                    quantity: row.querySelector('.q_quantity')?.value || 1,
                    unit: row.querySelector('.q_unit')?.value || '',
                    price: row.querySelector('.q_price')?.value || 0,
                    length: row.querySelector('.q_length')?.value || '',
                    breadth: row.querySelector('.q_breadth')?.value || '',
                    area: (
                        parseFloat(row.querySelector('.q_length')?.value || 0) *
                        parseFloat(row.querySelector('.q_breadth')?.value || 0)
                    ).toFixed(2)
                });
            } else {
                if (!itemSelect.value) return;
                validItems.push({
                    item_id: itemSelect.value,
                    custom_name: '',
                    description: row.querySelector('.q_description').value,
                    category: row.querySelector('.q_category_display')?.value || '',
                    quantity: row.querySelector('.q_quantity').value,
                    unit: row.querySelector('.q_unit').value,
                    price: row.querySelector('.q_price').value,
                    length: row.querySelector('.q_length')?.value || '',
                    breadth: row.querySelector('.q_breadth')?.value || '',
                    area: (parseFloat(row.querySelector('.q_length')?.value || 0) * parseFloat(row.querySelector('.q_breadth')?.value || 0)).toFixed(2),
                    gst_percentage: row.querySelector('.q_gst')?.value || 0,
                });
            }
        });

        // ── Collect Services first so we can validate together ──
        const serviceItems = [];
        if (document.getElementById('services_toggle').checked) {
            document.querySelectorAll('.service-item').forEach(row => {
                const serviceId = row.querySelector('.s_service').value;
                if (!serviceId) return;
                serviceItems.push({
                    service_id : serviceId,
                    price      : row.querySelector('.s_price').value,
                    gst        : row.querySelector('.s_gst').value,
                    tax        : row.querySelector('.s_tax').value,
                    gst_amount : row.querySelector('.s_gst_amount').value,
                    total      : row.querySelector('.s_total').value,
                });
            });
        }

        if (validItems.length === 0 && serviceItems.length === 0) {
            alert('Please select or enter at least one item or service.');
            return;
        }

        const formData = new FormData();
        formData.append('lead_id', '{{ $lead->id }}');
        formData.append('quotation_no', quotationNo);
        formData.append('quotation_id', quotationId);

        validItems.forEach((item, index) => {
            formData.append(`items[${index}][item_id]`, item.item_id);
            formData.append(`items[${index}][custom_name]`, item.custom_name);
            formData.append(`items[${index}][category]`, item.category);
            formData.append(`items[${index}][description]`, item.description);
            formData.append(`items[${index}][quantity]`, item.quantity);
            formData.append(`items[${index}][unit]`, item.unit);
            formData.append(`items[${index}][price]`, item.price);
            formData.append(`items[${index}][length]`, item.length);
            formData.append(`items[${index}][breadth]`, item.breadth);
            formData.append(`items[${index}][area]`, item.area);
            formData.append(`items[${index}][gst_percentage]`, item.gst_percentage);
        });

        serviceItems.forEach((s, i) => {
            formData.append(`services[${i}][service_id]`,  s.service_id);
            formData.append(`services[${i}][price]`,        s.price);
            formData.append(`services[${i}][gst]`,          s.gst);
            formData.append(`services[${i}][tax]`,          s.tax);
            formData.append(`services[${i}][gst_amount]`,   s.gst_amount);
            formData.append(`services[${i}][total]`,        s.total);
        });

        fetch('{{ route("estimator.quotation.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const pdfBtn = document.getElementById('pdf_after_save');
                    if (pdfBtn) {
                        pdfBtn.href = '/estimator/estimation/' + data.estimation_id + '/pdf';
                        pdfBtn.classList.remove('hidden');
                    }
                    alert('Estimation saved! Click "Generate PDF" to download.');
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Fetch error: ' + err.message));
    }

    function toggleMeasurement(checkbox) {
        const item = checkbox.closest('.quotation-item');
        const fields = item.querySelector('.measure_fields');
        if (checkbox.checked) {
            fields.classList.remove('hidden');
        } else {
            fields.classList.add('hidden');
            item.querySelector('.q_length').value = '';
            item.querySelector('.q_breadth').value = '';
            item.querySelector('.q_area').textContent = '— Sqft';
        }
    }

    function calcArea(input) {
        const item = input.closest('.quotation-item');
        const length = parseFloat(item.querySelector('.q_length').value) || 0;
        const breadth = parseFloat(item.querySelector('.q_breadth').value) || 0;
        const area = length * breadth;
        item.querySelector('.q_area').textContent = area > 0 ? area.toFixed(2) + ' Sqft' : '— Sqft';
    }

    window.addEventListener('DOMContentLoaded', function() {
        let existingItemsParsed = existingItems;
        if (typeof existingItemsParsed === 'string') {
            try { existingItemsParsed = JSON.parse(existingItemsParsed); } catch(e) { existingItemsParsed = []; }
        }
        if (!Array.isArray(existingItemsParsed) || existingItemsParsed.length === 0) return;

        const wrapper = document.getElementById('quotation_items_wrapper');
        wrapper.innerHTML = '';

        existingItemsParsed.forEach((item, index) => {
            addMoreQuotationItem();
            const row = wrapper.querySelectorAll('.quotation-item')[index];

            if (item.custom_name && item.custom_name.trim() !== '') {
                const toggle = row.querySelector('.custom_toggle');
                toggle.checked = true;
                toggleCustomItem(toggle);
                row.querySelector('.q_custom_name').value = item.custom_name || '';
                row.querySelector('.q_custom_price').value = item.price || '';
                row.querySelector('.q_price').value = item.price || '';
                const customCat = row.querySelector('.q_custom_category');
                if (customCat) customCat.value = item.category || '';
                const customGst = row.querySelector('.q_custom_gst');
                if (customGst) customGst.value = item.gst_percentage || 0;
            } else if (item.item_id) {
                const select = row.querySelector('.q_item');
                select.value = item.item_id;
                const categoryRow = row.querySelector('.q_category_row');
                const categoryDisplay = row.querySelector('.q_category_display');
                if (categoryDisplay && item.category) {
                    categoryDisplay.value = item.category;
                    categoryRow.classList.remove('hidden');
                }
                row.querySelector('.q_price').value = item.price || '';
                const gstInput = row.querySelector('.q_gst');
                if (gstInput) gstInput.value = item.gst_percentage || 0;
            }

            row.querySelector('.q_description').value = item.description || '';
            row.querySelector('.q_quantity').value = item.quantity || 1;
            const unitSelect = row.querySelector('.q_unit');
            if (unitSelect) unitSelect.value = item.unit || '';

            if (item.length || item.breadth) {
                const measureToggle = row.querySelector('.measure_toggle');
                measureToggle.checked = true;
                toggleMeasurement(measureToggle);
                row.querySelector('.q_length').value = item.length || '';
                row.querySelector('.q_breadth').value = item.breadth || '';
                calcArea(row.querySelector('.q_length'));
            }
        });

        updateItemCount();
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('q_custom_name')) {
            let itemName = e.target.value;
            let row = e.target.closest('.quotation-item');
            if (itemName.length < 2) return;
            fetch(`/estimator/item-details?item_name=${itemName}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.querySelector('.q_custom_category').value = data.category || '';
                        row.querySelector('.q_unit').value = data.unit || '';
                        row.querySelector('.q_price').value = data.price || '';
                        row.querySelector('.q_gst').value = data.gst_percentage || '';
                    }
                });
        }
    });

    // ════════════════════════════════════════════════════
    // SERVICES SECTION FUNCTIONS — NEW ADDITION
    // ════════════════════════════════════════════════════

    function toggleServicesSection(checkbox) {
        const wrapper = document.getElementById('services_wrapper');
        const addBtn  = document.getElementById('add_service_btn');
        if (checkbox.checked) {
            wrapper.classList.remove('hidden');
            addBtn.classList.remove('hidden');
            // Auto-add first service row if none exist
            if (wrapper.querySelectorAll('.service-item').length === 0) {
                addServiceItem();
            }
        } else {
            wrapper.classList.add('hidden');
            addBtn.classList.add('hidden');
        }
    }

    function buildServiceRow(index) {
        const div = document.createElement('div');
        div.className = 'service-item rounded-xl border border-purple-100 p-4 relative';
        div.style.background = '#faf5ff';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <span class="service-number text-xs font-bold text-purple-400 uppercase tracking-wide">Service #${index}</span>
                <button type="button" onclick="removeServiceItem(this)"
                    class="text-xs font-semibold text-red-500 bg-red-50 border border-red-200 px-3 py-1 rounded-lg hover:bg-red-100 transition ${index === 1 ? 'hidden' : ''}">
                    Remove
                </button>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <!-- Service Name Dropdown -->
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Service Name</label>
                    <select class="s_service w-full border border-purple-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 bg-white" onchange="fillServiceDetails(this)">
                        <option value="">-- Select Service --</option>
                        ${allServices.map(s => `<option value="${s.id}" data-category="${s.category_service ?? ''}" data-price="${s.price ?? 0}" data-gst="${s.gst_percentage ?? 0}" data-tax="${s.service_tax ?? 0}">${s.service_name}</option>`).join('')}
                    </select>
                </div>
                <!-- Category (readonly) -->
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Category</label>
                    <input type="text" class="s_category w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-600" readonly placeholder="Auto-filled on selection">
                </div>
                <!-- Price -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Price (₹)</label>
                    <input type="number" class="s_price w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50" readonly placeholder="0.00">
                </div>
                <!-- GST % -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">GST %</label>
                    <input type="number" class="s_gst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50" readonly placeholder="0">
                </div>
                <!-- Service Tax -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Service Tax (₹)</label>
                    <input type="number" class="s_tax w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50" readonly placeholder="0.00">
                </div>
                <!-- GST Amount (computed) -->
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">GST Amount (₹)</label>
                    <input type="number" class="s_gst_amount w-full border border-purple-100 rounded-lg px-3 py-2 text-sm bg-purple-50 text-purple-700 font-semibold" readonly placeholder="0.00">
                </div>
                <!-- Total -->
                <div class="col-span-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-1">Total (₹)</label>
                    <input type="number" class="s_total w-full border border-purple-200 rounded-lg px-3 py-2 text-sm bg-purple-50 text-purple-700 font-bold" readonly placeholder="0.00">
                </div>
            </div>`;
        return div;
    }

    function addServiceItem() {
        const wrapper = document.getElementById('services_wrapper');
        const index = wrapper.querySelectorAll('.service-item').length + 1;
        wrapper.appendChild(buildServiceRow(index));
    }

    function removeServiceItem(btn) {
        btn.closest('.service-item').remove();
        document.querySelectorAll('.service-item').forEach((row, i) => {
            row.querySelector('.service-number').textContent = `Service #${i + 1}`;
        });
    }

    function fillServiceDetails(select) {
        const row = select.closest('.service-item');
        const opt = select.options[select.selectedIndex];

        const price  = parseFloat(opt.dataset.price || 0);
        const gst    = parseFloat(opt.dataset.gst   || 0);
        const tax    = parseFloat(opt.dataset.tax   || 0);
        const gstAmt = (price * gst) / 100;
        const total  = price + gstAmt + tax;

        row.querySelector('.s_category').value   = opt.dataset.category || '';
        row.querySelector('.s_price').value      = price.toFixed(2);
        row.querySelector('.s_gst').value        = gst;
        row.querySelector('.s_tax').value        = tax.toFixed(2);
        row.querySelector('.s_gst_amount').value = gstAmt.toFixed(2);
        row.querySelector('.s_total').value      = total.toFixed(2);
    }

</script>
@endsection
