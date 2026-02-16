<?php
include '../includes/functions.php';

// 1️⃣ Get LOCAL maintenance records
$maintenance = getMaintenanceRecords(); // function returns all maintenance records

// 2️⃣ Handle new LOCAL maintenance requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['schedule_maintenance'])) {
        $vehicleId = intval($_POST['vehicle']);
        addMaintenance([
            'vehicle_id' => $vehicleId,
            'type' => $_POST['type'],
            'date' => $_POST['date'],
            'priority' => $_POST['priority'],
            'status' => 'Pending',
            'notes' => $_POST['notes'] ?? ''
        ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Mark maintenance complete
    if (isset($_POST['complete_id'])) {
        completeMaintenance(intval($_POST['complete_id']));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// 3️⃣ Fetch API data
$api_url = "https://log1.microfinancial-1.com/api/v1/alms/external/maintenance?key=63cfb7730dcc34299fa38cb1a620f701";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// 4️⃣ Insert or update API records
if (isset($data['data']) && !empty($data['data'])) {

    foreach ($data['data'] as $item) {

        // Extract plate and vehicle name
        $vehicle_full = $item['mnt_asset_name']; // e.g., "Honda CB500 - HON-500"
        if (strpos($vehicle_full, ' - ') !== false) {
            [$vehicle_name, $plate] = explode(' - ', $vehicle_full);
        } else {
            $vehicle_name = $vehicle_full;
            $plate = null;
        }

        // Get vehicle_id from plate
        $vehicle_id = getVehicleIdByPlate($plate);
        if (!$vehicle_id) continue; // skip if vehicle doesn't exist in your vehicles table

        // Prepare data
        $external_id = intval($item['mnt_id']);
        $type = $conn->real_escape_string($item['mnt_type']);
        $date = $conn->real_escape_string($item['mnt_scheduled_date']);
        $status = ucfirst(str_replace('_', ' ', $item['mnt_status']));
        $priority = ucfirst($item['mnt_priority']);
        $notes = $conn->real_escape_string(
            trim(
                ($item['rp_position'] ?? '') . ' - ' .
                ($item['rp_firstname'] ?? '') . ' ' .
                ($item['rp_lastname'] ?? '')
            )
        );

        // Insert or update
        $sql = "
            INSERT INTO maintenance 
            (external_id, vehicle_id, type, date, status, priority, notes, source)
            VALUES
            ('$external_id', '$vehicle_id', '$type', '$date', '$status', '$priority', '$notes', 'LOG1')
            ON DUPLICATE KEY UPDATE
                vehicle_id = VALUES(vehicle_id),
                type = VALUES(type),
                date = VALUES(date),
                status = VALUES(status),
                priority = VALUES(priority),
                notes = VALUES(notes),
                source = 'LOG1'
        ";
        $conn->query($sql);
    }

    // Refresh maintenance after sync
    $maintenance = getMaintenanceRecords();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Tracker - Logistics Admin</title>
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
            <!-- Optional Subtitle -->
            <div class="mb-6">
                <p class="text-gray-600">Monitor and manage vehicle maintenance activities</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Maintenance</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($maintenance); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-info-circle"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wrench text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Completed</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($maintenance, fn($m) => $m['status'] === 'Completed')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-up"></i> On schedule
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">In Progress</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($maintenance, fn($m) => $m['status'] === 'In Progress')); ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-spinner"></i> Ongoing
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Maintenance Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900">Maintenance Records</h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3">
                                <input type="text" id="searchInput" placeholder="Search by vehicle or type..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterStatus" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Status</option>
                                    <option value="Completed">Completed</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="openScheduleModal()">
                                <i class="fas fa-plus"></i> Request Maintenance
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="maintenanceTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
<th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle Number</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Maintenance Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Priority</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Notes</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($maintenance as $record): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
<td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $record['plate']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $record['type']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($record['date'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php 
                                        echo $record['status'] === 'Completed' ? 'bg-green-100 text-green-800' : 
                                            ($record['status'] === 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'); 
                                    ?>">
                                        <?php echo $record['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        <?php
                                            echo $record['priority'] === 'High' ? 'bg-red-100 text-red-800' :
                                                ($record['priority'] === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-green-100 text-green-800');
                                        ?>">
                                        <?php echo $record['priority']; ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-700 font-medium">
                                    <?php echo htmlspecialchars($record['notes']); ?>
                                </td>

                            
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

<!-- Request Maintenance Modal -->
<div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="scheduleModal">
    <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-8">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6">
            <span class="text-xl font-bold text-gray-900">Request Maintenance</span>
            <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Form -->
        <form id="scheduleForm" method="POST">
            <input type="hidden" name="schedule_maintenance" value="1">

            <!-- Vehicle -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle *</label>
                <select name="vehicle" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    <option value="">Select Vehicle</option>
                    <?php foreach (getVehicles() as $vehicle): ?>
                        <option value="<?php echo $vehicle['id']; ?>">
                            <?php echo $vehicle['plate'] . ' - ' . $vehicle['vehicle']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Maintenance Type -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Maintenance Type *</label>
                <select name="type" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="Oil Change">Oil Change</option>
                    <option value="Tire Replacement">Tire Replacement</option>
                    <option value="Engine Inspection">Engine Inspection</option>
                    <option value="Brake Service">Brake Service</option>
                    <option value="Battery Replacement">Battery Replacement</option>
                    <option value="AC Service">AC Service</option>
                </select>
            </div>

            <!-- Priority Level -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Priority Level *
                </label>
                <select name="priority" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                        focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    <option value="">Select Priority</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <!-- Date & Cost -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Scheduled Date *</label>
                    <input type="date" name="date" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                               focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                </div>

            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" placeholder="Additional notes..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                           focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold
                           hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Request Maintenance
                </button>

                <button type="button"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold
                           hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2"
                    onclick="closeScheduleModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[0].textContent.toLowerCase();
                const type = row.cells[1].textContent.toLowerCase();
                const status = row.cells[3].textContent.trim();

                const matchesSearch = vehicle.includes(searchInput) || type.includes(searchInput);
                const matchesStatus = !statusFilter || status.includes(statusFilter);

                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        function openScheduleModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
            document.getElementById('scheduleModal').classList.add('flex');
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
            document.getElementById('scheduleModal').classList.remove('flex');
            document.getElementById('scheduleForm').reset();
        }

        function saveMaintenance(event) {
            event.preventDefault();
            alert('Maintenance scheduled successfully!');
            closeScheduleModal();
            // Reload page or update table here
        }

        function markComplete(id) {
            if (confirm('Mark this maintenance as complete?')) {
                alert('Maintenance marked as complete!');
                // Update status via AJAX here
                location.reload();
            }
        }

        // Close modal when clicking outside
        document.getElementById('scheduleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeScheduleModal();
            }
        });
    </script>
</body>
</html>