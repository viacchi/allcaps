<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1️⃣ Get POST data safely
    $vehicle_id = isset($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null;
    $date       = isset($_POST['date']) ? $_POST['date'] : null;
    $liters     = isset($_POST['liters']) ? floatval($_POST['liters']) : 0;
    $cost       = isset($_POST['cost']) ? floatval($_POST['cost']) : 0;
    $driver_id  = isset($_POST['driver_id']) ? intval($_POST['driver_id']) : null;

    // 2️⃣ Validate required fields
    $errors = [];
    if (!$vehicle_id) $errors[] = "Vehicle is required.";
    if (!$date) $errors[] = "Date is required.";
    if ($liters <= 0) $errors[] = "Liters must be greater than 0.";
    if ($cost <= 0) $errors[] = "Cost must be greater than 0.";
    if (!$driver_id) $errors[] = "Driver is required.";

    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo "<p style='color:red;'>Error: $err</p>";
        }
        exit;
    }

    // 3️⃣ Handle file upload
    $receipt_path = null;
    if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === 0) {
        $uploadDir = '../uploads/fuel_receipts/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileExt = pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . rand(1000,9999) . '.' . $fileExt;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $targetFile)) {
            $receipt_path = 'uploads/fuel_receipts/' . $fileName; // relative path
    }

    // 4️⃣ Insert into DB
    $sql = "INSERT INTO fuel_expenses (vehicle_id, date, liters, cost, driver_id, receipt_path)
            VALUES (?, ?, ?, ?, ?, ?)";

    global $conn; // make sure $conn exists in functions.php

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isddis", $vehicle_id, $date, $liters, $cost, $driver_id, $receipt_path);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect after successful insert
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
}
// Fetch expenses for table
$expenses = getFuelExpenses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel & Expense Records - Logistics Admin</title>
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
                <p class="text-gray-600">Monitor fuel consumption and operational expenses</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Fuel Expenses</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">₱<?php echo number_format(array_sum(array_column($expenses, 'cost'))); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-calendar"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-gas-pump text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Liters</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo array_sum(array_column($expenses, 'liters')); ?> L</div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-up"></i> 5.2% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tint text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Cost per Liter</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">₱<?php 
                                $totalCost = array_sum(array_column($expenses, 'cost'));
                                $totalLiters = array_sum(array_column($expenses, 'liters'));
                                echo $totalLiters > 0 ? number_format($totalCost / $totalLiters, 2) : '0.00';
                            ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-arrow-up"></i> 3.1% increase
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Records</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($expenses); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> All verified
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fuel Expense Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py
                -4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900">Fuel & Expense Records</h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3">
                                <input type="text" id="searchInput" placeholder="Search by vehicle or driver..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterVehicle" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Vehicles</option>
                                    <?php 
                                    $uniqueVehicles = array_unique(array_column($expenses, 'vehicle'));
                                    foreach ($uniqueVehicles as $vehicle): 
                                    ?>
                                    <option value="<?php echo $vehicle; ?>"><?php echo $vehicle; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="openAddExpenseModal()">
                                <i class="fas fa-plus"></i> Add Expense
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="expenseTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Liters</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Cost</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Cost/Liter</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($expenses as $expense): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $expense['vehicle']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($expense['date'])); ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo number_format($expense['liters'], 2); ?> L</td>
                                <td class="px-5 py-4 text-sm text-gray-700 font-medium">₱<?php echo number_format($expense['cost'], 2); ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700">₱<?php echo number_format($expense['cost'] / $expense['liters'], 2); ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $expense['driver']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
<button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" 
    onclick='viewExpense(<?= json_encode($expense) ?>)'>
    <i class="fas fa-eye"></i> View
</button>
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="editExpense(<?php echo $expense['id']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
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

    <!-- Add/Edit Expense Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="expenseModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Add Fuel Expense</h3>
                <button onclick="closeExpenseModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="expenseForm" method="POST" enctype="multipart/form-data" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle *</label>
                    <select name="vehicle_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" required>
                        <option value="">Select Vehicle</option>
                        <?php foreach (getVehicles() as $vehicle): ?>
                            <option value="<?= $vehicle['id'] ?>">
                                <?= $vehicle['plate'] ?> - <?= $vehicle['model'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date *</label>
                        <input name="date" type="date" id="expenseDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Liters *</label>
                        <input name="liters" type="number" id="liters" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="0.00" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Total Cost (₱) *</label>
                        <input name="cost" type="number" id="totalCost" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Driver *</label>
                    <select name="driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" required>
                        <option value="">Select Driver</option>
                        <?php foreach (getDrivers() as $driver): ?>
                            <option value="<?= $driver['user_id'] ?>">
                                <?= $driver['full_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gas Station</label>
                    <input type="text" id="gasStation" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="e.g., Petron, Shell">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Receipt/Invoice Number</label>
                    <input type="text" id="receiptNumber" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Receipt number">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Receipt</label>
                    <input type="file" name="receipt_file" id="receiptFile" accept="image/*,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Additional notes..." rows="3"></textarea>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeExpenseModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Expense Details Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="viewModal">
        <div class="bg-white rounded-lg w-11/12 max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Expense Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Vehicle:</span>
                        <span class="text-sm text-gray-900 font-semibold" id="viewVehicle">-</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Date:</span>
                        <span class="text-sm text-gray-900" id="viewDate">-</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Liters:</span>
                        <span class="text-sm text-gray-900" id="viewLiters">-</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Total Cost:</span>
                        <span class="text-sm text-gray-900 font-bold" id="viewCost">-</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Cost per Liter:</span>
                        <span class="text-sm text-gray-900" id="viewCostPerLiter">-</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Driver:</span>
                        <span class="text-sm text-gray-900" id="viewDriver">-</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Status:</span>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Verified</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-200">
                        <span class="text-sm font-semibold text-gray-600">Receipt:</span><br>
                        <span id="viewReceipt">
                            <!-- The image or PDF link will go here -->
                            <em>No file uploaded</em>
                        </span>
                    </div>

                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <button class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeViewModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const vehicleFilter = document.getElementById('filterVehicle').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[0].textContent.toLowerCase();
                const driver = row.cells[5].textContent.toLowerCase();

                const matchesSearch = vehicle.includes(searchInput) || driver.includes(searchInput);
                const matchesVehicle = !vehicleFilter || row.cells[0].textContent === vehicleFilter;

                row.style.display = matchesSearch && matchesVehicle ? '' : 'none';
            });
        }

        function openAddExpenseModal() {
            document.getElementById('modalTitle').textContent = 'Add Fuel Expense';
            document.getElementById('expenseForm').reset();
            document.getElementById('expenseModal').classList.remove('hidden');
            document.getElementById('expenseModal').classList.add('flex');
        }

        function closeExpenseModal() {
            document.getElementById('expenseModal').classList.add('hidden');
            document.getElementById('expenseModal').classList.remove('flex');
            document.getElementById('expenseForm').reset();
        }


function viewExpense(expense) {
    document.getElementById('viewVehicle').textContent = expense.vehicle;
    document.getElementById('viewDate').textContent = new Date(expense.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('viewLiters').textContent = parseFloat(expense.liters).toFixed(2) + ' L';
    document.getElementById('viewCost').textContent = '₱' + parseFloat(expense.cost).toFixed(2);
    document.getElementById('viewCostPerLiter').textContent = '₱' + (expense.liters > 0 ? (expense.cost / expense.liters).toFixed(2) : '0.00');
    document.getElementById('viewDriver').textContent = expense.driver;

    const viewReceipt = document.getElementById('viewReceipt');
    if (expense.receipt_path) {
        if (/\.(jpg|jpeg|png|gif)$/i.test(expense.receipt_path)) {
            viewReceipt.innerHTML = `<img src="../${expense.receipt_path}" alt="Receipt" class="max-w-full max-h-64 rounded border">`;
        } else if (/\.(pdf)$/i.test(expense.receipt_path)) {
            viewReceipt.innerHTML = `<a href="../${expense.receipt_path}" target="_blank" class="text-blue-600 underline">View PDF</a>`;
        } else {
            viewReceipt.textContent = 'File uploaded';
        }
    } else {
        viewReceipt.innerHTML = '<em>No file uploaded</em>';
    }

    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        function editExpense(id) {
            document.getElementById('modalTitle').textContent = 'Edit Fuel Expense';
            // In a real application, fetch expense details and populate form
            document.getElementById('expenseModal').classList.remove('hidden');
            document.getElementById('expenseModal').classList.add('flex');
        }

        // Close modals when clicking outside
        document.getElementById('expenseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeExpenseModal();
            }
        });

        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });
    </script>
</body>
</html>