<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar-overlay" class="fixed inset-0 bg-black/30 hidden opacity-0 transition-opacity duration-300 z-40"></div>

<aside id="sidebar" class="fixed top-0 left-0 h-full w-72 bg-white border-r border-gray-100 shadow-sm z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col">

    <div class="h-16 flex items-center px-4 border-b border-gray-100 shrink-0">
        <a href="dashboard.php" class="flex items-center gap-3 w-full rounded-xl px-2 py-2 hover:bg-gray-100 active:bg-gray-200 transition group">
            <img src="../assets/images/logo.png" onerror="this.src='https://via.placeholder.com/40?text=L'" alt="Logo" class="w-10 h-10">
            <div class="leading-tight">
                <div class="font-bold text-gray-800 group-hover:text-brand-primary transition-colors">Microfinance LOG2</div>
                <div class="text-[11px] text-gray-500 font-semibold uppercase group-hover:text-brand-primary transition-colors">LOGISTIC II</div>
            </div>
        </a>
    </div>

    <div class="px-4 py-4 overflow-y-auto flex-1 custom-scrollbar">
        <div class="text-xs font-bold text-gray-400 tracking-wider px-2">MAIN MENU</div>

        <a href="dashboard.php" class="mt-3 flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99] font-semibold <?php echo $current_page == 'dashboard.php' ? 'bg-brand-primary text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-brand-primary hover:translate-x-1'; ?>">
            <span class="inline-flex w-9 h-9 rounded-lg items-center justify-center <?php echo $current_page == 'dashboard.php' ? 'bg-white/15' : 'bg-emerald-50'; ?>">🏠</span>
            Dashboard
        </a>

        <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6">TRIPS MANAGEMENT</div>
        
        <a href="live-tracker.php" class="mt-3 flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99] font-semibold <?php echo $current_page == 'live-tracker.php' ? 'bg-brand-primary text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-brand-primary hover:translate-x-1'; ?>">
            <span class="inline-flex w-9 h-9 rounded-lg items-center justify-center <?php echo $current_page == 'live-tracker.php' ? 'bg-white/15' : 'bg-emerald-50'; ?>">🛰️</span>
            Live GPS Tracker
        </a>

        <a href="assigned-trips.php" class="mt-3 flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99] font-semibold <?php echo $current_page == 'assigned-trips.php' ? 'bg-brand-primary text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-brand-primary hover:translate-x-1'; ?>">
            <span class="inline-flex w-9 h-9 rounded-lg items-center justify-center <?php echo $current_page == 'assigned-trips.php' ? 'bg-white/15' : 'bg-emerald-50'; ?>">🛣️</span>
            Assigned Trips
        </a>

        <a href="trip-logs.php" class="mt-3 flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99] font-semibold <?php echo $current_page == 'trip-logs.php' ? 'bg-brand-primary text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-brand-primary hover:translate-x-1'; ?>">
            <span class="inline-flex w-9 h-9 rounded-lg items-center justify-center <?php echo $current_page == 'trip-logs.php' ? 'bg-white/15' : 'bg-emerald-50'; ?>">📜</span>
            Trip Logs
        </a>

        <a href="fuel-logs.php" class="mt-3 flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99] font-semibold <?php echo $current_page == 'fuel-logs.php' ? 'bg-brand-primary text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-brand-primary hover:translate-x-1'; ?>">
            <span class="inline-flex w-9 h-9 rounded-lg items-center justify-center <?php echo $current_page == 'fuel-logs.php' ? 'bg-white/15' : 'bg-emerald-50'; ?>">⛽</span>
            Fuel Updates
        </a>

        <a href="driver-contact.php" class="mt-3 flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 active:scale-[0.99] font-semibold <?php echo $current_page == 'driver-contact.php' ? 'bg-brand-primary text-white shadow' : 'text-gray-700 hover:bg-green-50 hover:text-brand-primary hover:translate-x-1'; ?>">
            <span class="inline-flex w-9 h-9 rounded-lg items-center justify-center <?php echo $current_page == 'driver-contact.php' ? 'bg-white/15' : 'bg-emerald-50'; ?>">‼️</span>
            Incident Report
        </a>

        <div class="mt-8 px-2 pb-6">
            <div class="flex items-center gap-2 text-xs font-bold text-emerald-600">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                SYSTEM ONLINE
            </div>
            <div class="text-[11px] text-gray-400 mt-2 leading-snug">
                Microfinance HR &copy; 2026<br />
                Human Resource III System
            </div>
        </div>
    </div>
</aside>