<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Accounts Dashboard')</title>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            color: #6b7280;
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
    <div id="sidebarOverlay"
         class="sidebar-overlay fixed inset-0 bg-black/50 z-40 md:hidden">
    </div>

    <!-- Accounts Sidebar -->
    @include('accounts.partials.sidebar')

    <!-- Main Content Wrapper -->
    <div class="md:ml-64 flex flex-col min-h-screen transition-all duration-300">

        <!-- Accounts Navbar -->
        @include('accounts.partials.navbar')

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-200 overflow-y-auto custom-scrollbar">
            @yield('content')
        </main>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('show');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', toggleSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }
    </script>

    @stack('scripts')
</body>
</html>