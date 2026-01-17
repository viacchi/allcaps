<?php
include '../includes/functions.php';
$vehicles = getVehicles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Registry - Logistics Admin</title>
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
    <div class="ml-0 md:ml-[280px] min-h-screen">
        <!-- Header -->
        <?php include '../includes/header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <div class="mb-8">
                <p class="text-gray-600 mt-2">Manage all registered vehicles in your fleet</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($vehicles); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-up"></i> 4.5% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-car text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Active Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($vehicles, fn($v) => $v['status'] === 'Active')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-up"></i> 2.1% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Maintenance Due</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($vehicles, fn($v) => $v['status'] === 'Maintenance')); ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-arrow-up"></i> 0.8% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Inactive Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($vehicles, fn($v) => $v['status'] === 'Inactive')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-down"></i> 1.2% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-ban text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Registry Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Vehicle Registry</h2>
                    <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center gap-2" onclick="openAddVehicleModal()">
                        <i class="fas fa-plus"></i> Add Vehicle
                    </button>
                </div>

                <!-- Search & Filter -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex gap-3">
                        <input type="text" id="searchInput" placeholder="Search by plate number, model..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">
                        <select id="typeFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Types</option>
                            <option value="Truck">Truck</option>
                            <option value="Van">Van</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Car">Car</option>
                        </select>
                        <select id="statusFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="vehicleTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Plate Number</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Model</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Year</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Last Maintenance</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($vehicles as $vehicle): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm text-gray-700"><strong><?php echo $vehicle['plate']; ?></strong></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $vehicle['model']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $vehicle['type']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $vehicle['year']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php 
                                        echo $vehicle['status'] === 'Active' ? 'bg-green-100 text-green-800' : 
                                            ($vehicle['status'] === 'Maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); 
                                    ?>">
                                        <?php echo $vehicle['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($vehicle['last_maintenance'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="editVehicle(<?php echo $vehicle['id']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 transition-all inline-flex items-center gap-1.5" onclick="confirmDeactivateVehicle(<?php echo $vehicle['id']; ?>)">
                                            <i class="fas fa-ban"></i> Deactivate
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

    <!-- Add/Edit Vehicle Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="vehicleModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold text-gray-900" id="modalTitle">Add New Vehicle</span>
                <button onclick="closeVehicleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="vehicleForm" onsubmit="saveVehicle(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Plate Number *</label>
                    <input type="text" id="plateNumber" required placeholder="e.g., ABC-1234" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Model *</label>
                        <input type="text" id="model" required placeholder="e.g., Toyota Hiace" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle Type *</label>
                        <select id="vehicleType" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="Truck">Truck</option>
                            <option value="Van">Van</option>
                            <option value="Motorcycle">Motorcycle</option>
                            <option value="Car">Car</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Year *</label>
                        <input type="number" id="year" required placeholder="e.g., 2023" min="1990" max="2099" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                        <select id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Maintenance Date</label>
                        <input type="date" id="lastMaintenance" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle Image</label>
                    <input type="file" id="vehicleImage" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" placeholder="Additional notes..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent"></textarea>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Save Vehicle
                    </button>
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeVehicleModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deactivate Confirmation Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="confirmModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold text-gray-900">Confirm Action</span>
                <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6 text-gray-700">
                <p id="confirmMessage">Are you sure you want to deactivate this vehicle?</p>
            </div>
            <div class="flex gap-3">
                <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="confirmDeactivate()">
                    <i class="fas fa-check"></i> Confirm
                </button>
                <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeConfirmModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Set active page title
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('pageTitle').textContent = 'Vehicle Registry';
        });

        let editingId = null;
        let deactivatingId = null;

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const plate = row.cells[0].textContent.toLowerCase();
                const model = row.cells[1].textContent.toLowerCase();
                const type = row.cells[2].textContent;
                const status = row.cells[4].textContent.trim();

                const matchesSearch = plate.includes(searchInput) || model.includes(searchInput);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesStatus = !statusFilter || status === statusFilter;

                row.style.display = matchesSearch && matchesType && matchesStatus ? '' : 'none';
            });
        }

        function openAddVehicleModal() {
            editingId = null;
            document.getElementById('modalTitle').textContent = 'Add New Vehicle';
            document.getElementById('vehicleForm').reset();
            document.getElementById('vehicleModal').classList.remove('hidden');
            document.getElementById('vehicleModal').classList.add('flex');
        }

        function editVehicle(id) {
            editingId = id;
            document.getElementById('modalTitle').textContent = 'Edit Vehicle';
            // You would populate the form with vehicle data here
            document.getElementById('vehicleModal').classList.remove('hidden');
            document.getElementById('vehicleModal').classList.add('flex');
        }

        function closeVehicleModal() {
            document.getElementById('vehicleModal').classList.add('hidden');
            document.getElementById('vehicleModal').classList.remove('flex');
            editingId = null;
        }

        function saveVehicle(event) {
            event.preventDefault();
            
            // Here you would save the vehicle data
            alert(editingId ? 'Vehicle updated successfully!' : 'Vehicle added successfully!');
            closeVehicleModal();
            // Reload or update table here
        }

        function confirmDeactivateVehicle(id) {
            deactivatingId = id;
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to deactivate this vehicle?';
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function confirmDeactivate() {
            // Here you would deactivate the vehicle
            alert('Vehicle deactivated successfully!');
            closeConfirmModal();
            // Reload or update table here
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
            deactivatingId = null;
        }

        // Close modal when clicking outside
        document.getElementById('vehicleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVehicleModal();
            }
        });

        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });
    </script>
</body>
</html>