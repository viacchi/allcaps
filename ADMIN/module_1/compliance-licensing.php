<?php
include '../includes/functions.php';


$vehicle_id = isset($_GET['id']) ? intval($_GET['id']) : null; // null = show all


// Fetch compliance documents
if ($vehicle_id) {
    $stmt = $conn->prepare("
        SELECT c.*, v.plate, v.model 
        FROM compliance_documents c
        JOIN vehicles v ON c.vehicle_id = v.id
        WHERE c.vehicle_id = ?
        ORDER BY c.expiry_date ASC
    ");
    $stmt->bind_param("i", $vehicle_id);
} else {
    $stmt = $conn->prepare("
        SELECT c.*, v.plate, v.model 
        FROM compliance_documents c
        JOIN vehicles v ON c.vehicle_id = v.id
        ORDER BY c.expiry_date ASC
    ");
}

$stmt->execute();
$result = $stmt->get_result();
$compliance = $result->fetch_all(MYSQLI_ASSOC);
// -----------------------------
// HANDLE NEW COMPLIANCE UPLOAD
// -----------------------------
if (!empty($_POST['add_compliance'])) {
    $vehicle_id = intval($_POST['vehicle_id']);
    $document_type = $_POST['document_type'];
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $file_path = null;

    // Handle file upload
    if (!empty($_FILES['document']['name'])) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // create uploads folder if it doesn't exist
        }

        // Make filename unique
        $fileName = time() . '_' . basename($_FILES['document']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFile)) {
            $file_path = $targetFile;
        } else {
            die("Failed to upload file.");
        }
    }

$document_number = $_POST['document_number'] ?? null;

$stmt = $conn->prepare("
    INSERT INTO compliance_documents
    (vehicle_id, document_type, issue_date, expiry_date, file_path, document_number)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isssss", $vehicle_id, $document_type, $issue_date, $expiry_date, $file_path, $document_number);
if (!$stmt->execute()) {
    die("Error inserting record: " . $stmt->error);
}
header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $vehicle_id);
exit;
}

// -----------------------------
// FETCH VEHICLES FOR DROPDOWN
// -----------------------------
$vehicles = [];
$sql = "SELECT id, plate AS plate_number FROM vehicles ORDER BY plate ASC";
$result = $conn->query($sql);
if ($result) {
    $vehicles = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error fetching vehicles: " . $conn->error);
}

// -----------------------------
// GET VEHICLE ID (POST or GET)
// -----------------------------
if (isset($_POST['add_compliance'])) {
    // your upload handling code here...
    
    // Redirect to clear POST and show all documents
    header("Location: compliance-licensing.php");
    exit;
}

$stmt->execute();
$result = $stmt->get_result();
$compliance = $result->fetch_all(MYSQLI_ASSOC);

// -----------------------------
// CALCULATE STATUS AND DAYS REMAINING
// -----------------------------
$complianceStatuses = [
    'Valid' => 0,
    'Expiring Soon' => 0,
    'Expired' => 0
];

$today = new DateTime();

foreach ($compliance as &$record) {
    $expiryDate = new DateTime($record['expiry_date']);
    $daysRemaining = (int)$today->diff($expiryDate)->format('%r%a');
    $record['days_remaining'] = $daysRemaining;

    if ($daysRemaining < 0) {
        $record['status'] = 'Expired';
        $complianceStatuses['Expired']++;
    } elseif ($daysRemaining <= 30) {
        $record['status'] = 'Expiring Soon';
        $complianceStatuses['Expiring Soon']++;
    } else {
        $record['status'] = 'Valid';
        $complianceStatuses['Valid']++;
    }
}

// -----------------------------
// FETCH VEHICLE INFO (if selected)
// -----------------------------
$vehicle = null;
if ($vehicle_id) {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $vehicle = $stmt->get_result()->fetch_assoc();
}
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
    <?php include '../includes/sidebar.php'; ?>
    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <?php include '../includes/header.php'; ?>

        <main class="p-6">
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
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo $complianceStatuses['Valid']; ?>
                            </div>
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
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo $complianceStatuses['Expiring Soon']; ?>
                            </div>
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
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo $complianceStatuses['Expired']; ?>
                            </div>
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
<?php
$complianceRecords = getComplianceRecords();
foreach ($complianceRecords as $rec):
    // Determine days remaining
    $today = new DateTime();
    $expiryDate = new DateTime($rec['expiry_date']);
    $daysRemaining = (int)$today->diff($expiryDate)->format('%r%a');

    // Determine status
    if ($daysRemaining < 0) {
        $status = 'Expired';
    } elseif ($daysRemaining <= 30) {
        $status = 'Expiring Soon';
    } else {
        $status = 'Valid';
    }

    // Tailwind status classes
    $statusClass = $status === 'Valid' ? 'bg-green-100 text-green-800' :
                   ($status === 'Expiring Soon' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
?>
<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
    <!-- Vehicle -->
    <td class="px-5 py-4 text-sm font-semibold text-primary-green">
        <?= htmlspecialchars($rec['vehicle'] ?? '-') ?>
    </td>

    <!-- Document Type -->
    <td class="px-5 py-4 text-sm text-gray-700">
        <div class="flex items-center gap-2">
            <i class="fas fa-file-alt text-gray-400"></i>
            <?= htmlspecialchars($rec['document_type']) ?>
        </div>
    </td>

    <!-- Issue Date -->
    <td class="px-5 py-4 text-sm text-gray-700">
        <?= date('M d, Y', strtotime($rec['issue_date'])) ?>
    </td>

    <!-- Expiry Date -->
    <td class="px-5 py-4 text-sm text-gray-700">
        <?= date('M d, Y', strtotime($rec['expiry_date'])) ?>
    </td>

    <!-- Days Remaining -->
    <td class="px-5 py-4 text-sm">
        <?php if ($daysRemaining < 0): ?>
            <span class="text-red-600 font-semibold"><?= abs($daysRemaining) ?> days overdue</span>
        <?php elseif ($daysRemaining <= 30): ?>
            <span class="text-yellow-600 font-semibold"><?= $daysRemaining ?> days</span>
        <?php else: ?>
            <span class="text-gray-700"><?= $daysRemaining ?> days</span>
        <?php endif; ?>
    </td>

    <!-- Status -->
    <td class="px-5 py-4 text-sm">
        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
            <?= $status ?>
        </span>
    </td>

    <!-- Actions -->
    <td class="px-5 py-4 text-sm">
        <div class="flex gap-2">
            <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" 
                    onclick="viewDocument(<?= $rec['id'] ?>)">
                <i class="fas fa-eye"></i> View
            </button>
            <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" 
                    onclick="editExpiry(<?= $rec['id'] ?>)">
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
    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
            <button onclick="closeViewModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-900">&times;</button>
            <h2 class="text-xl font-bold mb-4">View Compliance Document</h2>
            <div class="space-y-2">
                <p><strong>Vehicle:</strong> <span id="viewVehicle"></span></p>
                <p><strong>Document Type:</strong> <span id="viewDocType"></span></p>
                <p><strong>Issue Date:</strong> <span id="viewIssueDate"></span></p>
                <p><strong>Expiry Date:</strong> <span id="viewExpiryDate"></span></p>
                <p><strong>Days Remaining:</strong> <span id="viewDaysRemaining"></span></p>
                <p><strong>Document Number:</strong> <span id="viewDocNumber"></span></p>
                <div id="viewFileContainer" class="mt-2"></div>
            </div>
        </div>
    </div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 overflow-auto">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-900">&times;</button>
        <h2 class="text-xl font-bold mb-4">Edit Compliance Document</h2>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            <input type="hidden" name="id" id="editId">
            <div>
                <label class="block text-sm font-medium text-gray-700">Document Type</label>
                <input type="text" name="document_type" id="editDocumentType" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Issue Date</label>
                <input type="date" name="issue_date" id="editIssueDate" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="date" name="expiry_date" id="editExpiryDate" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Document Number</label>
                <input type="text" name="document_number" id="editDocNumber" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">File</label>
                <input type="file" name="document" id="editDocumentFile" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <div id="editFileContainer" class="mt-2">
                    <!-- File preview of the current file -->
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Cancel</button>
                <button type="submit" name="update_compliance" class="px-4 py-2 bg-primary-green text-white rounded-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
        <button onclick="closeUploadModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-900">&times;</button>

        <h2 class="text-xl font-bold mb-4">Upload Compliance Document</h2>

        <form id="uploadForm" method="POST" enctype="multipart/form-data" class="space-y-3">
            <input type="hidden" name="add_compliance" value="1">
            <div>
                <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                <select name="vehicle_id" required
                        class="mt-1 block w-full border rounded px-3 py-2 text-sm">
                    <option value="" <?= is_null($vehicle_id) ? 'selected' : '' ?>>All Vehicles</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $v['id'] ?>" <?= ($v['id'] == $vehicle_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['plate_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Document Type</label>
                <select name="document_type" required
                        class="mt-1 block w-full border rounded px-3 py-2 text-sm">
                    <option value="">Select type</option>
                    <option value="Registration">Registration</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Emission Test">Emission Test</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Issue Date</label>
                <input type="date" name="issue_date" required
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="date" name="expiry_date" required
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload File</label>
                <input type="file" name="document" id="documentFile"
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                <p id="fileName" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeUploadModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-primary-green text-white rounded-md">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>


    <script>
                // Display selected file name
        const documentFile = document.getElementById('documentFile');
        if (documentFile) {
            documentFile.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    document.getElementById('fileName').textContent = `Selected: ${fileName}`;
                }
            });
        }

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
            const record = <?php echo json_encode($compliance); ?>.find(r => r.id == id);
            if (!record) return;

            document.getElementById('viewVehicle').textContent = record.plate;
            document.getElementById('viewDocType').textContent = record.document_type;
            document.getElementById('viewIssueDate').textContent = new Date(record.issue_date).toLocaleDateString();
            document.getElementById('viewExpiryDate').textContent = new Date(record.expiry_date).toLocaleDateString();
            document.getElementById('viewDaysRemaining').textContent = record.days_remaining + ' days';
            document.getElementById('viewDocNumber').textContent = record.document_number || '-';

            const fileContainer = document.getElementById('viewFileContainer');
            fileContainer.innerHTML = ''; // Clear previous content

            const filePath = record.file_path;
            if (!filePath) {
                fileContainer.textContent = 'No file uploaded';
                return;
            }

            const ext = filePath.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif'].includes(ext)) {
                fileContainer.innerHTML = `<img src="${filePath}" class="w-full h-auto rounded border" />`;
            } else if (ext === 'pdf') {
                fileContainer.innerHTML = `<iframe src="${filePath}" class="w-full h-64 border"></iframe>`;
            } else {
                fileContainer.textContent = 'File type not previewable: ' + filePath;
            }

            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        function editExpiry(id) {
            const record = <?php echo json_encode($compliance); ?>.find(r => r.id == id);
            if (!record) return;

            document.getElementById('editId').value = record.id;
            document.getElementById('editDocumentType').value = record.document_type;
            document.getElementById('editIssueDate').value = record.issue_date;
            document.getElementById('editExpiryDate').value = record.expiry_date;
            document.getElementById('editDocNumber').value = record.document_number;
            
            const editContainer = document.getElementById('editFileContainer');
            editContainer.innerHTML = '';

            if (record.file_path) {
                const ext = record.file_path.split('.').pop().toLowerCase();
                if (['jpg','jpeg','png','gif'].includes(ext)) {
                    const img = document.createElement('img');
                    img.src = record.file_path;
                    img.className = 'max-w-full max-h-64 rounded-lg border';
                    editContainer.appendChild(img);
                } else if (['pdf'].includes(ext)) {
                    const iframe = document.createElement('iframe');
                    iframe.src = record.file_path;
                    iframe.className = 'w-full h-64 border rounded-lg';
                    editContainer.appendChild(iframe);
                } else {
                    const p = document.createElement('p');
                    p.textContent = 'Cannot preview this file type.';
                    p.className = 'text-gray-600 italic';
                    editContainer.appendChild(p);
                }
            } else {
                const p = document.createElement('p');
                p.textContent = 'No file uploaded.';
                p.className = 'text-gray-600 italic';
                editContainer.appendChild(p);
            }

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
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

