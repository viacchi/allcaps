<?php
include '../includes/functions.php';


// Existing expenses from DB
$expenses = getTransportExpenses();

// Fetch Request Expenses from Log 1 API
$requestExpensesJson = file_get_contents('https://log2.microfinancial-1.com/api/request_expenses.php');
$requestExpenses = json_decode($requestExpensesJson, true) ?? [];

// Logged-in user info
$loggedInUser = $_SESSION['user_name'] ?? '';
$department = 'Logistic 2'; // fixed value

// Fetch drivers and vehicles
$drivers  = getDrivers();
$vehicles = getVehicles();
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

    <main class="p-6">
        <div class="mb-6">
            <p class="text-gray-600">Monitor fuel consumption and operational expenses</p>
        </div>

        <!-- KPI Cards -->

        <!-- Fuel Expense Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-lg font-bold text-gray-900">Records</h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition inline-flex items-center gap-2" onclick="openEntryModal()">
                            <i class="fas fa-file-invoice"></i> Request Expense
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="expenseTable">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Amount</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Requested By</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php foreach ($expenses as $expense): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-sm text-gray-700 font-medium">₱<?= number_format($expense['amount'] ?? 0, 2); ?></td>
                            <td class="px-5 py-4 text-sm text-gray-700"><?= htmlspecialchars($expense['requested_by'] ?? '-'); ?></td>
                            <td class="px-5 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                <?php
                                echo match($expense['status'] ?? '') {
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Approved' => 'bg-green-100 text-green-800',
                                    'Rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                ?>">
                                <?= htmlspecialchars($expense['status'] ?? '-'); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Request Expenses Table -->
        </div>

    </main>
</div>

<!-- JS -->
<script>
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const vehicleFilter = document.getElementById('filterVehicle').value;

    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach(row => {
        const vehicle = row.cells[0].textContent.toLowerCase();
        const driver = row.cells[4].textContent.toLowerCase();
        const matchesSearch = vehicle.includes(searchInput) || driver.includes(searchInput);
        const matchesVehicle = !vehicleFilter || row.cells[0].textContent === vehicleFilter;
        row.style.display = matchesSearch && matchesVehicle ? '' : 'none';
    });
}

function viewExpense(expense) {
    document.getElementById('viewVehicle').textContent = expense.vehicle ?? '-';
    document.getElementById('viewDate').textContent = expense.request_date ? new Date(expense.request_date).toLocaleDateString() : '-';
    document.getElementById('viewLiters').textContent = expense.liters ? expense.liters + ' L' : '-';
    document.getElementById('viewCost').textContent = '₱' + (expense.amount ?? 0).toFixed(2);
    document.getElementById('viewCostPerLiter').textContent = expense.liters ? '₱' + ((expense.amount ?? 0) / expense.liters).toFixed(2) : '-';
    document.getElementById('viewDriver').textContent = expense.driver ?? '-';
    document.getElementById('viewFuelType').textContent = expense.fuel_type ?? '-';

    const viewReceipt = document.getElementById('viewReceipt');
    if (expense.receipt_path) {
        viewReceipt.innerHTML = `<a href="../${expense.receipt_path}" target="_blank" class="text-blue-600 underline">View Receipt</a>`;
    } else {
        viewReceipt.innerHTML = '<em>No file uploaded</em>';
    }

    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}
</script>
</body>
</html>
