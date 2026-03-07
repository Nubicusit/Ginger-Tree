@extends('sales_executive.layout.app')

@section('title', 'Projects')

@section('content')

<div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">

    <div class="flex justify-between items-center mb-6">
        <span class="bg-blue-100 text-blue-600 text-sm px-3 py-1 rounded-full">
            Total: {{ $projects->count() }}
        </span>
    </div>

    @if($projects->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-blue-50 to-blue-100 text-gray-700 uppercase text-xs tracking-wider">
                        <th class="p-3">Project Code</th>
                        <th class="p-3">Client</th>
                        <th class="p-3">Phone</th>
                        <th class="p-3">Location</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Budget</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">View</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($projects as $project)
                        <tr class="hover:bg-gray-50 transition duration-200">

                            <td class="p-3 font-semibold text-blue-600">
                                {{ $project->project_code }}
                            </td>

                            <td class="p-3">
                                <div class="font-medium text-gray-800">
                                    {{ $project->lead->client_name }}
                                </div>
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $project->lead->phone }}
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $project->lead->location }}
                            </td>

                            <td class="p-3">
                                <span class="bg-purple-100 text-purple-600 text-xs px-2 py-1 rounded-full">
                                    {{ $project->lead->project_type }}
                                </span>
                            </td>

                            <td class="p-3 font-medium text-gray-700">
                                {{ $project->lead->budget_range }}
                            </td>

                            <td class="p-3 text-center">
                                <span class="bg-green-100 text-green-600 text-xs px-3 py-1 rounded-full font-medium">
                                    {{ $project->status }}
                                </span>
                            </td>
                            <td class="p-3 text-center">

    <button
        onclick="openProjectModal({{ $project->id }})"
        class="bg-blue-100 text-blue-600 px-3 py-1 text-xs rounded-full hover:bg-blue-200">
        View
    </button>

</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg">No projects available yet.</p>
        </div>
    @endif

</div>
<!-- Project View Modal -->

<div id="projectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">

    <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
            <h2 class="text-lg font-semibold tracking-wide">Project Details</h2>
            <button onclick="closeProjectModal()" class="text-white text-2xl hover:scale-110 transition">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">

            <!-- Dates Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Expected Start Date
                    </label>
                    <input type="date" id="modalStartDate"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Expected End Date
                    </label>
                    <input type="date" id="modalEndDate"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

            </div>

            <!-- Materials Section -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-md font-semibold text-gray-700">Materials</h3>
                    <button onclick="addMaterialRow()"
                        class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded-xl shadow-md transition">
                        + Add Material
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-2xl">

    <table class="min-w-full text-sm text-left">

        <!-- Header -->
        <thead>
            <tr class="bg-slate-100 text-gray-600 uppercase text-xs tracking-wider">
                <th class="px-4 py-3 font-semibold">name</th>
                <th class="px-4 py-3 font-semibold text-center">Qty</th>
                <th class="px-4 py-3 font-semibold text-center">Price</th>
                <th class="px-4 py-3 font-semibold text-center">Total</th>
                <th class="px-4 py-3 text-center font-semibold">Action</th>
            </tr>
        </thead>

        <!-- Body -->
        <tbody id="materialsTable" class="divide-y divide-gray-100 bg-white">
        </tbody>

        <!-- Footer Total -->
        <tfoot>
            <tr class="bg-gray-50">
                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700">
                    Grand Total :
                </td>
                <td class="px-4 py-3 text-center font-bold text-blue-600" id="grandTotal">
                    ₹ 0
                </td>
                <td></td>
            </tr>
        </tfoot>

    </table>
</div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <button onclick="closeProjectModal()"
                    class="px-5 py-2 rounded-xl  border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                    Cancel
                </button>

                <button onclick="saveProject()"
                    class="px-6 py-2 rounded-xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>

        </div>
    </div>
</div>
<script>
// function openProjectModal(projectId) {

//     fetch(`/sales/projects/${projectId}`)
//         .then(response => response.json())
//         .then(data => {

//             document.getElementById('modalStartDate').innerText = data.expected_start_date ?? 'N/A';
//             document.getElementById('modalEndDate').innerText = data.expected_end_date ?? 'N/A';

//             let materialsHtml = '';

//             if (data.materials && data.materials.length > 0) {
//                 data.materials.forEach(material => {
//                     materialsHtml += `
//                         <tr>
//                             <td class="p-2 border">${material.description}</td>
//                             <td class="p-2 border">${material.quantity}</td>
//                             <td class="p-2 border">${material.price}</td>
//                         </tr>
//                     `;
//                 });
//             } else {
//                 materialsHtml = `
//                     <tr>
//                         <td colspan="3" class="p-2 text-center text-gray-500">
//                             No materials added
//                         </td>
//                     </tr>
//                 `;
//             }

//             document.getElementById('materialsTable').innerHTML = materialsHtml;

//             document.getElementById('projectModal').classList.remove('hidden');
//             document.getElementById('projectModal').classList.add('flex');
//         });
// }

function closeProjectModal() {
    document.getElementById('projectModal').classList.add('hidden');
}
</script>
<script>
let currentProjectId = null;

function openProjectModal(projectId) {

    currentProjectId = projectId;

  fetch(`/sales/projects/${projectId}`)
        .then(res => res.json())
        .then(data => {

            document.getElementById('modalStartDate').value = data.expected_start_date ?? '';
            document.getElementById('modalEndDate').value = data.expected_end_date ?? '';

        // Clear old rows first
document.getElementById('materialsTable').innerHTML = '';

// Add materials using professional row function
if (data.materials && data.materials.length > 0) {
    data.materials.forEach(material => {
        addMaterialRow(
            material.description,
            material.quantity,
            material.price
        );
    });
}

// Recalculate total after loading
calculateGrandTotal();


            document.getElementById('projectModal').classList.remove('hidden');
            document.getElementById('projectModal').classList.add('flex');
        });
}
function addMaterialRow(description = '', quantity = '', price = '') {

    let row = `
        <tr class="hover:bg-gray-50 transition">

            <td class="px-4 py-3">

    <div class="space-y-2">

        <!-- Styled Select -->
        <div class="relative">
            <select onchange="handleItemSelect(this)"
    class="itemSelect w-full">
    <option value="">Search & Select Item</option>
</select>

            <!-- Custom Arrow -->
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                ▼
            </div>
        </div>

        <!-- Manual Input -->
        <input type="text" value="${description}"
            placeholder="Or enter custom material name"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm shadow-sm
                   focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition description">
    </div>

</td>

            <td class="px-4 py-2 text-center">
                <input type="number" value="${quantity}"
                    oninput="calculateRow(this)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 quantity text-center focus:ring-2 focus:ring-blue-400 outline-none">
            </td>

            <td class="px-4 py-2 text-center">
                <input type="number" value="${price}"
                    oninput="calculateRow(this)"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 price text-center focus:ring-2 focus:ring-blue-400 outline-none">
            </td>

            <td class="px-4 py-2 text-center font-semibold text-gray-700 rowTotal">
                ₹ 0
            </td>

            <td class="px-4 py-2 text-center">
                <button onclick="removeRow(this)"
                    class="bg-red-100 text-red-600 px-3 py-1 rounded-lg text-xs hover:bg-red-200 transition">
                    Remove
                </button>
            </td>
        </tr>
    `;

    document.getElementById('materialsTable').insertAdjacentHTML('beforeend', row);

    loadInventoryItems(); // Load dropdown items
        let lastRow = document.querySelector('#materialsTable tr:last-child');
    let priceInput = lastRow.querySelector('.price');
    calculateRow(priceInput);
}
function loadInventoryItems() {

    fetch('/inventory-items')
        .then(res => res.json())
        .then(items => {

            document.querySelectorAll('.itemSelect').forEach(select => {

                // Prevent duplicate loading
                if (select.options.length > 1) return;

                items.forEach(item => {
                    let option = document.createElement('option');
                    option.value = item.item_name;
                    option.textContent = item.item_name;
                    option.dataset.price = item.price; // store price
                    select.appendChild(option);
                });

            });

        });
}
function handleItemSelect(select) {

    let selectedOption = select.options[select.selectedIndex];

    let row = select.closest('tr');

    let descriptionInput = row.querySelector('.description');
    let priceInput = row.querySelector('.price');

    if (select.value !== '') {
        descriptionInput.value = select.value;
        priceInput.value = selectedOption.dataset.price || 0;
    }

    calculateRow(priceInput);
}
function saveProject() {

    let materials = [];

    document.querySelectorAll('#materialsTable tr').forEach(row => {

        materials.push({
            description: row.querySelector('.description').value,
            quantity: row.querySelector('.quantity').value,
            price: row.querySelector('.price').value
        });

    });

    fetch(`/sales/projects/${currentProjectId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            expected_start_date: document.getElementById('modalStartDate').value,
            expected_end_date: document.getElementById('modalEndDate').value,
            materials: materials
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        closeProjectModal();
        location.reload();
    });
}
function calculateRow(element) {

    let row = element.closest('tr');

    let qty = parseFloat(row.querySelector('.quantity').value) || 0;
    let price = parseFloat(row.querySelector('.price').value) || 0;

    let total = qty * price;

    row.querySelector('.rowTotal').innerText = "₹ " + total.toFixed(2);

    calculateGrandTotal();
}

function calculateGrandTotal() {

    let grandTotal = 0;

    document.querySelectorAll('.rowTotal').forEach(cell => {
        grandTotal += parseFloat(cell.innerText.replace('₹', '')) || 0;
    });

    document.getElementById('grandTotal').innerText = "₹ " + grandTotal.toFixed(2);
}

function removeRow(button) {
    button.closest('tr').remove();
    calculateGrandTotal();
}
</script>
@endsection
