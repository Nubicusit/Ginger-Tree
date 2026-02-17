<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Steels & SheetMart')</title>

  <!-- Tailwind CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Custom Styles for Sidebar Transitions & Scrollbar -->
  <style>
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    .sidebar-transition {
      transition: transform 0.3s ease-in-out;
    }
    /* Overlay transition */
    .sidebar-overlay {
      transition: opacity 0.3s ease-in-out;
      opacity: 0;
      pointer-events: none;
    }
    .sidebar-overlay.show {
      opacity: 1;
      pointer-events: auto;
    }
.label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280; /* gray-500 */
    margin-bottom: 4px;
}

.input {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background-color: #fff;
}

.input:read-only {
    background-color: #f9fafb;
}
</style>

</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 md:hidden"></div>

    <!-- Sidebar Include -->
    @include('partials.sidebar')

    <!-- Main Content Wrapper -->
    <!-- Added md:ml-64 to push content right on desktop so it doesn't sit under the fixed sidebar -->
    <div class="md:ml-64 flex flex-col min-h-screen transition-all duration-300">

        <!-- Navbar Include -->
        @include('partials.navbar')

        <!-- Dynamic Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-200 overflow-y-auto">
            @yield('content')
        </main>
    </div>
    <!-- Optional Global Scripts -->
     <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // Toggle Sidebar Logic
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('show');
        }

        if(mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
        }
        if(sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
    </script>

    @stack('scripts')
</body>
</html>
