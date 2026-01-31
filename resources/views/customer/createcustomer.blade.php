<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add / Update Customer Modal</title>
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

  <!-- Backdrop -->
  <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" id="backdrop"></div>

  <!-- Modal -->
  <div class="relative w-full max-w-md bg-white rounded-lg shadow-2xl z-10 flex flex-col max-h-[90vh]">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-blue-600 rounded-t-lg shrink-0">
      <h2 class="text-white font-semibold text-lg tracking-wide">
        Add/Update Customer
      </h2>
      <button onclick="closeModal()" class="text-white/80 hover:text-white text-xl leading-none transition-colors focus:outline-none" aria-label="Close modal">
        &times;
      </button>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex border-b text-sm bg-gray-50 shrink-0 overflow-x-auto">
      <button
        onclick="switchTab(event, 'customer-info')"
        class="tab-btn px-6 py-3 border-b-2 border-blue-600 text-blue-600 font-medium hover:bg-gray-100 transition-colors whitespace-nowrap"
        data-target="customer-info">
        Customer Info
      </button>
      <button
        onclick="switchTab(event, 'company-profile')"
        class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors whitespace-nowrap"
        data-target="company-profile">
        Company Profile
      </button>
      <button
        onclick="switchTab(event, 'payment-data')"
        class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors whitespace-nowrap"
        data-target="payment-data">
        Payment Data
      </button>
    </div>

    <!-- Scrollable Content Area -->
    <div class="p-6 overflow-y-auto custom-scrollbar flex-grow">
      <form id="customerForm">

        <!-- TAB 1: Customer Information -->
        <div id="customer-info" class="tab-content space-y-4 fade-in">
          <!-- Name -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="fullName" placeholder="e.g. John Doe" required
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-shadow"
            />
          </div>

          <!-- Customer Type -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Customer Type</label>
            <select name="customerType" class="w-full rounded border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <option>Retail</option>
              <option>Wholesale</option>
              <option>Distributor</option>
            </select>
          </div>

          <!-- Phone -->
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="text-gray-700 font-medium text-sm">Phone Number</label>
              <span class="text-xs text-gray-400">(Optional)</span>
            </div>
            <input type="tel" name="phone" value="+1 321-555-0198"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" placeholder="customer@example.com" required
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            />
          </div>

          <!-- Address -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Street Address</label>
            <input type="text" name="address" placeholder="123 Main St"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            />
          </div>
        </div>

        <!-- TAB 2: Company Profile -->
        <div id="company-profile" class="tab-content space-y-4 hidden fade-in">
          <!-- Company Name -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Company Name</label>
            <input type="text" name="companyName" placeholder="e.g. Acme Corp"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            />
          </div>

          <!-- Industry -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Industry</label>
            <select name="industry" class="w-full rounded border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <option value="">Select Industry</option>
              <option>Technology</option>
              <option>Healthcare</option>
              <option>Finance</option>
              <option>Retail</option>
              <option>Manufacturing</option>
            </select>
          </div>

          <!-- Website -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Website</label>
            <div class="flex items-center">
              <span class="bg-gray-100 border border-r-0 border-gray-300 rounded-l px-3 py-2 text-gray-500 text-sm">https://</span>
              <input type="text" name="website" placeholder="www.example.com"
                class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-l-none"
              />
            </div>
          </div>

          <!-- Tax ID -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Tax ID / VAT</label>
            <input type="text" name="taxId" placeholder="e.g. 12-3456789"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            />
          </div>

          <!-- Notes -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Internal Notes</label>
            <textarea name="notes" rows="3" placeholder="Additional details about this customer..."
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
          </div>
        </div>

        <!-- TAB 3: Payment Data -->
        <div id="payment-data" class="tab-content space-y-4 hidden fade-in">
          <!-- Payment Terms -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Payment Terms</label>
            <select name="paymentTerms" class="w-full rounded border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <option>Net 15</option>
              <option>Net 30</option>
              <option selected>Net 60</option>
              <option>Due on Receipt</option>
              <option>Credit Card (Auto)</option>
            </select>
          </div>

          <!-- Currency -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Currency</label>
            <select name="currency" class="w-full rounded border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
              <option value="USD">USD - US Dollar</option>
              <option value="EUR">EUR - Euro</option>
              <option value="GBP">GBP - British Pound</option>
              <option value="CAD">CAD - Canadian Dollar</option>
            </select>
          </div>

          <!-- Credit Limit -->
          <div>
            <label class="block text-gray-700 font-medium mb-1 text-sm">Credit Limit</label>
            <div class="relative">
              <span class="absolute left-3 top-2 text-gray-500 text-sm">$</span>
              <input type="number" name="creditLimit" placeholder="0.00"
                class="w-full rounded border border-gray-300 pl-7 pr-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
              />
            </div>
          </div>

          <!-- Billing Address Toggle -->
          <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" id="sameAddress" name="sameAddress" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500" checked>
            <label for="sameAddress" class="text-sm text-gray-600">Billing address same as shipping</label>
          </div>

          <!-- Billing Address (conditionally shown in real app, kept visible for demo) -->
          <div id="billing-address-fields" class="opacity-50 transition-opacity">
            <label class="block text-gray-700 font-medium mb-1 text-sm text-xs uppercase tracking-wider mt-2">Billing Address</label>
            <input type="text" placeholder="Street Address" disabled
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none bg-gray-50 cursor-not-allowed"
            />
          </div>
        </div>

      </form>
    </div>

    <!-- Footer -->
    <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-lg shrink-0">
      <button onclick="closeModal()" class="px-5 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200 transition-all">
        Cancel
      </button>
      <button onclick="saveCustomer()" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-all flex items-center gap-2">
        <span>Save</span>

      </button>
    </div>
  </div>



  <script>
    function switchTab(event, tabId) {
      // 1. Remove active state from all buttons
      const tabButtons = document.querySelectorAll('.tab-btn');
      tabButtons.forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
      });

      // 2. Add active state to the clicked button
      const clickedBtn = event.currentTarget;
      clickedBtn.classList.remove('border-transparent', 'text-gray-500');
      clickedBtn.classList.add('border-blue-600', 'text-blue-600');

      // 3. Hide all tab contents
      const tabContents = document.querySelectorAll('.tab-content');
      tabContents.forEach(content => {
        content.classList.add('hidden');
      });

      // 4. Show the specific tab content
      const targetContent = document.getElementById(tabId);
      if (targetContent) {
        targetContent.classList.remove('hidden');

        targetContent.classList.remove('fade-in');
        void targetContent.offsetWidth;
        targetContent.classList.add('fade-in');
      }
    }

    function closeModal() {
      const form = document.getElementById('customerForm');
      form.reset();
      const firstTabBtn = document.querySelector('.tab-btn');
      firstTabBtn.click();
    }

    function saveCustomer() {
      const form = document.getElementById('customerForm');

      if(!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      console.log("Saving Data:", data);

    }

    document.getElementById('sameAddress').addEventListener('change', function(e) {
      const billingFields = document.getElementById('billing-address-fields');
      const input = billingFields.querySelector('input');

      if(e.target.checked) {
        billingFields.classList.add('opacity-50');
        input.disabled = true;
        input.classList.add('cursor-not-allowed', 'bg-gray-50');
        input.classList.remove('bg-white');
      } else {
        billingFields.classList.remove('opacity-50');
        input.disabled = false;
        input.classList.remove('cursor-not-allowed', 'bg-gray-50');
        input.classList.add('bg-white');
      }
    });
  </script>
</body>
</html>
