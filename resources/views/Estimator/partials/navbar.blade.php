<!-- Designer Navbar -->
<header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 sm:px-6 shrink-0">

    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Page Title -->
    <div class="flex items-center space-x-2">
        <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'Estimator Portal')</h1>
    </div>

    <!-- Right Side -->
    <div class="flex items-center space-x-4">
        <!-- User Info -->
        <div class="flex items-center space-x-2">
            <div class="w-9 h-9 rounded-full bg-gray-800 flex items-center justify-center">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="hidden sm:block">
                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">Estimator</p>
            </div>
        </div>
    </div>
</header>

