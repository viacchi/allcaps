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
                <div class="text-white text-opacity-70 text-xs">Staff Panel</div>
            </div>
        </div>
    </div>

    <nav class="mt-6 pb-6">

        <!-- Dashboard -->
        <div class="mb-2">
            <a href="/CAPTONES/staff-dashboard.php" class="nav-item text-white/80 px-5 py-3 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white
       <?php echo $current_page === 'dashboard' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
        <i class="fa-solid fa-chart-line"></i>
        <span class="font-semibold">Dashboard</span>
    </a>
</div>

        <!-- Transport Management -->
        <div class="mb-2">
            <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['vehicle-registry', 'maintenance-tracker', 'fuel-expense-records', 'maintenance-approvals', 'compliance-licensing', 'predictive-maintenance']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('fleet')">
                <div class="flex items-center gap-3">
                    <i class="fas fa-truck"></i>
                    <span class="font-semibold">Transport Management</span>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['vehicle-registry', 'maintenance-tracker', 'fuel-expense-records', 'maintenance-approvals', 'compliance-licensing', 'predictive-maintenance']) ? 'rotate-180' : ''; ?>" id="fleet-icon"></i>
            </div>
            <div class="<?php echo in_array($current_page, ['assigned-vehicle', 'maintenance-tracker', 'fuel-expense-records', 'maintenance-approvals', 'compliance-licensing', 'predictive-maintenance']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="fleet-submenu">
                <!-- PAGE #1 -->
                <a href="/CAPTONES/staff/assigned-vehicle.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'vehicle-registry' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-car text-sm"></i>
                    <span class="text-sm">Assigned Vehicle</span>
                </a>
                
                <!-- PAGE #2 -->
                <a href="/CAPTONES/staff/fuel-tracking.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'maintenance-tracker' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fa-solid fa-gas-pump text-sm"></i>
                    <span class="text-sm">Fuel, Mileage & Expense Tracking</span>
                </a>
                
                <!-- PAGE #3 -->
                <a href="/CAPTONES/staff/trip-logs.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'fuel-expense-records' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fas fa-route text-sm"></i>
                    <span class="text-sm">Trip Logs </span>
                </a>
                
            </div>
        </div>

        <!-- Reservation Management -->
         <div class="mb-2">
             <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['transport-cost-optimization']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('reservation')">
                 <div class="flex items-center gap-3">
            <i class="fa-solid fa-calendar-days"></i>
            <span class="font-semibold">Reservation Management</span>
        </div>
        <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['my-reservation']) ? 'rotate-180' : ''; ?>" id="reservation-icon"></i>
    </div>
    <div class="<?php echo in_array($current_page, ['my-reservation']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="reservation-submenu">
        <a href="/CAPTONES/staff/my-reservation.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'transport-cost-optimization' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
            <i class="fa-regular fa-calendar text-sm"></i>
            <span class="text-sm">My Reservation</span>
        </a>
    </div>
</div>
  
   <!-- Driver Management -->
    <div class="mb-2">
        <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['driver-performance']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('driver')">
             <div class="flex items-center gap-3">
                <i class="fa-solid fa-id-card"></i>
                <span class="font-semibold">Driver Management</span>
        </div>
        <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['driver-performance']) ? 'rotate-180' : ''; ?>" id="driver-icon"></i>
    </div>
    <div class="<?php echo in_array($current_page, ['driver-performance']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="driver-submenu">
        <a href="/CAPTONES/staff/driver-performance.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'driver-performance' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
            <i class="fa-solid fa-address-card text-sm"></i>
            <span class="text-sm">Driver Performance</span>
        </a>
    </div>
</div>

<!-- Incident Management -->
    <div class="mb-2">
        <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['incident-reports']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('incident')">
             <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="font-semibold">Incident Management</span>
        </div>
        <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['incident-reports']) ? 'rotate-180' : ''; ?>" id="incident-icon"></i>
    </div>
    <div class="<?php echo in_array($current_page, ['incident-reports']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="incident-submenu">
        <a href="/CAPTONES/staff/incident-reports.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'driver-performance' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
            <i class="fa-solid fa-file-circle-exclamation text-sm"></i>
            <span class="text-sm">Incident Reporting</span>
        </a>
    </div>
</div>


        <!-- Need Help Section (Inside scrollable area) -->
         <div class="mt-6 mx-4 mb-4">
            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                <i class="fas fa-headset text-white text-2xl mb-2"></i>
                <div class="text-white text-sm font-semibold">Need Help?</div>
                <div class="text-white text-opacity-70 text-xs mt-1">Contact support team</div>
                <button class="w-full mt-3 px-4 py-2 bg-white bg-opacity-20 text-white rounded-md text-sm font-semibold hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center gap-2" onclick="window.location.href='/CAPTONES/staff-contact.php'">
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

    // Close all other open submenus
    document.querySelectorAll('[id$="-submenu"]').forEach((menu) => {
        if (menu.id !== `${moduleName}-submenu`) {
            menu.classList.add('hidden');
        }
    });

    // Reset all icons
    document.querySelectorAll('[id$="-icon"]').forEach((ic) => {
        if (ic.id !== `${moduleName}-icon`) {
            ic.classList.remove('rotate-180');
        }
    });

    // Toggle the clicked submenu
    submenu.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

function contactSupport() {
    alert('Opening support contact form...');
}
</script>
