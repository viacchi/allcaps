<?php
include '../staff-includes/staff-functions.php';
$assigned = getAssigned();
$total = count($assigned);
$active = count(array_filter($assigned, fn($v) => $v['status'] === 'Active'));
$maintenance = count(array_filter($assigned, fn($v) => $v['status'] === 'Maintenance'));
$inactive = count(array_filter($assigned, fn($v) => $v['status'] === 'Inactive'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Vehicle - Logistics Staff</title>
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
    <!-- Sidebar --> <?php include '../staff-includes/staff-sidebar.php'; ?>
    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen">
        <!-- Header --> <?php include '../staff-includes/staff-header.php'; ?>
        <!-- Page Content -->
        <main class="p-6">
            <div class="mb-8">
                <p class="text-gray-600 mt-2">Display assigned vehicles for the staff's department</p>
            </div>
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Assigned Vehicles -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Assigned Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $total; ?></div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-car text-green-600 text-xl"></i>
                        </div>
                    </div>
                     <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-car"></i> In fleet
                    </div>
                </div>
                <!-- Active Vehicles -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Available Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $active; ?></div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-blue-600">
                        <i class="fas fa-check-circle"></i> Ready to dispatch
                    </div>
                </div>
                <!-- Vehicles Under Maintenance -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Under Mainntenance</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $maintenance; ?></div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-wrench text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                     <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-search"></i> Maintenance
                    </div>
                </div>
            </div>

            <!-- Assigned Vehicle Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Assigned Vehicle </h2>
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
                            <option value="Available">Available</option>
                            <option value="Inuse">In Use</option>
                        </select>
                    </div>
                </div>
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="AssignedTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Plate Number</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Model</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Assigned Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Last Maintenance</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Remarks</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"> <?php foreach ($assigned as $assigned): ?> <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm text-gray-700"><strong><?php echo $assigned['plate']; ?></strong></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $assigned['model']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $assigned['type']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($assigned['assigned_date'])); ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($assigned['last_maintenance'])); ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $assigned['remarks']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick='viewAssignedDetail(<?php echo json_encode($assigned); ?>)'>
                                            <i class="fas fa-eye"></i> View Details </button>
                                    </div>
                                </td>
                            </tr> <?php endforeach; ?> </tbody>
                    </table>
                </div>
                <!-- Assigned Detail Modal -->
                <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" id="assignedModal">
                    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[100vh] overflow-y-auto shadow-2xl">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
                            <h3 class="text-xl font-bold">Assigned Details</h3>
                            <button onclick="closeAssignedModal()" class="text-white hover:text-gray-200 text-2xl">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <!-- Body -->
                        <div class="p-6 space-y-6">
                            <!-- Vehicle Information -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-black-700 mb-3">Vehicle Information</h4>
                                <div class="space-y-2">
                                    <div class=" text-sm">
                                        <span class="text-gray-600">Plate Number:</span>
                                        <span class="font-semibold text-gray-900 ml-2" id="detailPLateNumber">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Model:</span>
                                        <span class="font-semibold text-gray-900 ml-2" id="detailModel">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Type:</span>
                                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold" id="detailType">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Year:</span>
                                        <span class="font-bold text-gray-900 ml-2" id="detailYear">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="font-bold text-gray-900 ml-2" id="detailStatus">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Assigned Date:</span>
                                        <span class="font-bold text-gray-900 ml-2 " id="detailDate">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Last Maintenance:</span>
                                        <span class="font-bold text-gray-900 ml-2 " id="detailMaintenance">-</span>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-600">Remarks:</span>
                                        <span class="font-bold text-gray-900 ml-2" id="detailRemarks">-</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Buttons -->
                            <div class="flex gap-3 mt-4">
                                <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-circle-check"></i> Acknowledge </button>
                                <button class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-semibold hover:bg-yellow-600 transition-all duration-300 inline-flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-arrows-rotate"></i> Request Change </button>
                                <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeAssignedModal()">
                                    <i class="fa-solid fa-xmark"></i> Close </button>
                            </div>
                        </div>
        </main>
    </div>
    <script>
        //FILTER TABLE FUNCTIONALITY
        function filterTable() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            const typeFilter = document.getElementById("typeFilter").value;
            const statusFilter = document.getElementById("statusFilter").value;
            const rows = document.querySelectorAll("#AssignedTable tbody tr");
            rows.forEach(row => {
                const plate = row.cells[0].textContent.toLowerCase();
                const model = row.cells[1].textContent.toLowerCase();
                const type = row.cells[2].textContent;
                const status = row.cells[5]?.textContent || ''; // optional
                const matchesSearch = plate.includes(searchInput) || model.includes(searchInput);
                const matchesType = !typeFilter || type === typeFilter;
                const matchesStatus = !statusFilter || status === statusFilter;
                if (matchesSearch && matchesType && matchesStatus) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
        // OPEN MODAL WITH VEHICLE DETAILS
        function viewAssignedDetail(vehicle) {
            // Fill modal with data
            document.getElementById("detailPLateNumber").textContent = vehicle.plate || '-';
            document.getElementById("detailModel").textContent = vehicle.model || '-';
            document.getElementById("detailType").textContent = vehicle.type || '-';
            document.getElementById("detailYear").textContent = vehicle.year || '-';
            document.getElementById("detailStatus").textContent = vehicle.status || '-';
            document.getElementById("detailDate").textContent = vehicle.assigned_date ? formatDate(vehicle.assigned_date) : '-';
            document.getElementById("detailMaintenance").textContent = vehicle.last_maintenance ? formatDate(vehicle.last_maintenance) : '-';
            document.getElementById("detailRemarks").textContent = vehicle.remarks || '-';
            // Change the color of type badge dynamically
            const typeBadge = document.getElementById("detailType");
            typeBadge.classList.remove("bg-green-100", "bg-blue-100", "bg-yellow-100", "text-green-800", "text-blue-800", "text-yellow-800");
            switch (vehicle.type) {
                case "Truck":
                    typeBadge.classList.add("bg-green-100", "text-green-800");
                    break;
                case "Van":
                    typeBadge.classList.add("bg-blue-100", "text-blue-800");
                    break;
                case "Car":
                    typeBadge.classList.add("bg-yellow-100", "text-yellow-800");
                    break;
                default:
                    typeBadge.classList.add("bg-gray-100", "text-gray-800");
            }
            // Show modal
            document.getElementById("assignedModal").classList.remove("hidden");
            document.getElementById("assignedModal").classList.add("flex");
        }
        // CLOSE MODAL
        function closeAssignedModal() {
            document.getElementById("assignedModal").classList.remove("flex");
            document.getElementById("assignedModal").classList.add("hidden");
        }
        // DATE FORMATTER
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const options = {
                month: 'short',
                day: '2-digit',
                year: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }
    </script>
</body>

</html>