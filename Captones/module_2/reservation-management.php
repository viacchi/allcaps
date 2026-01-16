<?php
include '../includes/functions.php';
$reservations = getReservations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Management - Logistics Admin</title>
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
                <p class="text-gray-600">Record and track all vehicle dispatches and reservations</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Pending Dispatch</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($reservations, fn($r) => $r['status'] === 'Pending Dispatch')); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-clock"></i> Awaiting assignment
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Assigned</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($reservations, fn($r) => $r['status'] === 'Assigned')); ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-user-check"></i> Ready to depart
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">In Use</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($reservations, fn($r) => $r['status'] === 'In Use')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-route"></i> Currently active
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-truck-moving text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Completed</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($reservations, fn($r) => $r['status'] === 'Completed')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> Successfully done
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-double text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Cancelled</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($reservations, fn($r) => $r['status'] === 'Cancelled')); ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-ban"></i> Not proceeded
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservations Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-tasks text-primary-green"></i>
                            Reservation Records
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3 flex-wrap">
                                <input type="text" id="searchInput" placeholder="Search by vehicle, driver..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterStatus" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Status</option>
                                    <option value="Pending Dispatch">Pending Dispatch</option>
                                    <option value="Assigned">Assigned</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                                <select id="filterType" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Types</option>
                                    <option value="Van">Van</option>
                                    <option value="Truck">Truck</option>
                                    <option value="Motorcycle">Motorcycle</option>
                                    <option value="Car">Car</option>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="openNewReservationModal()">
                                <i class="fas fa-plus"></i> New Reservation
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="reservationTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Reservation ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Assigned Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date/Time</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Destination</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($reservations as $reservation): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green">#RES-<?php echo str_pad($reservation['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="font-semibold text-gray-900"><?php echo $reservation['vehicle']; ?></div>
                                    <div class="text-xs text-gray-500"><?php echo $reservation['model']; ?> (<?php echo $reservation['type']; ?>)</div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <?php if ($reservation['driver']): ?>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-primary-green rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                <?php echo strtoupper(substr($reservation['driver'], 0, 2)); ?>
                                            </div>
                                            <span class="text-gray-900"><?php echo $reservation['driver']; ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">Not assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="text-gray-900"><?php echo date('M d, Y', strtotime($reservation['date'])); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo $reservation['time']; ?></div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    <?php echo $reservation['destination']; ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <?php
                                    $statusColors = [
                                        'Pending Dispatch' => 'bg-blue-100 text-blue-800',
                                        'Assigned' => 'bg-yellow-100 text-yellow-800',
                                        'In Use' => 'bg-primary-green/10 text-primary-green',
                                        'Completed' => 'bg-green-100 text-green-800',
                                        'Cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                    ?>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $statusColors[$reservation['status']]; ?>">
                                        <?php echo $reservation['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="viewDetails(<?php echo $reservation['id']; ?>)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <?php if ($reservation['status'] === 'Pending Dispatch'): ?>
                                            <button class="px-3 py-1.5 bg-primary-green text-white rounded-md text-xs font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-1.5" onclick="assignDriver(<?php echo $reservation['id']; ?>)">
                                                <i class="fas fa-user-plus"></i> Assign
                                            </button>
                                        <?php elseif ($reservation['status'] === 'In Use'): ?>
                                            <button class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs font-semibold hover:bg-green-700 transition-all inline-flex items-center gap-1.5" onclick="markCompleted(<?php echo $reservation['id']; ?>)">
                                                <i class="fas fa-check"></i> Complete
                                            </button>
                                        <?php endif; ?>
                                        <?php if (in_array($reservation['status'], ['Pending Dispatch', 'Assigned'])): ?>
                                            <button class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 transition-all inline-flex items-center gap-1.5" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        <?php endif; ?>
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

    <!-- Reservation Details Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="detailsModal">
        <div class="bg-white rounded-lg w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Reservation Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Timeline Panel -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Reservation Timeline</h4>
                    <div class="flex items-center justify-between relative">
                        <!-- Progress Line -->
                        <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 z-0">
                            <div class="h-full bg-primary-green transition-all duration-500" style="width: 50%"></div>
                        </div>
                        
                        <!-- Timeline Steps -->
                        <div class="flex justify-between w-full relative z-10">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-primary-green rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-900">Pending</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-primary-green rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-900">Assigned</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-truck-moving"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-500">In Use</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-white mb-2">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <span class="text-xs font-medium text-gray-500">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Vehicle Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-car text-primary-green"></i>
                            Vehicle Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Plate Number:</span>
                                <span class="font-semibold text-gray-900" id="detailVehicle">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Model:</span>
                                <span class="text-gray-900" id="detailModel">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Type:</span>
                                <span class="text-gray-900" id="detailType">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Driver Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-user text-primary-green"></i>
                            Assigned Driver
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold text-gray-900" id="detailDriver">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">License:</span>
                                <span class="text-gray-900">N01-12-123456</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Contact:</span>
                                <span class="text-gray-900">0917-123-4567</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule & Trip Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-primary-green"></i>
                        Schedule & Trip Details
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-600">Date:</span>
                            <p class="text-sm font-semibold text-gray-900" id="detailDate">-</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-600">Time:</span>
                            <p class="text-sm font-semibold text-gray-900" id="detailTime">-</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-600">Duration:</span>
                            <p class="text-sm font-semibold text-gray-900" id="detailDuration">-</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-600">Status:</span>
                            <p class="text-sm"><span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800" id="detailStatus">-</span></p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-xs text-gray-600">Destination:</span>
                            <p class="text-sm font-semibold text-gray-900" id="detailDestination">-</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-xs text-gray-600">Purpose of Trip:</span>
                            <p class="text-sm text-gray-900" id="detailPurpose">-</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeDetailsModal()">
                        Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="markCompletedFromModal()">
                        <i class="fas fa-check-circle"></i> Mark as Completed
                    </button>
                    <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="cancelFromModal()">
                        <i class="fas fa-ban"></i> Cancel Reservation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Driver Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="assignModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Assign Driver to Reservation</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="assignForm" onsubmit="saveDriverAssignment(event)" class="p-6">
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-900"><strong>Reservation ID:</strong> <span id="assignReservationId">#RES-0001</span></p>
                    <p class="text-sm text-blue-900"><strong>Vehicle:</strong> <span id="assignVehicle">ABC-1234</span></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Driver *</label>
                    <select id="driverSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                        <option value="">Choose available driver</option>
                        <?php foreach (getAvailableDrivers() as $driver): ?>
                            <?php if ($driver['status'] === 'Available'): ?>
                            <option value="<?php echo $driver['name']; ?>"><?php echo $driver['name']; ?> - <?php echo $driver['license']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notify Driver</label>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="notifyDriver" checked class="w-4 h-4 text-primary-green border-gray-300 rounded focus:ring-primary-green">
                        <label for="notifyDriver" class="text-sm text-gray-700">Send automatic notification to assigned driver via SMS/Email</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Instructions</label>
                    <textarea id="instructions" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Any special instructions for the driver..." rows="3"></textarea>
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

    <!-- New Reservation Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="newReservationModal">
        <div class="bg-white rounded-lg w-11/12 max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Create New Reservation</h3>
                <button onclick="closeNewReservationModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="newReservationForm" onsubmit="saveNewReservation(event)" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle *</label>
                        <select id="newVehicle" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Vehicle</option>
                            <?php foreach (getVehicles() as $vehicle): ?>
                                <?php if ($vehicle['status'] === 'Active'): ?>
                                <option value="<?php echo $vehicle['plate']; ?>"><?php echo $vehicle['plate']; ?> - <?php echo $vehicle['model']; ?> (<?php echo $vehicle['type']; ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Assign Driver (Optional)</label>
                        <select id="newDriver" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            <option value="">Assign later</option>
                            <?php foreach (getAvailableDrivers() as $driver): ?>
                                <?php if ($driver['status'] === 'Available'): ?>
                                <option value="<?php echo $driver['name']; ?>"><?php echo $driver['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date *</label>
                        <input type="date" id="newDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Time *</label>
                        <input type="time" id="newTime" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Destination *</label>
                    <input type="text" id="newDestination" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="e.g., Quezon City Hall" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration *</label>
                        <select id="newDuration" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select duration</option>
                            <option value="1 hour">1 hour</option>
                            <option value="2 hours">2 hours</option>
                            <option value="3 hours">3 hours</option>
                            <option value="4 hours">4 hours</option>
                            <option value="5 hours">5 hours</option>
                            <option value="6 hours">6 hours</option>
                            <option value="8 hours">8 hours</option>
                            <option value="Full day">Full day</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Purpose *</label>
                        <select id="newPurpose" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select purpose</option>
                            <option value="Delivery">Delivery</option>
                            <option value="Pickup">Pickup</option>
                            <option value="Transport">Transport</option>
                            <option value="Client Meeting">Client Meeting</option>
                            <option value="Urgent Delivery">Urgent Delivery</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="newNotes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Additional information..." rows="3"></textarea>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeNewReservationModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Create Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="confirmModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Confirm Action</h3>
                        <p class="text-sm text-gray-600 mt-1" id="confirmMessage">Are you sure you want to proceed?</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeConfirmModal()">
                        Cancel
                    </button>
                    <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300" onclick="confirmAction()">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentReservationId = null;
        let currentAction = null;

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const typeFilter = document.getElementById('filterType').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[1].textContent.toLowerCase();
                const driver = row.cells[2].textContent.toLowerCase();
                const status = row.cells[5].textContent.trim();
                const type = row.cells[1].textContent;

                const matchesSearch = vehicle.includes(searchInput) || driver.includes(searchInput);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesType = !typeFilter || type.includes(typeFilter);

                row.style.display = matchesSearch && matchesStatus && matchesType ? '' : 'none';
            });
        }

        function viewDetails(id) {
            // In a real application, fetch data via AJAX
            <?php foreach ($reservations as $reservation): ?>
                if (id === <?php echo $reservation['id']; ?>) {
                    document.getElementById('detailVehicle').textContent = '<?php echo $reservation['vehicle']; ?>';
                    document.getElementById('detailModel').textContent = '<?php echo $reservation['model']; ?>';
                    document.getElementById('detailType').textContent = '<?php echo $reservation['type']; ?>';
                    document.getElementById('detailDriver').textContent = '<?php echo $reservation['driver'] ?? 'Not assigned'; ?>';
                    document.getElementById('detailDate').textContent = '<?php echo date('M d, Y', strtotime($reservation['date'])); ?>';
                    document.getElementById('detailTime').textContent = '<?php echo $reservation['time']; ?>';
                    document.getElementById('detailDuration').textContent = '<?php echo $reservation['duration']; ?>';
                    document.getElementById('detailDestination').textContent = '<?php echo $reservation['destination']; ?>';
                    document.getElementById('detailPurpose').textContent = '<?php echo $reservation['purpose']; ?>';
                    document.getElementById('detailStatus').textContent = '<?php echo $reservation['status']; ?>';
                }
            <?php endforeach; ?>
            
            currentReservationId = id;
            document.getElementById('detailsModal').classList.remove('hidden');
            document.getElementById('detailsModal').classList.add('flex');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.getElementById('detailsModal').classList.remove('flex');
        }

        function assignDriver(id) {
            currentReservationId = id;
            document.getElementById('assignReservationId').textContent = '#RES-' + String(id).padStart(4, '0');
            
            <?php foreach ($reservations as $reservation): ?>
                if (id === <?php echo $reservation['id']; ?>) {
                    document.getElementById('assignVehicle').textContent = '<?php echo $reservation['vehicle']; ?>';
                }
            <?php endforeach; ?>
            
            document.getElementById('assignModal').classList.remove('hidden');
            document.getElementById('assignModal').classList.add('flex');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
            document.getElementById('assignModal').classList.remove('flex');
            document.getElementById('assignForm').reset();
        }

        function saveDriverAssignment(event) {
            event.preventDefault();
            alert('Driver assigned successfully! Notification sent.');
            closeAssignModal();
            // Reload or update table here
            location.reload();
        }

        function markCompleted(id) {
            currentReservationId = id;
            currentAction = 'complete';
            document.getElementById('confirmMessage').textContent = 'Mark this reservation as completed?';
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function markCompletedFromModal() {
            closeDetailsModal();
            markCompleted(currentReservationId);
        }

        function cancelReservation(id) {
            currentReservationId = id;
            currentAction = 'cancel';
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to cancel this reservation? This action cannot be undone.';
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function cancelFromModal() {
            closeDetailsModal();
            cancelReservation(currentReservationId);
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
            currentAction = null;
        }

        function confirmAction() {
            if (currentAction === 'complete') {
                alert('Reservation marked as completed!');
            } else if (currentAction === 'cancel') {
                alert('Reservation cancelled successfully!');
            }
            closeConfirmModal();
            // Reload or update table here
            location.reload();
        }

        function openNewReservationModal() {
            document.getElementById('newReservationForm').reset();
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('newDate').value = today;
            document.getElementById('newReservationModal').classList.remove('hidden');
            document.getElementById('newReservationModal').classList.add('flex');
        }

        function closeNewReservationModal() {
            document.getElementById('newReservationModal').classList.add('hidden');
            document.getElementById('newReservationModal').classList.remove('flex');
        }

        function saveNewReservation(event) {
            event.preventDefault();
            alert('New reservation created successfully!');
            closeNewReservationModal();
            // Reload or update table here
            location.reload();
        }

        // Close modals when clicking outside
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetailsModal();
        });

        document.getElementById('assignModal').addEventListener('click', function(e) {
            if (e.target === this) closeAssignModal();
        });

        document.getElementById('newReservationModal').addEventListener('click', function(e) {
            if (e.target === this) closeNewReservationModal();
        });

        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) closeConfirmModal();
        });
    </script>
</body>
</html>