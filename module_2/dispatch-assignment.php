<?php
include '../includes/functions.php';
$dispatches = getDispatchAssignments();
$drivers = getAvailableDrivers();
$schedules = getDispatchSchedules();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch & Assignment - Logistics Admin</title>
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
                <p class="text-gray-600">Central control panel for assigning vehicles and drivers</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($dispatches); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-car"></i> In fleet
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Available Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($dispatches, fn($d) => $d['availability'] === 'Available')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> Ready to dispatch
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-double text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Active Assignments</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($dispatches, fn($d) => $d['availability'] === 'Assigned')); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-route"></i> On the road
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Available Drivers</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($drivers, fn($d) => $d['status'] === 'Available')); ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-users"></i> Ready to assign
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-id-card text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map and Calendar Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Real-time Map -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-primary-green"></i>
                        Real-Time Vehicle Locations
                    </h3>
                    <div class="flex items-center justify-center h-80 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <i class="fas fa-map text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 font-medium">Map Integration</p>
                            <p class="text-sm text-gray-500 mt-2">Google Maps / Leaflet.js integration</p>
                            <button class="mt-4 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all">
                                <i class="fas fa-map-marker-alt"></i> View Full Map
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dispatch Calendar -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-primary-green"></i>
                        Scheduled Dispatches
                    </h3>
                    <div class="space-y-3">
                        <?php foreach ($schedules as $schedule): ?>
                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-primary-green"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-gray-900"><?php echo $schedule['vehicle']; ?></span>
                                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full"><?php echo date('M d', strtotime($schedule['date'])); ?></span>
                                </div>
                                <div class="text-xs text-gray-600">
                                    <i class="fas fa-user text-gray-400"></i> <?php echo $schedule['driver']; ?>
                                </div>
                                <div class="text-xs text-gray-600">
                                    <i class="fas fa-route text-gray-400"></i> <?php echo $schedule['route']; ?>
                                </div>
                            </div>
                            <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="w-full mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200 transition-all">
                        <i class="fas fa-calendar-plus"></i> View Full Calendar
                    </button>
                </div>
            </div>

            <!-- Dispatch Assignments Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-clipboard-list text-primary-green"></i>
                            Vehicle Assignments
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3">
                                <input type="text" id="searchInput" placeholder="Search by vehicle or driver..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterType" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Types</option>
                                    <option value="Van">Van</option>
                                    <option value="Truck">Truck</option>
                                    <option value="Motorcycle">Motorcycle</option>
                                    <option value="Car">Car</option>
                                </select>
                                <select id="filterAvailability" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Assigned">Assigned</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="openAssignModal()">
                                <i class="fas fa-user-plus"></i> Assign Driver
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="dispatchTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Plate Number</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Route</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Dispatch Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($dispatches as $dispatch): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green">#<?php echo str_pad($dispatch['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="font-semibold text-gray-900"><?php echo $dispatch['vehicle']; ?></div>
                                    <div class="text-xs text-gray-500"><?php echo $dispatch['model']; ?></div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $dispatch['type']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <?php if ($dispatch['driver']): ?>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-primary-green rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                <?php echo strtoupper(substr($dispatch['driver'], 0, 2)); ?>
                                            </div>
                                            <span class="text-gray-900"><?php echo $dispatch['driver']; ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">No driver assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <?php echo $dispatch['route'] ? '<i class="fas fa-route text-gray-400 mr-1"></i>' . $dispatch['route'] : '<span class="text-gray-400">-</span>'; ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <?php echo $dispatch['dispatch_date'] ? date('M d, Y', strtotime($dispatch['dispatch_date'])) : '<span class="text-gray-400">-</span>'; ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php 
                                        echo $dispatch['availability'] === 'Available' ? 'bg-green-100 text-green-800' : 
                                            ($dispatch['availability'] === 'Assigned' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'); 
                                    ?>">
                                        <?php echo $dispatch['availability']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <?php if ($dispatch['availability'] === 'Available'): ?>
                                            <button class="px-3 py-1.5 bg-primary-green text-white rounded-md text-xs font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-1.5" onclick="assignVehicle(<?php echo $dispatch['id']; ?>, '<?php echo $dispatch['vehicle']; ?>')">
                                                <i class="fas fa-user-plus"></i> Assign
                                            </button>
                                        <?php elseif ($dispatch['availability'] === 'Assigned'): ?>
                                            <button class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 transition-all inline-flex items-center gap-1.5" onclick="unassignVehicle(<?php echo $dispatch['id']; ?>, '<?php echo $dispatch['vehicle']; ?>')">
                                                <i class="fas fa-user-times"></i> Unassign
                                            </button>
                                        <?php endif; ?>
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="viewDetails(<?php echo $dispatch['id']; ?>)">
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Assign Driver Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="assignModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Assign Driver to Vehicle</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="assignForm" onsubmit="saveAssignment(event)" class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle *</label>
                    <select id="vehicleSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                        <option value="">Select Vehicle</option>
                        <?php foreach (array_filter($dispatches, fn($d) => $d['availability'] === 'Available') as $vehicle): ?>
                        <option value="<?php echo $vehicle['vehicle']; ?>"><?php echo $vehicle['vehicle']; ?> - <?php echo $vehicle['model']; ?> (<?php echo $vehicle['type']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Driver *</label>
                    <select id="driverSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                        <option value="">Select Driver</option>
                        <?php foreach (array_filter($drivers, fn($d) => $d['status'] === 'Available' || $d['status'] === 'On Duty') as $driver): ?>
                        <option value="<?php echo $driver['name']; ?>"><?php echo $driver['name']; ?> - <?php echo $driver['license']; ?> (<?php echo $driver['status']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dispatch Date *</label>
                        <input type="date" id="dispatchDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expected Return</label>
                        <input type="date" id="returnDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Route/Destination *</label>
                    <input type="text" id="route" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="e.g., Manila - Quezon City" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Purpose</label>
                    <select id="purpose" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        <option value="Delivery">Delivery</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Service">Service Call</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Additional notes..." rows="3"></textarea>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeAssignModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i> Assign Driver
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('filterType').value;
            const availabilityFilter = document.getElementById('filterAvailability').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[1].textContent.toLowerCase();
                const type = row.cells[2].textContent;
                const driver = row.cells[3].textContent.toLowerCase();
                const availability = row.cells[6].textContent.trim();

                const matchesSearch = vehicle.includes(searchInput) || driver.includes(searchInput);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesAvailability = !availabilityFilter || availability === availabilityFilter;

                row.style.display = matchesSearch && matchesType && matchesAvailability ? '' : 'none';
            });
        }

        function openAssignModal() {
            document.getElementById('assignForm').reset();
            document.getElementById('assignModal').classList.remove('hidden');
            document.getElementById('assignModal').classList.add('flex');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
            document.getElementById('assignModal').classList.remove('flex');
        }

        function assignVehicle(id, vehicle) {
            document.getElementById('vehicleSelect').value = vehicle;
            openAssignModal();
        }

        function unassignVehicle(id, vehicle) {
            if (confirm(`Are you sure you want to unassign driver from ${vehicle}?`)) {
                alert('Driver unassigned successfully!');
                // Reload or update table here
            }
        }

        function saveAssignment(event) {
            event.preventDefault();
            alert('Driver assigned successfully!');
            closeAssignModal();
            // Reload page or update table here
        }

        function viewDetails(id) {
            alert('Viewing detailed vehicle assignment information...');
        }

        // Close modal when clicking outside
        document.getElementById('assignModal').addEventListener('click', function(e) {
            if (e.target === this) {closeAssignModal();
            }
        });

        // Set today's date as default for dispatch date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            if (document.getElementById('dispatchDate')) {
                document.getElementById('dispatchDate').value = today;
            }
        });
    </script>
</body>
</html>