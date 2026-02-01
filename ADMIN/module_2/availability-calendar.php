<?php
include '../includes/functions.php';
$availability = getVehicleAvailability();
$vehicles = getVehicles();
$locations = getLocations();

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
$firstDayOfMonth = date('w', strtotime("$currentYear-$currentMonth-01"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability Calendar - Logistics Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-green': '#2D7A5C',
                        'light-green': '#E8F5F0',
                        'dark-green': '#1F5240',
                    }
                }
            }
        }
    </script>
    <style>
        .calendar-day {
            min-height: 100px;
            border: 1px solid #E5E7EB;
        }
        .calendar-day:hover {
            background-color: #F9FAFB;
        }
        .vehicle-badge {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            margin: 2px 0;
            display: block;
            cursor: pointer;
        }
        .vehicle-badge:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <!-- Header -->
        <?php include '../includes/header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Optional Subtitle -->
            <div class="mb-6">
                <p class="text-gray-600">Visual schedule showing vehicle availability and bookings</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Available Today</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php 
                                $today = date('Y-m-d');
                                $availableToday = count(array_filter($availability, fn($a) => $a['date'] === $today && $a['status'] === 'Available'));
                                echo $availableToday;
                                ?>
                            </div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> Ready to book
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Assigned Today</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php 
                                $assignedToday = count(array_filter($availability, fn($a) => $a['date'] === $today && $a['status'] === 'Assigned'));
                                echo $assignedToday;
                                ?>
                            </div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-truck"></i> Currently booked
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Under Maintenance</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php 
                                $maintenanceToday = count(array_filter($availability, fn($a) => $a['date'] === $today && $a['status'] === 'Maintenance'));
                                echo $maintenanceToday;
                                ?>
                            </div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-wrench"></i> Not available
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tools text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Fleet</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($vehicles); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-car"></i> Active vehicles
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-warehouse text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Legend -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Filters -->
                    <div class="flex flex-wrap gap-3">
                        <select id="filterType" onchange="filterCalendar()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Vehicle Types</option>
                            <option value="Van">Van</option>
                            <option value="Truck">Truck</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Car">Car</option>
                        </select>
                        <select id="filterLocation" onchange="filterCalendar()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Locations</option>
                            <?php foreach ($locations as $location): ?>
                            <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select id="filterStatus" onchange="filterCalendar()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="Available">Available</option>
                            <option value="Assigned">Assigned</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <!-- Legend -->
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="text-sm font-semibold text-gray-700">Legend:</div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-green-500 rounded"></span>
                            <span class="text-xs text-gray-600">Available</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-blue-500 rounded"></span>
                            <span class="text-xs text-gray-600">Assigned</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 bg-yellow-500 rounded"></span>
                            <span class="text-xs text-gray-600">Maintenance</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Calendar Header -->
                <div class="bg-primary-green text-white px-6 py-4 flex items-center justify-between">
                    <button class="p-2 hover:bg-white/10 rounded-lg transition-all" onclick="previousMonth()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h3 class="text-lg font-bold" id="calendarMonth"><?php echo date('F Y'); ?></h3>
                    <button class="p-2 hover:bg-white/10 rounded-lg transition-all" onclick="nextMonth()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Days of Week -->
                <div class="grid grid-cols-7 bg-gray-100">
                    <div class="p-3 text-center text-xs font-semibold text-gray-700 border-r border-gray-200">Sunday</div>
                    <div class="p-3 text-center text-xs font-semibold text-gray-700 border-r border-gray-200">Monday</div>
                    <div class="p-3 text-center text-xs font-semibold text-gray-700 border-r border-gray-200">Tuesday</div>
                    <div class="p-3 text-center text-xs font-semibold text-gray-700 border-r border-gray-200">Wednesday</div>
                    <div class="p-3 text-center text-xs font-semibold text-gray-700 border-r border-gray-200">Thursday</div>
                    <div class="p-3 text-center text-xs font-semibold text-gray-700 border-r border-gray-200">Friday</div>
                    <div class="p-3 text-center text-xs font-semibold text-gray-700">Saturday</div>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7" id="calendarGrid">
                    <?php
                    // Empty cells for days before month starts
                    for ($i = 0; $i < $firstDayOfMonth; $i++) {
                        echo '<div class="calendar-day bg-gray-50"></div>';
                    }

                    // Days of the month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = sprintf('%s-%s-%02d', $currentYear, $currentMonth, $day);
                        $isToday = $date === date('Y-m-d') ? 'bg-blue-50 border-2 border-blue-500' : '';
                        
                        echo "<div class='calendar-day p-2 $isToday' data-date='$date'>";
                        echo "<div class='text-sm font-semibold text-gray-700 mb-2'>$day</div>";
                        
                        // Get vehicles for this date
                        $dayVehicles = array_filter($availability, fn($a) => $a['date'] === $date);
                        
                        foreach ($dayVehicles as $vehicle) {
                            $statusColor = [
                                'Available' => 'bg-green-500 text-white',
                                'Assigned' => 'bg-blue-500 text-white',
                                'Maintenance' => 'bg-yellow-500 text-white'
                            ][$vehicle['status']];
                            
                            echo "<div class='vehicle-badge $statusColor' onclick='showVehicleDetails(" . json_encode($vehicle) . ")'>";
                            echo "<i class='fas fa-car'></i> " . $vehicle['vehicle'];
                            echo "</div>";
                        }
                        
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Vehicle List View (Mobile Friendly) -->
            <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-list text-primary-green"></i>
                        Vehicle Schedule (List View)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="vehicleList">
                        <?php
                        // Group by vehicle
                        $vehicleSchedules = [];
                        foreach ($availability as $item) {
                            if (!isset($vehicleSchedules[$item['vehicle']])) {
                                $vehicleSchedules[$item['vehicle']] = [];
                            }
                            $vehicleSchedules[$item['vehicle']][] = $item;
                        }

                        foreach ($vehicleSchedules as $vehiclePlate => $schedules):
                            $vehicleInfo = array_filter($vehicles, fn($v) => $v['plate'] === $vehiclePlate)[0] ?? null;
                            if (!$vehicleInfo) continue;
                        ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-car text-primary-green text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900"><?php echo $vehiclePlate; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $vehicleInfo['model']; ?> - <?php echo $vehicleInfo['type']; ?></div>
                                    </div>
                                </div>
                                <span class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-semibold">
                                    <?php echo count($schedules); ?> bookings
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($schedules as $schedule): ?>
                                    <?php
                                    $statusColor = [
                                        'Available' => 'bg-green-100 text-green-800 border-green-300',
                                        'Assigned' => 'bg-blue-100 text-blue-800 border-blue-300',
                                        'Maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-300'
                                    ][$schedule['status']];
                                    ?>
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border rounded-lg text-xs <?php echo $statusColor; ?>">
                                        <span class="font-semibold"><?php echo date('M d', strtotime($schedule['date'])); ?></span>
                                        <span>•</span>
                                        <span><?php echo $schedule['status']; ?></span>
                                        <?php if ($schedule['driver']): ?>
                                            <span>•</span>
                                            <span><?php echo $schedule['driver']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Vehicle Details Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="vehicleDetailsModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Vehicle Details</h3>
                <button onclick="closeVehicleDetailsModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-car text-primary-green text-lg"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900" id="modalVehicle">ABC-1234</div>
                            <div class="text-sm text-gray-500" id="modalModel">Toyota Hiace</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Date:</span>
                            <span class="text-sm text-gray-900" id="modalDate">-</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Status:</span>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" id="modalStatus">-</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Driver:</span>
                            <span class="text-sm text-gray-900" id="modalDriver">-</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Destination:</span>
                            <span class="text-sm text-gray-900" id="modalDestination">-</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Time:</span>
                            <span class="text-sm text-gray-900" id="modalTime">-</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <button class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeVehicleDetailsModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterCalendar() {
            const typeFilter = document.getElementById('filterType').value;
            const locationFilter = document.getElementById('filterLocation').value;
            const statusFilter = document.getElementById('filterStatus').value;

            const badges = document.querySelectorAll('.vehicle-badge');
            badges.forEach(badge => {
                const text = badge.textContent.toLowerCase();
                // This is simplified - in a real app, you'd filter based on actual data attributes
                badge.style.display = 'block';
            });
        }

        function showVehicleDetails(vehicle) {
            document.getElementById('modalVehicle').textContent = vehicle.vehicle;
            document.getElementById('modalDate').textContent = new Date(vehicle.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('modalDriver').textContent = vehicle.driver || 'Not assigned';
            document.getElementById('modalDestination').textContent = vehicle.destination || '-';
            document.getElementById('modalTime').textContent = vehicle.time || '-';
            
            const statusColors = {
                'Available': 'bg-green-100 text-green-800',
                'Assigned': 'bg-blue-100 text-blue-800',
                'Maintenance': 'bg-yellow-100 text-yellow-800'
            };
            
            const statusEl = document.getElementById('modalStatus');
            statusEl.textContent = vehicle.status;
            statusEl.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ' + statusColors[vehicle.status];
            
            document.getElementById('vehicleDetailsModal').classList.remove('hidden');
            document.getElementById('vehicleDetailsModal').classList.add('flex');
        }

        function closeVehicleDetailsModal() {
            document.getElementById('vehicleDetailsModal').classList.add('hidden');
            document.getElementById('vehicleDetailsModal').classList.remove('flex');
        }

        function previousMonth() {
            alert('Navigate to previous month - Implement with AJAX');
        }

        function nextMonth() {
            alert('Navigate to next month - Implement with AJAX');
        }

        // Close modal when clicking outside
        document.getElementById('vehicleDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVehicleDetailsModal();
            }
        });
    </script>
</body>
</html>