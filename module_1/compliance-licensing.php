<?php
include '../includes/functions.php';
$compliance = getComplianceRecords();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance & Licensing - Logistics Admin</title>
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
                <p class="text-gray-600">Manage vehicle documents and compliance records</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Documents</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($compliance); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-file-alt"></i> All records
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-contract text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Valid Documents</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($compliance, fn($c) => $c['status'] === 'Valid')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> Up to date
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-double text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Expiring Soon</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($compliance, fn($c) => $c['status'] === 'Expiring Soon')); ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-exclamation-triangle"></i> Needs attention
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Expired</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($compliance, fn($c) => $c['status'] === 'Expired')); ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-times-circle"></i> Immediate action
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-ban text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compliance Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900">Compliance & Licensing Records</h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3">
                                <input type="text" id="searchInput" placeholder="Search by vehicle or document..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterStatus" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Status</option>
                                    <option value="Valid">Valid</option>
                                    <option value="Expiring Soon">Expiring Soon</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="openUploadModal()">
                                <i class="fas fa-upload"></i> Upload Document
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="complianceTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Document Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Issue Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Expiry Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Days Remaining</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($compliance as $record): 
                                $expiryDate = new DateTime($record['expiry']);
                                $today = new DateTime();
                                $daysRemaining = $today->diff($expiryDate)->days;
                                if ($expiryDate < $today) {
                                    $daysRemaining = -$daysRemaining;
                                }
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $record['vehicle']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                        <?php echo $record['document']; ?>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <?php 
                                    $issueDate = new DateTime($record['expiry']);
                                    $issueDate->modify('-1 year');
                                    echo $issueDate->format('M d, Y'); 
                                    ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($record['expiry'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <?php if ($daysRemaining < 0): ?>
                                        <span class="text-red-600 font-semibold"><?php echo abs($daysRemaining); ?> days overdue</span>
                                    <?php elseif ($daysRemaining <= 30): ?>
                                        <span class="text-yellow-600 font-semibold"><?php echo $daysRemaining; ?> days</span>
                                    <?php else: ?>
                                        <span class="text-gray-700"><?php echo $daysRemaining; ?> days</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php 
                                        echo $record['status'] === 'Valid' ? 'bg-green-100 text-green-800' : 
                                            ($record['status'] === 'Expiring Soon' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); 
                                    ?>">
                                        <?php echo $record['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="viewDocument(<?php echo $record['id']; ?>)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="editExpiry(<?php echo $record['id']; ?>)">
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

    <!-- Upload Document Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="uploadModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Upload Compliance Document</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="uploadForm" onsubmit="saveDocument(event)" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle *</label>
                        <select id="vehicleSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Vehicle</option>
                            <?php foreach (getVehicles() as $vehicle): ?>
                            <option value="<?php echo $vehicle['plate']; ?>"><?php echo $vehicle['plate']; ?> - <?php echo $vehicle['model']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Document Type *</label>
                        <select id="documentType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Type</option>
                            <option value="Registration">Registration</option>
                            <option value="Insurance">Insurance</option>
                            <option value="Emission Test">Emission Test</option>
                            <option value="Safety Inspection">Safety Inspection</option>
                            <option value="Franchise">Franchise</option>
                            <option value="Operating Permit">Operating Permit</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Issue Date *</label>
                        <input type="date" id="issueDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date *</label>
                        <input type="date" id="expiryDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Document Number</label>
                    <input type="text" id="documentNumber" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="e.g., REG-2024-12345">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Document File *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center hover:border-primary-green transition-colors">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-500">PDF, PNG, JPG up to 10MB</p>
                        <input type="file" id="documentFile" accept=".pdf,.png,.jpg,.jpeg" class="hidden" required>
                        <button type="button" onclick="document.getElementById('documentFile').click()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200 transition-all">
                            Choose File
                        </button>
                        <p id="fileName" class="mt-2 text-sm text-gray-600"></p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Additional notes..." rows="3"></textarea>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeUploadModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Document Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="viewModal">
        <div class="bg-white rounded-lg w-11/12 max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Document Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="space-y-4">
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Vehicle:</span>
                            <span class="text-sm text-gray-900 font-semibold" id="viewVehicle">-</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Document Type:</span>
                            <span class="text-sm text-gray-900" id="viewDocType">-</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Issue Date:</span>
                            <span class="text-sm text-gray-900" id="viewIssueDate">-</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Expiry Date:</span>
                            <span class="text-sm text-gray-900" id="viewExpiryDate">-</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Days Remaining:</span>
                            <span class="text-sm text-gray-900 font-bold" id="viewDaysRemaining">-</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Status:</span>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800" id="viewStatus">Valid</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <i class="fas fa-file-pdf text-6xl text-red-500 mb-4"></i>
                    <p class="text-sm text-gray-600 mb-4">Document Preview</p>
                    <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-2">
                        <i class="fas fa-download"></i> Download Document
                    </button>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 flex gap-3">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeViewModal()">
                        Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300" onclick="renewDocument()">
                        <i class="fas fa-sync"></i> Renew Document
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display selected file name
        document.getElementById('documentFile').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('fileName').textContent = `Selected: ${fileName}`;
            }
        });

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[0].textContent.toLowerCase();
                const document = row.cells[1].textContent.toLowerCase();
                const status = row.cells[5].textContent.trim();

                const matchesSearch = vehicle.includes(searchInput) || document.includes(searchInput);
                const matchesStatus = !statusFilter || status.includes(statusFilter);

                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        function openUploadModal() {
            document.getElementById('uploadForm').reset();
            document.getElementById('fileName').textContent = '';
            document.getElementById('uploadModal').classList.remove('hidden');
            document.getElementById('uploadModal').classList.add('flex');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadModal').classList.remove('flex');
        }

        function saveDocument(event) {
            event.preventDefault();
            alert('Document uploaded successfully!');
            closeUploadModal();
            // Reload page or update table here
        }

        function viewDocument(id) {
            // In a real application, fetch document details via AJAX
            document.getElementById('viewVehicle').textContent = 'ABC-1234';
            document.getElementById('viewDocType').textContent = 'Insurance';
            document.getElementById('viewIssueDate').textContent = 'Mar 15, 2024';
            document.getElementById('viewExpiryDate').textContent = 'Mar 15, 2025';
            document.getElementById('viewDaysRemaining').textContent = '140 days';
            
            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        function editExpiry(id) {
            alert('Edit expiry date functionality - Coming soon!');
        }

        function renewDocument() {
            alert('Renew document functionality - Coming soon!');
        }

        // Close modals when clicking outside
        document.getElementById('uploadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
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