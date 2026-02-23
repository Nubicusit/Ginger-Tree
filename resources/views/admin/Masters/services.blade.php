@extends('layout.app')

@section('title', 'Services')

@section('content')
<div x-data="{
    openStockModal: false,
    formAction: '{{ route('services.store') }}',
    formMethod: 'POST',
    service_name: '',
    category_service: '',
    price: '',
    gst_percentage: '',
    service_tax: '',
}" class="bg-white rounded-lg shadow border border-gray-200">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Services</h2>

        <a href="#"
            @click.prevent="
                openStockModal = true;
                formAction = '{{ route('services.store') }}';
                formMethod = 'POST';
                service_name = '';
                category_service = '';
                price = '';
                gst_percentage = '';
                service_tax = '';
            "
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Add Service
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-right">Price</th>
                    <th class="px-6 py-3 text-right">GST</th>
                    <th class="px-6 py-3 text-right">Service TAX</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($stocks as $stock)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $stock->service_name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            {{ $stock->category_service ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-800">
                            ₹{{ number_format($stock->price, 2) }}
                        </td>

                        <td class="px-6 py-4 text-right font-medium text-gray-800">
                            {{ number_format($stock->gst_percentage, 2) }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            {{ number_format($stock->service_tax, 2) }}
                        </td>


                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <!-- Edit -->
                                <button
                                    @click.prevent="
                                        openStockModal = true;
                                        formAction = '{{ route('services.update', $stock->id) }}';
                                        formMethod = 'PUT';
                                        service_name = '{{ addslashes($stock->service_name) }}';
                                        category_service = '{{ addslashes($stock->category_service) }}';
                                        price = '{{ $stock->price }}';
                                        gst_percentage = '{{ $stock->gst_percentage }}';
                                        service_tax = '{{ $stock->service_tax }}';
                                    "
                                    class="p-2 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('services.destroy', $stock->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this Service?')">
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
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            No Services records found
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
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div
            @click.away="openStockModal = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800"
                    x-text="formMethod === 'PUT' ? 'Edit service' : 'Add Service'">
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
                    <label class="block text-sm font-medium text-gray-700">Service Name</label>
                    <input
                        type="text"
                        name="service_name"
                        x-model="service_name"
                        required
                        placeholder="Enter Service name"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input
                        type="text"
                        name="category_service"
                        x-model="category_service"
                        placeholder="Enter category"
                        class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400" />
                </div>

                <div class="grid grid-cols-2 gap-4">


                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
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
                    <label class="block text-sm font-medium text-gray-700">Service TAX</label>
                    <input
                        type="number"
                        name="service_tax"
                        x-model="service_tax"
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
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                        x-text="formMethod === 'PUT' ? 'Update Service' : 'Save Service'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
