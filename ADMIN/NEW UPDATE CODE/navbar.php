<?php
include 'db.php';
session_start();

// This will be the exact name from the database if logged in
$username = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>

  <body class="bg-gray-100">
    <!-- Top Navbar -->
    <nav class="fixed top-0 left-0 w-full p-3 h-16 bg-[#28644c] text-white shadow-md z-50 flex justify-between items-center">
      <!-- Toggle Button -->
      <button id="toggle-btn" class="text-white text-2xl focus:outline-none">
        <i class="bx bx-menu"></i>
      </button>

      <!-- User Info -->
      <div class="flex items-center space-x-3 pr-4">
        <i class="fa-solid fa-user text-[18px] bg-white text-[#28644c] px-2.5 py-2 rounded-full"></i>
        <span class="text-white font-medium"><?php echo $username; ?></span>
      </div>
    </nav>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

    <!-- Sidebar -->
    <aside
      id="sidebar"
      class="bg-[#2f855A] text-white flex flex-col z-50 fixed top-16 left-0 h-[calc(100vh-4rem)] w-72 transform -translate-x-72 md:translate-x-0 transition-transform duration-300 ease-in-out"
    >
      <div class="department-header px-2 py-4 mx-2 border-b border-white/50">
        <h1 class="text-xl text-center font-bold">Logistics 2</h1>
      </div>

      <div class="px-3 py-10 flex-1 overflow-y-auto">
        <ul class="space-y-6">

          <li class="has-dropdown">
            <div class="flex items-center justify-between px-4 py-2.5 text-lg hover:bg-white/30 rounded-lg cursor-pointer">
              <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
						 d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM3 5h11v12H3V5zm11 0h4l3 5v7h-7V5z" />
				    </svg>
                <span>Fleet & Vehicle <br />Management</span>
              </div>
              <i class="bx bx-chevron-down text-2xl transition-transform"></i>
            </div>
            <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
              <li><a href="../FVM/vehicle-registry.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Vehicle Registry</a></li>
              <li><a href="../FVM/maintenance-tracker.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Maintenance Tracker</a></li>
              <li><a href="../FVM/fuel-expense.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Fuel & Expense Records</a></li>
              <li><a href="../FVM/maintenance-schedule.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Maintenance Schedule Approvals</a></li>
              <li><a href="../FVM/predictive-maintenance.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Predictive Maintenance Forecasting</a></li>
            </ul>
          </li>

          <!-- Vehicle Reservation & Dispatch -->
          <li class="has-dropdown">
            <div class="flex items-center justify-between px-4 py-2.5 text-lg hover:bg-white/30 rounded-lg cursor-pointer">
              <div class="flex items-center space-x-2">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 16 16" fill="currentColor">
						  <path d="M2.908 2.067A.978.978 0 0 0 2 3.05V8h6V3.05a.978.978 0 0 0-.908-.983 32.481 32.481 0 0 0-4.184 0Z" />
						  <path d="M12.919 4.722A.98.98 0 0 0 11.968 4H10a1 1 0 0 0-1 1v6.268A2 2 0 0 1 12 13h1a.977.977 0 0 0 .985-1 31.99 31.99 0 0 0-1.066-7.278Z" />
						  <path d="M11 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM2 12V9h6v3a1 1 0 0 1-1 1 2 2 0 1 0-4 0 1 1 0 0 1-1-1Z" />
						  <path d="M6 13a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" />
				    </svg>      
                <span>Vehicle Reservation & <br />Dispatch System</span>
              </div>
              <i class="bx bx-chevron-down text-2xl transition-transform"></i>
            </div>
            <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
              <li><a href="../VRDS/dispatch-assignment.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Dispatch & Assignment Dashboard</a></li>
              <li><a href="../VRDS/reservation-management.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Reservation Management</a></li>
              <li><a href="../VRDS/availability-calendar.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Availability Calendar</a></li>
            </ul>
          </li>

          <!-- Driver & Trip Performance -->
          <li class="has-dropdown">
            <div class="flex items-center justify-between px-4 py-2.5 text-lg hover:bg-white/30 rounded-lg cursor-pointer">
              <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 16 16" fill="currentColor" >
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
                    </svg>
                <span>Driver & Trip <br />Performance</span>
              </div>
              <i class="bx bx-chevron-down text-2xl transition-transform"></i>
            </div>
            <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
              <li><a href="../DTPM/incident-case.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Incident Case Management</a></li>
              <li><a href="../DTPM/driver-profiles.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Driver Profiles</a></li>
            </ul>
          </li>

          <!-- Transport Cost Analysis -->
          <li class="has-dropdown">
            <div class="flex items-center justify-between px-4 py-2.5 text-lg hover:bg-white/30 rounded-lg cursor-pointer">
              <div class="flex items-center space-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 16 16" fill="currentColor" >
                        <path d="M6.375 5.5h.875v1.75h-.875a.875.875 0 1 1 0-1.75ZM8.75 10.5V8.75h.875a.875.875 0 0 1 0 1.75H8.75Z" />
                        <path fill-rule="evenodd" d="M15 8A7 7 0 1 1 1 8a7 7 0 0 1 14 0ZM7.25 3.75a.75.75 0 0 1 1.5 0V4h2.5a.75.75 0 0 1 0 1.5h-2.5v1.75h.875a2.375 2.375 0 1 1 0 4.75H8.75v.25a.75.75 0 0 1-1.5 0V12h-2.5a.75.75 0 0 1 0-1.5h2.5V8.75h-.875a2.375 2.375 0 1 1 0-4.75h.875v-.25Z" clip-rule="evenodd" />
                    </svg>
                <span>Transport Cost Analysis <br />& Optimization</span>
              </div>
              <i class="bx bx-chevron-down text-2xl transition-transform"></i>
            </div>
            <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
              <li><a href="../TCAO/transport-cost.php" class="block px-3 py-2 text-sm hover:bg-white/30 rounded-lg">Transport Cost Analysis & Optimization</a></li>
            </ul>
          </li>

         </ul>
              <hr class="my-1">
              <a href="../../logout.php" class="w-full flex items-center gap-2 px-4 py-2 text-xl hover:bg-white/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
              </svg>
              <span>Logout</span>
            </a>
      </div>
    </aside>


    <!-- JS for Sidebar & Dropdown -->
    <script>
      const toggleBtn = document.getElementById("toggle-btn");
      const sidebar = document.getElementById("sidebar");
      const overlay = document.getElementById("overlay");
      const dropdownToggles = document.querySelectorAll(".has-dropdown > div");

      toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("-translate-x-72");
        overlay.classList.toggle("hidden");
      });

      overlay.addEventListener("click", () => {
        sidebar.classList.add("-translate-x-72");
        overlay.classList.add("hidden");
      });

      dropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", () => {
          const dropdown = toggle.nextElementSibling;
          const icon = toggle.querySelector(".bx-chevron-down");
          dropdown.classList.toggle("hidden");
          icon.classList.toggle("rotate-180");
        });
      });

      window.addEventListener("resize", () => {
        if (window.innerWidth >= 768) {
          sidebar.classList.remove("-translate-x-72");
          overlay.classList.add("hidden");
        } else {
          sidebar.classList.add("-translate-x-72");
        }
      });
    </script>
  </body>
</html>
