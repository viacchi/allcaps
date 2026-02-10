<?php
include '../staff-includes/staff-functions.php';
$incident = getIncident();
$totalIncidents = count($incident);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reporting- Logistics Staff</title>
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
    <?php include '../staff-includes/staff-sidebar.php'; ?> 

    <div class="ml-0 md:ml-[280px] min-h-screen"> 
        <?php include '../staff-includes/staff-header.php'; ?> <main class="p-6">
            <div class="mb-8">
                <p class="text-gray-600 mt-2">Enables staff to report vehicle accidents, damage, or issues </p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-rose-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Incidents</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $totalIncidents; ?> </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-folder-closed text-rose-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-rose-600"><i class="fa-solid fa-clipboard-list"></i> Total Incidents Reports</div>
                </div>
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Pending Reviews</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"> <?php echo count(array_filter($incident, fn($i) => $i['status'] === 'Pending')); ?> </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-folder-open text-amber-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-amber-600"><i class="fa-solid fa-wrench"></i> Pending & Under Review Reports</div>
                </div>
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-lime-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Resolved Reports</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"> <?php echo count(array_filter($incident, fn($i) => $i['status'] === 'Resolved')); ?></div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-file-circle-check text-lime-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-lime-600"><i class="fa-solid fa-wrench"></i> Completed and Fixed Issues</div>
                </div>
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Denied Reports</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"> <?php echo count(array_filter($incident, fn($i) => $i['status'] === 'Under Investigation')); ?> </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-file-circle-xmark text-red-600 text-xl"></i>                        
                        </div>
                    </div>
                    <div class="text-xs font-medium text-red-600"><i class="fa-solid fa-circle-xmark"></i> Not Processed</div>
                </div>
            </div>

            <!-- Main Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Incident Reports</h2>
                      <div class="flex items-center gap-3">
                        <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition inline-flex items-center gap-2" onclick="openAddIncidentModal()">
                            <i class="fas fa-plus"></i> Add Incident
                        </button>
                    </div>
                </div>

            </div>
            <!-- Filters -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex gap-3 flex-wrap">
                    <input type="text" id="searchInput" placeholder="Search by vehicle, date, or status..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">
                    <select id="statusFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                        <option value="">All Status</option> <?php
                         $statusList = array_unique(array_column($incident, 'status'));
                         foreach($statusList as $s) {
                            echo '<option value="'.htmlspecialchars($s).'">'.htmlspecialchars($s).'</option>';

                         }
                        ?>
                    </select>
                </div>
            </div>
            <!-- Table -->
            <div class="overflow-x-auto">
                <div class="max-h-[52vh] overflow-auto">
                    <table id="IncidentTable" class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Report ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Incident Type</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Remarks</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"> 
                            <?php foreach ($incident as $row): ?>
                                 <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"> #<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?> </td>
                                <td class="px-5 py-4 text-sm font-semibold text-gray-900"> <?php echo htmlspecialchars($row['vehicle']); ?> 
                                <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['model'] ?? ''); ?></div></td>
                                <td class="px-5 py-4 text-sm text-gray-700"> <?php echo date('M d, Y', strtotime($row['date'])); ?> </td>
                                <td class="px-5 py-4 text-sm text-gray-700"> <?php echo htmlspecialchars($row['incident_type']); ?>
                                <td class="status-cell px-5 py-4 text-sm">
                                  <span class="status-cell px-3 py-1 rouded-full text-xs font-semibold
                                    <?php
                                      echo $row['status'] === 'Resolved' ? 'bg-green-100 text-green-800' :
                                           ($row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-red-100 text-red-800');
                                    ?>">
                                    <?php echo trim($row['status']); ?>
                                 <td class="px-5 py-4 text-sm text-gray-700"><?php echo $row['remarks']; ?></td>

                                 </span>
                                 <td class="px-5 py-4 text-sm text-gray-700">
                                      <div class="flex items-center justify-center gap-2">
                                        
                                    <!-- VIEW DETAILS -->
                                     
                                    <button onclick='viewIncidentDetail(<?php echo json_encode($row); ?>)'
                                     class="w-28 px-3 py-1.5 bg-green-600 text-white rounded-md text-xs font-semibold hover:bg-green-700 transition-all inline-flex items-center gap-1.5">
                                      <i class="fas fa-eye"></i> View Details
                                     </button>
                                     
                                     <!-- EDIT -->
                                          <button onclick='editIncident(<?php echo json_encode($row); ?>)'
                                           class="w-28 px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5">
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

 <!-- ADD INCIDENT MODAL -->
  <div id="addIncidentModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 id="addIncidentTitle" class="text-xl font-bold text-gray-900">Add Incident</h3>
        <button onclick="closeAddIncidentModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>

      <form id="addIncidentForm" class="space-y-4" onsubmit="saveIncident(event)">
        <input type="hidden" id="incidentIndex" value="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle</label>
            <input type="text" id="incidentVehicle" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., ABC-1234" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Model</label>
            <input type="text" id="incidentModel" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., Hiace" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
            <input type="date" id="incidentDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" required>
          </div>

           <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Incident Type</label>
            <input type="text" id="incidentType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., Engine Overheat" required>
          </div>

        <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Entry Image</label>
        <input type="file" id="entryImage" accept="image/*"
            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-2 focus:ring-primary-black focus:border-transparent">
        </div>

        </div>
        <div class="flex gap-3 justify-end">
          <button type="button" onclick="closeAddIncidentModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-primary-green text-white rounded-md font-semibold">Save Incident</button>
        </div>
      </form>
    </div>
  </div>

  <!-- VIEW INCIDENT DETAILS MODAL -->
   <div id="detailIncidentModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
      <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
         <h3 class="text-xl font-bold">Incident Details</h3> <button onclick="closeDetailIncidentModal()" 
         class="text-white hover:text-gray-200 text-2xl"><i class="fas fa-times"></i></button>
      </div>
      <div class="p-6 space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
          <h4 class="text-sm font-bold text-gray-900 mb-3">Incident Logs Information</h4>
          
          <div class="space-y-3 text-sm">
            <div><strong>ID:</strong> <span id="detailIncidentID">-</span></div>
            <div><strong>Vehicle:</strong> <span id="detailVehicle">-</span></div>
            <div><strong>Model:</strong> <span id="detailModel">-</span></div>
            <div><strong>Date:</strong> <span id="detailDate">-</span></div>
            <div><strong>Incident Type:</strong> <span id="detailType">-</span></div>
            <div><strong>Status:</strong><span id="detailStatus" class="px-3 py-1 rounded-full text-xs font-semibold">-</span></div>
            <div><strong>Remarks:</strong> <span id="detailRemarks">-</span></div>
        </div>
        <div class="flex gap-3 mt-4">
          <button onclick="closeDetailIncidentModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300">Close</button>
        </div>
  </div>
</div>


<script>

function filterTable() {
    let search = document.getElementById("searchInput").value.toLowerCase();
    let statusFilter = document.getElementById("statusFilter").value.toLowerCase();

    let rows = document.querySelectorAll("#IncidentTable tbody tr");

    rows.forEach(row => {
        let rowText = row.innerText.toLowerCase();

        let statusCell = row.querySelector(".status-cell");
        let status = statusCell ? statusCell.innerText.toLowerCase().trim() : "";

        let matchesSearch = rowText.includes(search);

        let matchesStatus =
            statusFilter === "" || status.includes(statusFilter);

        row.style.display = (matchesSearch && matchesStatus) ? "" : "none";
    });
}

function openAddIncidentModal() {
    document.getElementById('addIncidentModal').classList.remove('hidden');
    document.getElementById('addIncidentModal').classList.add('flex');
}

function closeAddIncidentModal() {
    document.getElementById('addIncidentModal').classList.remove('flex');
    document.getElementById('addIncidentModal').classList.add('hidden');
}

function saveIncident(e) {
    e.preventDefault();
    const vehicle = document.getElementById('incidentVehicle').value;
    alert('Incident saved . Vehicle: ' + vehicle);
    closeAddIncidentModal();
}

function viewIncidentDetail(incident) {

    console.log(incident); 

    document.getElementById('detailIncidentID').textContent =
        "#" + String(incident.id).padStart(4, '0');

    document.getElementById('detailVehicle').textContent = incident.vehicle ?? "-";
    document.getElementById('detailModel').textContent = incident.model ?? "-";
    document.getElementById('detailDate').textContent = incident.date ?? "-";
    document.getElementById('detailType').textContent = incident.incident_type ?? "-";
    document.getElementById('detailRemarks').textContent = incident.remarks ?? "-";

    const statusEl = document.getElementById('detailStatus');
    const status = incident.status ?? "Unknown";

    statusEl.textContent = status;
    statusEl.className = "px-3 py-1 rounded-full text-xs font-semibold ml-2";

    if (status === "Resolved") {
        statusEl.classList.add("bg-green-100", "text-green-800");
    } else if (status === "Pending") {
        statusEl.classList.add("bg-yellow-100", "text-yellow-800");
    } else {
        statusEl.classList.add("bg-red-100", "text-red-800");
    }

    const modal = document.getElementById("detailIncidentModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function closeDetailIncidentModal() {
    const modal = document.getElementById("detailIncidentModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function editIncident(incident){

    //Change modal title
    document.getElementById('addIncidentTitle').textContent = "Edit Incident";

    //Store ID (hidden input)
    document.getElementById('incidentIndex').value = incident.id;

    //Fill from fields
    document.getElementById('incidentVehicle').value = incident.vehicle;
    document.getElementById('incidentModel').value = incident.model;
    document.getElementById('incidentDate').value = incident.date;
    document.getElementById('incidentType').value = incident.incident_type;

    //Open modal
    const modal = document.getElementById('addIncidentModal');
    modal.classList.remove("hidden");
    modal.classList("flex")
}

</script>
    
</body>
</html>