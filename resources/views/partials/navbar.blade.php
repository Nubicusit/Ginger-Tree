<header class="bg-white shadow-sm sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 md:py-4">
      
      <!-- Left: Toggle & Title -->
      <div class="flex items-center space-x-2 sm:space-x-4 flex-1">
        <!-- Mobile Menu Toggle Button -->
        <button id="mobileMenuBtn" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition-colors">
          <i class="fas fa-bars text-xl"></i>
        </button>
        
        <h2 class="text-lg font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
        
        <!-- Search Bar -->
        <div class="relative hidden sm:block flex-1 max-w-xs lg:max-w-md ml-4">
          <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-gray-50 focus:bg-white transition-colors">
          <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
        </div>
      </div>

      <!-- Right: Notifications & Profile -->
      <div class="flex items-center space-x-3 sm:space-x-6">
        <button class="relative text-gray-500 hover:text-blue-600 transition-colors focus:outline-none p-2">
          <i class="far fa-bell text-xl"></i>
          <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
        </button>
        
        <div class="flex items-center space-x-3 cursor-pointer group">
          <img src="https://i.pravatar.cc/100?img=5" alt="Admin" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-sm group-hover:border-blue-200 transition-colors">
          <div class="hidden sm:block text-right">
            <span class="block font-semibold text-gray-700 text-sm">Administrator</span>
            <span class="text-xs text-gray-500 block">Admin</span>
          </div>
          <i class="fas fa-chevron-down text-xs text-gray-400 group-hover:text-gray-600 hidden sm:block"></i>
        </div>
      </div>
    </div>
</header>