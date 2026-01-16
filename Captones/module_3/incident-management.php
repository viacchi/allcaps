<?php
include '../includes/functions.php';
$incidents = getIncidentCases();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Case Management - Logistics Admin</title>
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
                <p class="text-gray-600">Investigate, document, and close incident cases</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Cases</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($incidents); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-file-alt"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Under Investigation</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($incidents, fn($i) => $i['status'] === 'Under Investigation')); ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-search"></i> Active cases
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Resolved Cases</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($incidents, fn($i) => $i['status'] === 'Resolved')); ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> Completed
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
                            <div class="text-gray-600 text-sm font-medium">High Severity</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($incidents, fn($i) => $i['severity'] === 'High')); ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-exclamation-triangle"></i> Requires attention
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Incident Cases Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-folder-open text-primary-green"></i>
                            Incident Cases
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3 flex-wrap">
                                <input type="text" id="searchInput" placeholder="Search by case, driver..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterStatus" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Status</option>
                                    <option value="Under Investigation">Under Investigation</option>
                                    <option value="Pending Review">Pending Review</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Closed">Closed</option>
                                </select>
                                <select id="filterSeverity" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Severity</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="openAddCaseModal()">
                                <i class="fas fa-plus"></i> Add Case
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="incidentTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Case ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Severity</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($incidents as $incident): 
                                $severityColors = [
                                    'High' => 'bg-red-100 text-red-800',
                                    'Medium' => 'bg-yellow-100 text-yellow-800',
                                    'Low' => 'bg-blue-100 text-blue-800'
                                ];
                                $statusColors = [
                                    'Under Investigation' => 'bg-yellow-100 text-yellow-800',
                                    'Pending Review' => 'bg-blue-100 text-blue-800',
                                    'Resolved' => 'bg-green-100 text-green-800',
                                    'Closed' => 'bg-gray-100 text-gray-800'
                                ];
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $incident['case_number']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($incident['date'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-primary-green rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            <?php echo strtoupper(substr($incident['driver'], 0, 2)); ?>
                                        </div>
                                        <span class="text-gray-900"><?php echo $incident['driver']; ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $incident['vehicle']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $incident['type']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $severityColors[$incident['severity']]; ?>">
                                        <?php echo $incident['severity']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $statusColors[$incident['status']]; ?>">
                                        <?php echo $incident['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick='viewCaseDetails(<?php echo json_encode($incident); ?>)'>
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <?php if ($incident['status'] !== 'Closed'): ?>
                                            <button class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 transition-all inline-flex items-center gap-1.5" onclick="closeCase(<?php echo $incident['id']; ?>, '<?php echo $incident['case_number']; ?>')">
                                                <i class="fas fa-times-circle"></i> Close
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

    <!-- View Case Details Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="viewModal">
        <div class="bg-white rounded-lg w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Incident Case Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Case Header -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-xs text-gray-600 mb-1">Case Number</div>
                            <div class="text-sm font-semibold text-primary-green" id="modalCaseNumber">-</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-600 mb-1">Date Reported</div>
                            <div class="text-sm font-semibold text-gray-900" id="modalDate">-</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-600 mb-1">Severity</div>
                            <div><span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" id="modalSeverity">-</span></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-600 mb-1">Status</div>
                            <div><span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" id="modalStatus">-</span></div>
                        </div>
                    </div>
                </div>

                <!-- Case Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Driver & Vehicle Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-user-circle text-primary-green"></i>
                            Driver & Vehicle Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Driver:</span>
                                <span class="font-semibold text-gray-900" id="modalDriver">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Vehicle:</span>
                                <span class="font-semibold text-gray-900" id="modalVehicle">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Location:</span>
                                <span class="text-gray-900" id="modalLocation">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Incident Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary-green"></i>
                            Incident Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Type:</span>
                                <span class="font-semibold text-gray-900" id="modalType">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Reported By:</span>
                                <span class="text-gray-900" id="modalReportedBy">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                    <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-900" id="modalDescription">
                        -
                    </div>
                </div>

                <!-- Attachments -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-paperclip text-primary-green"></i>
                        Attachments
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary-green transition-colors cursor-pointer">
                            <i class="fas fa-file-image text-4xl text-gray-400 mb-2"></i>
                            <p class="text-xs text-gray-600">accident_photo1.jpg</p>
                        </div>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary-green transition-colors cursor-pointer">
                            <i class="fas fa-file-pdf text-4xl text-red-400 mb-2"></i>
                            <p class="text-xs text-gray-600">police_report.pdf</p>
                        </div>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-primary-green transition-colors cursor-pointer">
                            <i class="fas fa-file-image text-4xl text-gray-400 mb-2"></i>
                            <p class="text-xs text-gray-600">damage_photo.jpg</p>
                        </div>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center">
                            <button class="text-primary-green hover:text-dark-green">
                                <i class="fas fa-plus-circle text-2xl"></i>
                                <p class="text-xs mt-1">Add File</p>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Investigation Notes -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Investigation Notes</h4>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" rows="4" placeholder="Add investigation notes...">Initial investigation shows minor collision. No injuries reported. Vehicle damage minimal.</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeViewModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="updateCase()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeCaseFromModal()">
                        <i class="fas fa-check-circle"></i> Close Case</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Case Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="addCaseModal">
        <div class="bg-white rounded-lg w-11/12 max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Add New Incident Case</h3>
                <button onclick="closeAddCaseModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addCaseForm" onsubmit="saveNewCase(event)" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Driver *</label>
                        <select id="caseDriver" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Driver</option>
                            <?php foreach (getAvailableDrivers() as $driver): ?>
                            <option value="<?php echo $driver['name']; ?>"><?php echo $driver['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle *</label>
                        <select id="caseVehicle" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Vehicle</option>
                            <?php foreach (getVehicles() as $vehicle): ?>
                            <option value="<?php echo $vehicle['plate']; ?>"><?php echo $vehicle['plate']; ?> - <?php echo $vehicle['model']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Incident Type *</label>
                        <select id="caseType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Type</option>
                            <option value="Accident">Accident</option>
                            <option value="Traffic Violation">Traffic Violation</option>
                            <option value="Breakdown">Breakdown</option>
                            <option value="Speeding">Speeding</option>
                            <option value="Rule Violation">Rule Violation</option>
                            <option value="Customer Complaint">Customer Complaint</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Severity *</label>
                        <select id="caseSeverity" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                            <option value="">Select Severity</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date & Time *</label>
                        <input type="datetime-local" id="caseDateTime" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Reported By *</label>
                        <input type="text" id="caseReportedBy" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="e.g., Admin, Driver, Customer" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Location *</label>
                    <input type="text" id="caseLocation" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="e.g., EDSA-Quezon Ave" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                    <textarea id="caseDescription" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Detailed description of the incident..." rows="4" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Attach Files</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center hover:border-primary-green transition-colors">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                        <p class="text-xs text-gray-500">Photos, PDFs, Documents (Max 10MB each)</p>
                        <input type="file" id="caseAttachments" multiple accept="image/*,.pdf,.doc,.docx" class="hidden">
                        <button type="button" onclick="document.getElementById('caseAttachments').click()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200 transition-all">
                            Choose Files
                        </button>
                        <p id="fileNames" class="mt-2 text-sm text-gray-600"></p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeAddCaseModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Create Case
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Close Case Confirmation Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="closeConfirmModal">
        <div class="bg-white rounded-lg w-11/12 max-w-md shadow-2xl">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Close Case</h3>
                        <p class="text-sm text-gray-600 mt-1">Are you sure you want to close this case?</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Resolution Notes</label>
                    <textarea id="resolutionNotes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="Explain how the case was resolved..." rows="3"></textarea>
                </div>
                <div class="flex gap-3 mt-6">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeCloseConfirmModal()">
                        Cancel
                    </button>
                    <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300" onclick="confirmCloseCase()">
                        Close Case
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCaseId = null;
        let currentCaseNumber = null;

        // Display selected file names
        document.getElementById('caseAttachments').addEventListener('change', function(e) {
            const fileNames = Array.from(e.target.files).map(file => file.name).join(', ');
            if (fileNames) {
                document.getElementById('fileNames').textContent = `Selected: ${fileNames}`;
            }
        });

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const severityFilter = document.getElementById('filterSeverity').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const caseNumber = row.cells[0].textContent.toLowerCase();
                const driver = row.cells[2].textContent.toLowerCase();
                const status = row.cells[6].textContent.trim();
                const severity = row.cells[5].textContent.trim();

                const matchesSearch = caseNumber.includes(searchInput) || driver.includes(searchInput);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesSeverity = !severityFilter || severity === severityFilter;

                row.style.display = matchesSearch && matchesStatus && matchesSeverity ? '' : 'none';
            });
        }

        function viewCaseDetails(incident) {
            const severityColors = {
                'High': 'bg-red-100 text-red-800',
                'Medium': 'bg-yellow-100 text-yellow-800',
                'Low': 'bg-blue-100 text-blue-800'
            };
            const statusColors = {
                'Under Investigation': 'bg-yellow-100 text-yellow-800',
                'Pending Review': 'bg-blue-100 text-blue-800',
                'Resolved': 'bg-green-100 text-green-800',
                'Closed': 'bg-gray-100 text-gray-800'
            };

            document.getElementById('modalCaseNumber').textContent = incident.case_number;
            document.getElementById('modalDate').textContent = new Date(incident.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('modalDriver').textContent = incident.driver;
            document.getElementById('modalVehicle').textContent = incident.vehicle;
            document.getElementById('modalLocation').textContent = incident.location;
            document.getElementById('modalType').textContent = incident.type;
            document.getElementById('modalReportedBy').textContent = incident.reported_by;
            document.getElementById('modalDescription').textContent = incident.description;
            
            const severityEl = document.getElementById('modalSeverity');
            severityEl.textContent = incident.severity;
            severityEl.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ' + severityColors[incident.severity];
            
            const statusEl = document.getElementById('modalStatus');
            statusEl.textContent = incident.status;
            statusEl.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ' + statusColors[incident.status];

            currentCaseId = incident.id;
            currentCaseNumber = incident.case_number;
            
            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        function openAddCaseModal() {
            document.getElementById('addCaseForm').reset();
            document.getElementById('fileNames').textContent = '';
            document.getElementById('addCaseModal').classList.remove('hidden');
            document.getElementById('addCaseModal').classList.add('flex');
        }

        function closeAddCaseModal() {
            document.getElementById('addCaseModal').classList.add('hidden');
            document.getElementById('addCaseModal').classList.remove('flex');
        }

        function saveNewCase(event) {
            event.preventDefault();
            alert('New incident case created successfully!');
            closeAddCaseModal();
            location.reload();
        }

        function closeCase(id, caseNumber) {
            currentCaseId = id;
            currentCaseNumber = caseNumber;
            document.getElementById('closeConfirmModal').classList.remove('hidden');
            document.getElementById('closeConfirmModal').classList.add('flex');
        }

        function closeCaseFromModal() {
            closeViewModal();
            closeCase(currentCaseId, currentCaseNumber);
        }

        function closeCloseConfirmModal() {
            document.getElementById('closeConfirmModal').classList.add('hidden');
            document.getElementById('closeConfirmModal').classList.remove('flex');
            document.getElementById('resolutionNotes').value = '';
        }

        function confirmCloseCase() {
            const notes = document.getElementById('resolutionNotes').value;
            if (!notes.trim()) {
                alert('Please provide resolution notes before closing the case.');
                return;
            }
            alert(`Case ${currentCaseNumber} closed successfully!`);
            closeCloseConfirmModal();
            location.reload();
        }

        function updateCase() {
            alert('Case updated successfully!');
            closeViewModal();
            location.reload();
        }

        // Close modals when clicking outside
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) closeViewModal();
        });

        document.getElementById('addCaseModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddCaseModal();
        });

        document.getElementById('closeConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) closeCloseConfirmModal();
        });
    </script>
</body>
</html>