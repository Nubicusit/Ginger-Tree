<!-- HR Sidebar Component -->
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
            <a href="{{ url('/hr/dashboard') }}"
               class="nav-item flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors
               {{ request()->is('hr/dashboard*')
                    ? 'bg-gray-300 text-gray-900 shadow-md'
                    : 'text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-th-large text-lg w-6 text-center"></i>
                <span>Dashboard</span>
            </a>

            <!-- Management Section -->
            <div class="pt-4 mt-4 border-t border-gray-100">
                <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">
                    Management
                </h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ url('/hr/employees') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('hr/employees*') ? 'bg-gray-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-users text-lg w-6 text-center"></i>
                            <span>Employees</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/hr/attendance') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('hr/attendance*') ? 'bg-gray-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-clock text-lg w-6 text-center"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/hr/leaves') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('hr/leaves*') ? 'bg-gray-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-calendar-alt text-lg w-6 text-center"></i>
                            <span>Leave Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/hr/payroll') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('hr/payroll*') ? 'bg-gray-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-wallet text-lg w-6 text-center"></i>
                            <span>Payroll</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/hr/announcements') }}"
                           class="nav-item flex items-center space-x-3 px-4 py-2.5 text-sm font-medium rounded-lg transition-colors
                           {{ request()->is('hr/announcements*') ? 'bg-gray-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-gray-200' }}">
                            <i class="fas fa-bullhorn text-lg w-6 text-center"></i>
                            <span>Announcements</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Settings Section -->
            <div class="pt-4 mt-4 border-t border-gray-100">
                <h3 class="text-gray-600 font-bold text-xs uppercase tracking-wider mb-2 px-4">
                    Settings
                </h3>
                <div class="space-y-2 px-4">

                    <!-- Clear Cache -->
                    <button onclick="clearCache(this)"
                        class="w-full text-center px-4 py-2 text-xs font-bold
                               text-gray-700 border border-gray-400 rounded-full
                               hover:bg-gray-200 uppercase transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-broom"></i>
                        <span id="clearCacheText">Clear Cache</span>
                    </button>

                    <!-- Logout -->
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

<!-- ═══ Animations ═══ -->
<style>
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(110px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes slideOutRight {
    from { opacity: 1; transform: translateX(0); }
    to   { opacity: 0; transform: translateX(110px); }
}
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
.toast-in  { animation: slideInRight 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
.toast-out { animation: slideOutRight 0.3s ease-in forwards; }
.spin-icon { animation: spin 0.8s linear infinite; }
</style>

<!-- ═══ Toast ═══ -->
<div id="cacheToast"
     style="display:none; position:fixed; bottom:24px; right:24px; z-index:9999;
            min-width:260px; background:#111827; color:white;
            border-radius:12px; overflow:hidden;
            box-shadow:0 12px 40px rgba(0,0,0,0.3);">
    <div style="display:flex; align-items:center; gap:10px; padding:14px 18px;">
        <i id="toastIcon" class="fas fa-check-circle" style="font-size:18px; color:#4ade80; flex-shrink:0"></i>
        <span id="cacheToastMsg" style="font-size:0.85rem; font-weight:600;">Cache cleared!</span>
    </div>
    <!-- Progress bar -->
    <div style="height:3px; background:rgba(255,255,255,0.1);">
        <div id="toastProgress" style="height:100%; background:#4ade80; width:100%;"></div>
    </div>
</div>

<!-- ═══ Script ═══ -->
<script>
function showToast(message, type = 'success') {
    const toast    = document.getElementById('cacheToast');
    const msg      = document.getElementById('cacheToastMsg');
    const icon     = document.getElementById('toastIcon');
    const progress = document.getElementById('toastProgress');

    if (type === 'success') {
        icon.className            = 'fas fa-check-circle';
        icon.style.color          = '#4ade80';
        progress.style.background = '#4ade80';
    } else {
        icon.className            = 'fas fa-circle-xmark';
        icon.style.color          = '#f87171';
        progress.style.background = '#f87171';
    }

    msg.textContent     = message;
    toast.style.display = 'block';
    toast.classList.remove('toast-out');
    toast.classList.add('toast-in');

    // Animate progress bar shrinking over 3s
    progress.style.transition = 'none';
    progress.style.width      = '100%';
    setTimeout(() => {
        progress.style.transition = 'width 3s linear';
        progress.style.width      = '0%';
    }, 50);

    // Auto hide
    setTimeout(() => {
        toast.classList.remove('toast-in');
        toast.classList.add('toast-out');
        setTimeout(() => {
            toast.style.display = 'none';
            toast.classList.remove('toast-out');
        }, 300);
    }, 3000);
}

function clearCache(btn) {
    const textEl  = document.getElementById('clearCacheText');
    const iconEl  = btn.querySelector('i');

    // Loading state
    textEl.textContent = 'Clearing...';
    btn.disabled       = true;
    iconEl.className   = 'fas fa-circle-notch spin-icon';

    fetch('{{ route('hr.clear-cache') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept':       'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message ?? 'Cache cleared successfully!', 'success');
    })
    .catch(() => {
        showToast('Failed to clear cache!', 'error');
    })
    .finally(() => {
        textEl.textContent = 'Clear Cache';
        btn.disabled       = false;
        iconEl.className   = 'fas fa-broom';
    });
}
</script>