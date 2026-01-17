<?php
include '../includes/functions.php';
$approvals = getApprovals();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Approvals - Logistics Admin</title>
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
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Maintenance Approvals</h2>
                <p class="text-gray-600 mt-2">Review and approve maintenance requests</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Requests -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Requests</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($approvals); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-clock"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Pending</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($approvals, fn($a) => $a['status'] === 'Pending')); ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-hourglass-half"></i> Needs action
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Approved</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($approvals, fn($a) => $a['status'] === 'Approved')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-double text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Budget -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Budget</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">₱<?php echo number_format(array_sum(array_column($approvals, 'amount'))); ?></div>
                            <div class="text-xs font-medium text-gray-600">
                                <i class="fas fa-peso-sign"></i> All requests
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approvals Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Maintenance Approval Requests</h2>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center gap-2">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Search & Filter -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex gap-3">
                        <input type="text" id="searchInput" placeholder="Search by vehicle or type..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        <select id="statusFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                        <select id="typeFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Repair">Repair</option>
                            <option value="Inspection">Inspection</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="approvalsTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Request ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Amount</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Request Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($approvals as $approval): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm text-gray-700"><strong>#<?php echo str_pad($approval['id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-car text-gray-400"></i>
                                        <strong><?php echo $approval['vehicle']; ?></strong>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $approval['type']; ?></td>
                                <td class="px-5 py-4 text-sm font-semibold text-gray-900">₱<?php echo number_format($approval['amount']); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php 
                                        echo $approval['status'] === 'Approved' ? 'bg-green-100 text-green-800' : 
                                            ($approval['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); 
                                    ?>">
                                        <?php echo $approval['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-500"><?php echo date('M d, Y', strtotime($approval['date'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="viewDetails(<?php echo $approval['id']; ?>)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <?php if ($approval['status'] === 'Pending'): ?>
                                        <button class="px-3 py-1.5 bg-green-500 text-white rounded-md text-xs font-semibold hover:bg-green-600 transition-all inline-flex items-center gap-1.5" onclick="approveRequest(<?php echo $approval['id']; ?>)">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 transition-all inline-flex items-center gap-1.5" onclick="rejectRequest(<?php echo $approval['id']; ?>)">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing <strong>1-<?php echo count($approvals); ?></strong> of <strong><?php echo count($approvals); ?></strong> results
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <button class="px-3 py-1.5 bg-primary-green text-white rounded-md text-sm font-semibold">1</button>
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">2</button>
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Details Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="detailsModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold text-gray-900">Request Details</span>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Request ID</label>
                        <div class="text-sm text-gray-900">#0001</div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Vehicle</label>
                        <div class="text-sm text-gray-900">ABC-1234</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                        <div class="text-sm text-gray-900">Maintenance</div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Amount</label>
                        <div class="text-sm font-semibold text-gray-900">₱5,000</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">
                        Regular maintenance service including oil change, filter replacement, and general inspection.
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Requested By</label>
                    <div class="text-sm text-gray-900">John Doe - Fleet Manager</div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Priority</label>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                        Medium
                    </span>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button class="flex-1 px-4 py-2 bg-green-500 text-white rounded-md text-sm font-semibold hover:bg-green-600 transition-all duration-300 inline-flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> Approve Request
                </button>
                <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Reject Request
                </button>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="approveModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold text-gray-900">Confirm Approval</span>
                <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6 text-gray-700">
                <p>Are you sure you want to approve this maintenance request?</p>
                <div class="mt-4 p-4 bg-green-50 rounded-lg">
                    <div class="text-sm font-semibold text-gray-700">Request Details:</div>
                    <div class="text-sm text-gray-600 mt-1" id="approveDetails">Vehicle: ABC-1234 | Amount: ₱5,000</div>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="flex-1 px-4 py-2 bg-green-500 text-white rounded-md text-sm font-semibold hover:bg-green-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="confirmApproval()">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeApproveModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="rejectModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <span class="text-xl font-bold text-gray-900">Confirm Rejection</span>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-6">
                <p class="text-gray-700 mb-4">Are you sure you want to reject this maintenance request?</p>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Rejection *</label>
                <textarea id="rejectionReason" rows="3" placeholder="Please provide a reason..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent"></textarea>
            </div>
            <div class="flex gap-3">
                <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="confirmRejection()">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeRejectModal()">
                    <i class="fas fa-ban"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentRequestId = null;

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[1].textContent.toLowerCase();
                const type = row.cells[2].textContent;
                const status = row.cells[4].textContent.trim();

                const matchesSearch = vehicle.includes(searchInput) || type.toLowerCase().includes(searchInput);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesType = !typeFilter || type === typeFilter;

                row.style.display = matchesSearch && matchesStatus && matchesType ? '' : 'none';
            });
        }

        function viewDetails(id) {
            currentRequestId = id;
            document.getElementById('detailsModal').classList.remove('hidden');
            document.getElementById('detailsModal').classList.add('flex');
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.getElementById('detailsModal').classList.remove('flex');
        }

        function approveRequest(id) {
            currentRequestId = id;
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }

        function confirmApproval() {
            alert('Request #' + currentRequestId + ' approved successfully!');
            closeApproveModal();
            location.reload();
        }

        function rejectRequest(id) {
            currentRequestId = id;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('rejectionReason').value = '';
        }

        function confirmRejection() {
            const reason = document.getElementById('rejectionReason').value;
            if (!reason.trim()) {
                alert('Please provide a reason for rejection');
                return;
            }
            alert('Request #' + currentRequestId + ' rejected successfully!');
            closeRejectModal();
            location.reload();
        }

        // Close modals when clicking outside
        document.getElementById('detailsModal').addEventListener('click', function(e) {
            if (e.target === this) closeDetailsModal();
        });

        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) closeApproveModal();
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
</body>
</html>