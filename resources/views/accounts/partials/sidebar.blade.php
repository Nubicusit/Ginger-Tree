<!-- Accounts Sidebar Component -->
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
            <a href="{{ url('/accounts/dashboard') }}"
               class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors
               {{ request()->is('accounts/dashboard*')
                    ? 'bg-gray-300 text-gray-900 shadow-md'
                    : 'text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-th-large text-lg w-6 text-center"></i>
                <span>Dashboard</span>
            </a>

            <!-- ================= MANAGEMENT ================= -->
            <div class="pt-4 mt-4 border-t border-gray-100">
                <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">
                    Management
                </h3>

                <ul class="space-y-1">

                    <!-- Estimation -->
                    <li>
                        <a href="{{ url('/accounts/estimations') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/estimations*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-calculator text-lg w-6 text-center"></i>
                            <span>Estimation</span>
                        </a>
                    </li>

                    <!-- Income & Expenses -->
                    <li>
                        <a href="{{ url('/accounts/income-expenses') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/income-expenses*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-exchange-alt text-lg w-6 text-center"></i>
                            <span>Income & Expenses</span>
                        </a>
                    </li>

                    <!-- Invoices -->
                    <li>
                        <a href="{{ url('/accounts/invoices') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/invoices*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-file-invoice text-lg w-6 text-center"></i>
                            <span>Invoices</span>
                        </a>
                    </li>

                    <!-- Project Payments -->
                    <li>
                        <a href="{{ url('/accounts/projects') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/projects*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-building text-lg w-6 text-center"></i>
                            <span>Project Payments</span>
                        </a>
                    </li>

                    <!-- Accounts Receivable -->
                    <li>
                        <a href="{{ url('/accounts/receivables') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/receivables*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-hand-holding-usd text-lg w-6 text-center"></i>
                            <span>Accounts Receivable</span>
                        </a>
                    </li>

                    <!-- Accounts Payable -->
                    <li>
                        <a href="{{ url('/accounts/payables') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/payables*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-file-invoice-dollar text-lg w-6 text-center"></i>
                            <span>Accounts Payable</span>
                        </a>
                    </li>

                    <!-- Vendors -->
                    <li>
                        <a href="{{ url('/accounts/vendors') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/vendors*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-truck text-lg w-6 text-center"></i>
                            <span>Vendors</span>
                        </a>
                    </li>

                    <!-- Payroll -->
                    <li>
                        <a href="{{ url('/accounts/payroll') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/payroll*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-money-check-alt text-lg w-6 text-center"></i>
                            <span>Payroll</span>
                        </a>
                    </li>

                </ul>
            </div>

            <!-- ================= FINANCE ================= -->
            <div class="pt-4 mt-4 border-t border-gray-100">
                <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">
                    Finance
                </h3>

                <ul class="space-y-1">

                    <!-- Bank & Cash -->
                    <li>
                        <a href="{{ url('/accounts/bank') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/bank*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-university text-lg w-6 text-center"></i>
                            <span>Bank & Cash</span>
                        </a>
                    </li>

                    <!-- Ledger -->
                    <li>
                        <a href="{{ url('/accounts/ledger') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/ledger*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-book text-lg w-6 text-center"></i>
                            <span>General Ledger</span>
                        </a>
                    </li>

                    <!-- Tax -->
                    <li>
                        <a href="{{ url('/accounts/tax') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/tax*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-receipt text-lg w-6 text-center"></i>
                            <span>Tax & Compliance</span>
                        </a>
                    </li>

                </ul>
            </div>

            <!-- ================= REPORTS ================= -->
            <div class="pt-4 mt-4 border-t border-gray-100">
                <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">
                    Reports
                </h3>

                <ul class="space-y-1">

                    <!-- Profit & Loss -->
                    <li>
                        <a href="{{ url('/accounts/profit-loss') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/profit-loss*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-chart-line text-lg w-6 text-center"></i>
                            <span>Profit & Loss</span>
                        </a>
                    </li>

                    <!-- Cash Flow -->
                    <li>
                        <a href="{{ url('/accounts/cash-flow') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('accounts/cash-flow*')
                                ? 'bg-gray-300 text-gray-900 shadow-md'
                                : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-chart-area text-lg w-6 text-center"></i>
                            <span>Cash Flow</span>
                        </a>
                    </li>

                </ul>
            </div>

            <!-- ================= SETTINGS ================= -->
            <div class="pt-4 mt-4 border-t border-gray-100">
                <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">
                    Settings
                </h3>

                <div class="space-y-2 px-4">

                    <button onclick="clearCache()"
                        class="w-full text-center px-4 py-2 text-xs font-bold
                               text-gray-700 border border-gray-400 rounded-full
                               hover:bg-gray-200 uppercase transition-colors">
                        Clear Cache
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-center px-4 py-2 text-xs font-bold
                                   text-red-600 border border-red-600 rounded-full
                                   hover:bg-red-50 uppercase transition-colors">
                            Logout
                        </button>
                    </form>

                </div>
            </div>

        </nav>
    </div>
</aside>
