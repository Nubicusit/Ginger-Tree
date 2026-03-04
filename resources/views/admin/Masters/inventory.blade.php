@extends('layout.app')

@section('title', 'Inventory Stocks')

@section('content')
<div x-data="{
    openStockModal: false,
    formAction: '{{ route('inventory.store') }}',
    formMethod: 'POST',
    item_name: '',
    category: '',
    unit: '',
    price: '',
    gst_percentage: '',
    quantity: ''
}" class="bg-white rounded-lg shadow border border-gray-200">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 ">
        <h2 class="text-lg font-semibold text-gray-800">Inventory Stocks</h2>

        <a href="#"
            @click.prevent="
                openStockModal = true;
                formAction = '{{ route('inventory.store') }}';
                formMethod = 'POST';
                item_name = '';
                category = '';
                unit = '';
                price = '';
                gst_percentage = '';
                quantity = '';
            "
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-800 rounded-lg hover:bg-red-900">
            <i class="fas fa-plus mr-2"></i>
            Add Stock
        </a>
    </div>
<div class="px-6 py-4  bg-gray-50">
    <form method="GET" action="{{ route('inventory.index') }}"
          class="flex items-center justify-end flex-wrap gap-4">

        <div class="flex-1 max-w-sm">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search by item name..."
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm
                          focus:ring-2 focus:ring-red-200 focus:outline-none">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-red-800 rounded-lg hover:bg-red-900 transition">
                Search
            </button>

            <a href="{{ route('inventory.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Reset
            </a>
        </div>

    </form>
</div>
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Item Name</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-center">Unit</th>
                    <th class="px-6 py-3 text-right">Unit Price</th>
                    <th class="px-6 py-3 text-right">GST</th>
                    <th class="px-6 py-3 text-right">Quantity</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($stocks as $stock)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $stock->item_name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $stock->category ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-center text-gray-700">
                            {{ $stock->unit ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-800">
                            ₹{{ number_format($stock->price, 2) }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-800">
                            {{ number_format($stock->gst_percentage, 2) }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            {{ $stock->quantity }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                {{ $stock->quantity > 0
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700' }}">
                                {{ $stock->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Edit -->
                                <button
                                    @click.prevent="
                                        openStockModal = true;
                                        formAction = '{{ route('inventory.update', $stock->id) }}';
                                        formMethod = 'PUT';
                                        item_name = '{{ addslashes($stock->item_name) }}';
                                        category = '{{ addslashes($stock->category) }}';
                                        unit = '{{ addslashes($stock->unit) }}';
                                        price = '{{ $stock->price }}';
                                        gst_percentage = '{{ $stock->gst_percentage }}';
                                        quantity = '{{ $stock->quantity }}';
                                    "
                                    class="p-2 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('inventory.destroy', $stock->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this stock?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            No inventory records found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Add / Edit Stock Modal -->
    <div
        x-show="openStockModal"
        x-transition x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div
            @click.away="openStockModal = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800"
                    x-text="formMethod === 'PUT' ? 'Edit Inventory Stock' : 'Add Inventory Stock'">
                </h3>
                <button @click="openStockModal = false" class="text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </div>

            <!-- Modal Form -->
            <form :action="formAction" method="POST" class="space-y-4">
                @csrf

                <!-- Spoof PUT for edit -->
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Item Name</label>
                    <input
                        type="text"
                        name="item_name"
                        x-model="item_name"
                        required
                        placeholder="Enter item name"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input
                        type="text"
                        name="category"
                        x-model="category"
                        placeholder="Enter category"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit</label>
                        <input
                            type="text"
                            name="unit"
                            x-model="unit"
                            placeholder="e.g. kg, pcs"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <input
                            type="number"
                            step="0.01"
                            name="price"
                            x-model="price"
                            placeholder="0.00"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">GST</label>
                        <input
                            type="number"
                            step="0.01"
                            name="gst_percentage"
                            x-model="gst_percentage"
                            placeholder="0.00"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input
                        type="number"
                        name="quantity"
                        x-model="quantity"
                        placeholder="0"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        @click="openStockModal = false"
                        class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm text-white bg-red-700 rounded-lg hover:bg-red-800"
                        x-text="formMethod === 'PUT' ? 'Update Stock' : 'Save Stock'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
