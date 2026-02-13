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
                <div class="text-white text-opacity-70 text-xs">Driver Panel</div>
            </div>
        </div>
    </div>

    <nav class="mt-6 pb-6">

        <!-- Dashboard -->
         <div class="mb-2">
            <a href="/CAPTONES/driver-dashboard.php" class="nav-item text-white/80 px-5 py-3 flex items-center gap-3 cursor-pointer transiton-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white
           <?php echo $current_page === 'dashboard' ? 'bg-white/15 border-l-white text-white'  : ''; ?> " >

           <i class="fa-solid fa-chart-line"></i>
           <span class="font-semibold">Dashboard</span>
            </a>
         </div>

        <!-- Assigned Trips -->
        <div class="mb-2">
            <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['trips-details', 'trip-logs', 'fuel-updates','incident-report', 'performance']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('trips-details')">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-clipboard-list"></i>
                   <span class="font-semibold">Assigned Trips</span>
                </div>
                <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['trips-details', 'trip-logs', 'fuel-updates','incident-report', 'performance']) ? 'rotate-180' : ''; ?>" id="trips-details-icon"></i>
            </div>
            <div class="<?php echo in_array($current_page, ['trips-details', 'trip-logs', 'fuel-updates','incident-report', 'performance']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="trips-details-submenu">
                <!-- PAGE #1 -->
                <a href="/CAPTONES/DRIVER/trips-details.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'trips-details' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
                    <i class="fa-solid fa-map-location-dot text-sm"></i>
                    <span class="text-sm">Trips Details</span>
                </a>
                            
            </div>
        </div>

        <!-- Trip Logs  -->
         <div class="mb-2">
             <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['trip-logs']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('trip-logs')">
                 <div class="flex items-center gap-3">
                    <i class="fa-solid fa-road"></i>
            <span class="font-semibold">Trip Logs </span>
        </div>
        <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['trip-logs']) ? 'rotate-180' : ''; ?>" id="trip-logs-icon"></i>
    </div>
    <div class="<?php echo in_array($current_page, ['trip-logs']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="trip-logs-submenu">
        <a href="/CAPTONES/driver/mileage-tracking.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'trip-logs' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
            <i class="fa-solid fa-gauge-high text-sm"></i>
            <span class="text-sm">Mileage Tracking</span>
        </a>
    </div>
</div>
  

<!-- Fuel Updates -->
 <div class="mb-2">
    <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['fuel-updates']) ? 'bg-white/10' : ''; ?>"  onclick="toggleModule('fuel-updates')">

    <div class="flex items-center gap-3">
      <i class="fa-solid fa-gas-pump"></i>
      <span class="font-semibold">Fuel Updates</span>
    </div>
    <i class="fas fa-chevron-down transition-transform duration-300  <?php echo in_array($current_page, ['fuel-updates']) ? 'rotate-180' : ''; ?>" id="fuel-updates-icon"></i>
  </div>

  <div class="<?php echo in_array($current_page, ['fuel-updates']) ? '' : 'hidden'; ?>  bg-white/5 overflow-hidden transition-all duration-300" id="fuel-updates-submenu">

    <a href="/CAPTONES/driver/fuel-tracking.php"  class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'fuel-updates' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
      <i class="fa-solid fa-peso-sign text-sm"></i>
      <span class="text-sm">Fuel Cost Tracking</span>
    </a>    
  </div>
</div>


<!-- Incident Report -->
    <div class="mb-2">
        <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['incident-report']) ? 'bg-white/10' : ''; ?>" onclick="toggleModule('incident-report')">
             <div class="flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="font-semibold">Incident Report</span>
        </div>
        <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['incident-report']) ? 'rotate-180' : ''; ?>" id="incident-report-icon"></i>
    </div>
    <div class="<?php echo in_array($current_page, ['incident-report']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="incident-report-submenu">
        <a href="/CAPTONES/driver/incident-report.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'incident-report' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
        <i class="fa-solid fa-folder-open text-sm"></i>
            <span class="text-sm">Incident History </span>
        </a>
    </div>
</div>

<!-- Performance -->
    <div class="mb-2">
        <div class="nav-item text-white/80 px-5 py-3 flex items-center justify-between cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo in_array($current_page, ['performance']) ? 'bg-white/10' : ''; ?>"onclick="toggleModule('performance')">  
             <div class="flex items-center gap-3">
                <i class="fa-solid fa-chart-area"></i>
                <span class="font-semibold">Performance</span>
        </div>
        <i class="fas fa-chevron-down transition-transform duration-300 <?php echo in_array($current_page, ['performance']) ? 'rotate-180' : ''; ?>" id="performance-icon"></i>
    </div>
    <div class="<?php echo in_array($current_page, ['performance']) ? '' : 'hidden'; ?> bg-white/5 overflow-hidden transition-all duration-300" id="performance-submenu">
        <a href="/CAPTONES/driver/performance.php" class="nav-item text-white/70 px-5 py-2.5 pl-14 flex items-center gap-3 cursor-pointer transition-all duration-300 border-l-3 border-transparent hover:bg-white/10 hover:text-white <?php echo $current_page === 'performance' ? 'bg-white/15 border-l-white text-white' : ''; ?>">
            <i class="fa-solid fa-ranking-star text-sm"></i>
            <span class="text-sm">Efficiency Score</span>
        </a>
    </div>
</div>


        <!-- Need Help Section (Inside scrollable area) -->
         <div class="mt-6 mx-4 mb-4">
            <div class="bg-white bg-opacity-10 rounded-lg p-4 text-center">
                <i class="fas fa-headset text-white text-2xl mb-2"></i>
                <div class="text-white text-sm font-semibold">Need Help?</div>
                <div class="text-white text-opacity-70 text-xs mt-1">Contact support team</div>
                <button class="w-full mt-3 px-4 py-2 bg-white bg-opacity-20 text-white rounded-md text-sm font-semibold hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center gap-2" onclick="window.location.href='/CAPTONES/other-employee-contact.php'">
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
