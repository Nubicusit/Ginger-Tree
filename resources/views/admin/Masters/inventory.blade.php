@extends('layouts.app')

@section('title', 'Inventory Stocks')

@section('content')
<div class="bg-white rounded-lg shadow border border-gray-200">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Inventory Stocks</h2>

        <a href="#"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>
            Add Stock
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">Item Name</th>
                    <th class="px-6 py-3 text-left">Category</th>
                    <th class="px-6 py-3 text-center">Unit</th>
                    <th class="px-6 py-3 text-right">Quantity</th>
                    <th class="px-6 py-3 text-right">Reorder Level</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                <!-- @forelse($stocks as $stock) -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">
                            <!-- {{ $stock->item_name }} -->
                        </td>

                        <td class="px-6 py-3">
                            <!-- {{ $stock->category }} -->
                        </td>

                        <td class="px-6 py-3 text-center">
                            <!-- {{ $stock->unit }} -->
                        </td>

                        <td class="px-6 py-3 text-right">
                            <!-- {{ $stock->quantity }} -->
                        </td>

                        <td class="px-6 py-3 text-right">
                            <!-- {{ $stock->reorder_level }} -->
                        </td>

                        <td class="px-6 py-3 text-center">
                            <!-- @if($stock->quantity <= $stock->reorder_level) -->
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                    Low Stock
                                </span>
                            <!-- @else -->
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    In Stock
                                </span>
                            <!-- @endif -->
                        </td>

                        <td class="px-6 py-3 text-center space-x-2">
                            <a href="#"
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="#"
                               class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <!-- @empty -->
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                            No inventory records found
                        </td>
                    </tr>
                <!-- @endforelse -->
            </tbody>
        </table>
    </div>

</div>
@endsection
