
<!-- Sidebar Component -->
<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl
              transform -translate-x-full md:translate-x-0
              flex flex-col h-screen custom-scrollbar sidebar-transition">

  <!-- Sidebar Header / Logo -->
  <div class="h-20 flex items-center justify-center border-b border-gray-100 shrink-0 bg-white">
    <img src="{{ asset('img/gingertree-white-logo.png') }}"
         alt="Ginger Tree Logo"
         class="h-20 object-contain">
  </div>

  <!-- Scrollable Navigation Area -->
  <div class="flex-1 overflow-y-auto py-4 px-3 bg-white">
    <nav class="space-y-1">

      <!-- Dashboard -->
      <a href="{{ url('/sales/dashboard') }}"
         class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors
         {{ request()->is('dashboard*')
            ? 'bg-gray-300 text-gray-900 shadow-md'
            : 'text-gray-700 hover:bg-gray-200' }}">
        <i class="fas fa-th-large text-lg w-6 text-center"></i>
        <span>Dashboard</span>
      </a>

      <!-- Management Section -->
      <div class="pt-4 mt-4 border-t border-gray-100">
        <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">Management</h3>
        <ul class="space-y-1">

          <!-- Customers -->
          <!-- <li>
            <a href="{{ url('customers') }}"
               class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
               {{ request()->is('customers*')
                  ? 'bg-gray-300 text-gray-900 shadow-md'
                  : 'text-gray-700 hover:bg-gray-200' }}">
              <i class="fas fa-users text-lg w-6 text-center"></i>
              <span>Customer Management</span>
            </a>
          </li> -->

          <!-- Leads -->
          <li>
            <a href="{{ url('/sales/leads') }}"
               class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
               {{ request()->is('leads*')
                  ? 'bg-gray-300 text-gray-900 shadow-md'
                  : 'text-gray-700 hover:bg-gray-200' }}">
              <i class="fas fa-file-alt text-lg w-6 text-center"></i>
              <span>Leads and Inquiries</span>
            </a>
          </li>
            <!-- sitevisit -->
          <li>
            <a href="{{ url('/sales/sitevisits') }}"
               class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
               {{ request()->is('leads*')
                  ? 'bg-gray-300 text-gray-900 shadow-md'
                  : 'text-gray-700 hover:bg-gray-200' }}">
              <i class="fas fa-file-alt text-lg w-6 text-center"></i>
              <span>Sitevisit and Requirements</span>
            </a>
          </li>

          <!-- Design Status -->
          <!-- <li>
            <a href="{{ url('design-status') }}"
               class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
               {{ request()->is('design-status*')
                  ? 'bg-gray-300 text-gray-900 shadow-md'
                  : 'text-gray-700 hover:bg-gray-200' }}">
              <i class="fas fa-paint-brush text-lg w-6 text-center"></i>
              <span>Design Status</span>
            </a>
          </li> -->

          <!-- Production -->
          <!-- <li>
            <a href="{{ url('production') }}"
               class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
               {{ request()->is('production*')
                  ? 'bg-gray-300 text-gray-900 shadow-md'
                  : 'text-gray-700 hover:bg-gray-200' }}">
              <i class="fas fa-cogs text-lg w-6 text-center"></i>
              <span>Production & Delivery</span>
            </a>
          </li> -->

          <!-- HR -->
          <!-- <li>
            <a href="{{ url('hr') }}"
               class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
               {{ request()->is('hr*')
                  ? 'bg-gray-300 text-gray-900 shadow-md'
                  : 'text-gray-700 hover:bg-gray-200' }}">
              <i class="fas fa-id-badge text-lg w-6 text-center"></i>
              <span>HR & Payroll</span>
            </a>
          </li> -->
        </ul>
      </div>
      <!-- Settings -->
      <div class="pt-4 mt-4 border-t border-gray-100">
        <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">Settings</h3>
        <div class="space-y-2 px-4">

          <button class="w-full text-center px-4 py-2 text-xs font-bold text-gray-700 border border-gray-400 rounded-full hover:bg-gray-200 uppercase transition-colors">
            Clear Cache
          </button>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
              class="w-full text-center px-4 py-2 text-xs font-bold text-red-600 border border-red-600 rounded-full hover:bg-red-50 uppercase transition-colors">
              Logout
            </button>
          </form>
        </div>
      </div>
    </nav>
  </div>
</aside>
