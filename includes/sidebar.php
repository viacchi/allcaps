<?php
// Sidebar component - Left navigation with collapsible modules
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Hide scrollbar but keep functionality */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="fixed left-0 top-0 w-0 md:w-[280px] h-screen bg-primary-green overflow-y-auto no-scrollbar z-40 transition-all duration-300" id="sidebar" style="background-color: #2D7A5C;">
    <!-- Sidebar Header (without hamburger) -->
    <div class="p-6 border-b border-white border-opacity-20">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-truck text-white text-lg"></i>
            </div>
            <div>
                <div class="text-white font-bold text-sm">Logistics 2</div>
                <div class="text-white text-opacity-70 text-xs">Admin Panel</div>
            </div>
        </div>
    </div>

    <nav class="mt-6 pb-6">
        <!-- Fleet & Vehicle Management Module -->
        <div class="mb-2">
            <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['vehicle-registry', 'maintenance-tracker', 'fuel-expense-records', 'maintenance-approvals', 'compliance-licensing', 'predictive-maintenance']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('fleet')">
                <div class="flex items-center gap-3">
                    <i class="fas fa-truck"></i>
                    <span class="font-semibold">Fleet & Vehicle Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['vehicle-registry', 'maintenance-tracker', 'fuel-expense-records', 'maintenance-approvals', 'compliance-licensing', 'predictive-maintenance']) ? 'rotate-180' : ''; ?>" id="fleet-icon"></i>
            </div>
            <div class="<?php echo in_array($current_page, ['vehicle-registry', 'maintenance-tracker', 'fuel-expense-records', 'maintenance-approvals', 'compliance-licensing', 'predictive-maintenance']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="fleet-submenu">
                <!-- PAGE #1 -->
                <a href="/CAPTONES/module_1/vehicle-registry.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'vehicle-registry' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-car text-sm"></i>
                    <span class="text-sm">Vehicle Registry</span>
                </a>
                
                <!-- PAGE #2 -->
                <a href="/CAPTONES/module_1/maintenance-tracker.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'maintenance-tracker' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-wrench text-sm"></i>
                    <span class="text-sm">Maintenance Tracker</span>
                </a>
                
                <!-- PAGE #3 -->
                <a href="/CAPTONES/module_1/fuel-expense-records.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'fuel-expense-records' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-gas-pump text-sm"></i>
                    <span class="text-sm">Fuel & Expense Records</span>
                </a>
                
                <!-- PAGE #4 -->
                <a href="/CAPTONES/module_1/maintenance-approvals.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'maintenance-approvals' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-check-circle text-sm"></i>
                    <span class="text-sm">Maintenance Approvals</span>
                </a>
                
                <!-- PAGE #5 -->
                <a href="/CAPTONES/module_1/compliance-licensing.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'compliance-licensing' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-file-contract text-sm"></i>
                    <span class="text-sm">Compliance & Licensing</span>
                </a>
                
                <!-- PAGE #6 -->
                <a href="/CAPTONES/module_1/predictive-maintenance.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'predictive-maintenance' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-brain text-sm"></i>
                    <span class="text-sm">Predictive Maintenance</span>
                </a>
            </div>
        </div>

        <!-- Vehicle Reservation & Dispatch System (VRDS) - Module 2 -->
            <div class="mb-2">
                <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['dispatch-assignment', 'reservation-management', 'availability-calendar']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('vrds')">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-calendar-check"></i>
                        <span class="font-semibold">Reservation & Dispatch</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['dispatch-assignment', 'reservation-management', 'availability-calendar']) ? 'rotate-180' : ''; ?>" id="vrds-icon"></i>
                </div>
                <div class="<?php echo in_array($current_page, ['dispatch-assignment', 'reservation-management', 'availability-calendar']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="vrds-submenu">
                    <!-- PAGE #7 -->
                    <a href="/CAPTONES/module_2/dispatch-assignment.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'dispatch-assignment' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-clipboard-list text-sm"></i>
                        <span class="text-sm">Dispatch & Assignment</span>
                    </a>
                    
                    <!-- PAGE #8 -->
                    <a href="/CAPTONES/module_2/reservation-management.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'reservation-management' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-tasks text-sm"></i>
                        <span class="text-sm">Reservation Management</span>
                    </a>
                    
                    <!-- PAGE #9 -->
                    <a href="/CAPTONES/module_2/availability-calendar.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'availability-calendar' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-calendar-alt text-sm"></i>
                        <span class="text-sm">Availability Calendar</span>
                    </a>
                </div>
            </div>

        <!-- Driver and Trip Performance Monitoring - Module 3 -->
            <div class="mb-2">
                <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['behavior-analytics', 'incident-management', 'driver-profiles', 'trip-performance']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('performance')">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user-check"></i>
                        <span class="font-semibold">Performance Monitoring</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['behavior-analytics', 'incident-management', 'driver-profiles', 'trip-performance']) ? 'rotate-180' : ''; ?>" id="performance-icon"></i>
                </div>
                <div class="<?php echo in_array($current_page, ['behavior-analytics', 'incident-management', 'driver-profiles', 'trip-performance']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="performance-submenu">
                    <!-- PAGE #10 -->
                    <a href="/CAPTONES/module_3/behavior-analytics.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'behavior-analytics' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-chart-line text-sm"></i>
                        <span class="text-sm">Behavior Analytics</span>
                    </a>
                    
                    <!-- PAGE #11 -->
                    <a href="/CAPTONES/module_3/incident-management.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'incident-management' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-exclamation-triangle text-sm"></i>
                        <span class="text-sm">Incident Management</span>
                    </a>
                    
                    <!-- PAGE #12 -->
                    <a href="/CAPTONES/module_3/driver-profiles.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'driver-profiles' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-id-card-alt text-sm"></i>
                        <span class="text-sm">Driver Profiles</span>
                    </a>
                    
                    <!-- PAGE #13 -->
                    <a href="/CAPTONES/module_3/trip-performance.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'trip-performance' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                        <i class="fas fa-route text-sm"></i>
                        <span class="text-sm">Trip Performance</span>
                    </a>
                </div>
            </div>

        <!-- TRANSPORT COST ANALYSIS & OPTIMIZATION -->
        <div class="mb-2">
            <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['fleet-reports', 'financial-reports', 'analytics-dashboard', 'export-data']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('reports')">
                <div class="flex items-center gap-3">
                    <i class="fas fa-chart-bar"></i>
                    <span class="font-semibold">Transport Cost Analysis & Optimization</span>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['transport-cost-optimization']) ? 'rotate-180' : ''; ?>" id="reports-icon"></i>
            </div>
            <div class="<?php echo in_array($current_page, ['transport-cost-optimization']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="reports-submenu">
                <a href="/CAPTONES/module_4/transport-cost-optimization.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'fleet-reports' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-file-alt text-sm"></i>
                    <span class="text-sm">Transport Cost & Optimization</span>
                </a>
            </div>
        </div>

        <!-- Need Help Section (Inside scrollable area) -->
        <div class="mt-6 mx-4 mb-4">
            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                <i class="fas fa-headset text-white text-2xl mb-2"></i>
                <div class="text-white text-sm font-semibold">Need Help?</div>
                <div class="text-white text-opacity-70 text-xs mt-1">Contact support team</div>
                <button class="w-full mt-3 px-4 py-2 bg-white bg-opacity-20 text-white rounded-md text-sm font-semibold hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center gap-2" onclick="window.location.href='/CAPTONES/contact-support.php'">
                     <i class="fas fa-envelope"></i> Contact
                </button>
            </div>
        </div>
    </nav>
</div>

<script>
function toggleModule(moduleName) {
    const submenu = document.getElementById(`${moduleName}-submenu`);
    const icon = document.getElementById(`${moduleName}-icon`);
    
    if (submenu.classList.contains('hidden')) {
        submenu.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        submenu.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

function contactSupport() {
    alert('Opening support contact form...');
}
</script>