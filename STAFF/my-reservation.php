<?php
include '../staff-includes/staff-functions.php';
$reservation = getReserv();
$total = count($reservation);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservation - Logistics Staff</title>
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

<?php include '../staff-includes/staff-header.php'; ?>

<main class="p-6">
    <div class="mb-8">
        <p class="text-gray-600 mt-2">Allow staff to submit and monitor vehicle reservation request</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Total Reservation</div>
                    <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $total; ?></div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-list text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="text-xs font-medium text-indigo-600"><i class="fa-solid fa-list"></i> Reservation average</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-lime-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Approved</div>
                    <div class="text-3xl font-bold text-gray-900 my-2">
                        <?php echo count(array_filter($reservation, fn($r) => $r['status'] === 'Approved')); ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-lime-600 text-xl"></i>
                </div>
            </div>
            <div class="text-xs font-medium text-lime-600"><i class="fa-solid fa-check-double"></i> Successfully Done</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Pending</div>
                    <div class="text-3xl font-bold text-gray-900 my-2">
                        <?php echo count(array_filter($reservation, fn($r) => $r['status'] === 'Pending')); ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fa-regular fa-hourglass-half text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="text-xs font-medium text-yellow-600"><i class="fa-solid fa-spinner"></i> Awaiting Trips</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-600 text-sm font-medium">Denied</div>
                    <div class="text-3xl font-bold text-gray-900 my-2">
                        <?php echo count(array_filter($reservation, fn($r) => $r['status'] === 'Denied')); ?>
                    </div>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-calendar-xmark text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="text-xs font-medium text-red-600"><i class="fa-solid fa-circle-xmark"></i> Not Processed</div>
        </div>

    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">

        <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">My Reservation</h2>
        </div>

        <!-- Filters -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex gap-3 flex-wrap">
                <input type="text" id="searchInput" placeholder="Search by vehicle, date, or destination..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">

                <select id="vehicleFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                    <option value="">All Vehicles</option>
                    <?php
                        $vehicleList = array_unique(array_column($reservation, 'vehicle'));
                        foreach ($vehicleList as $v) {
                            echo '<option value="'.htmlspecialchars($v).'">'.htmlspecialchars($v).'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <div class="max-h-[52vh] overflow-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Reservation ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Destination</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        <?php foreach ($reservation as $row): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">

                            <td class="px-5 py-4 text-sm font-semibold text-primary-green">
                                #<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?>
                            </td>

                            <td class="px-5 py-4 text-sm font-semibold text-gray-900">
                                <?php echo htmlspecialchars($row['vehicle']); ?>
                                <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['model'] ?? ''); ?></div>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                <?php echo date('M d, Y', strtotime($row['date'])); ?>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                <?php echo $row['destination'] ? '<i class="fas fa-route mr-1"></i>'.htmlspecialchars($row['destination']): '<span class="text-gray-400">-</span>'; ?>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?php 
                                        echo $row['status'] === 'Approved' ? 'bg-green-100 text-green-800' : 
                                            ($row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            'bg-red-100 text-red-800');
                                    ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm text-center">
                                <button onclick='viewReserveDetail(<?php echo json_encode($row); ?>)' class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>

</main>
</div>

  <!-- DETAIL RESERVATION MODAL -->
  <div id="detailReserveModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
      <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
        <h3 class="text-xl font-bold">Reservation Logs Details</h3>
        <button onclick="closeDetailReserveModal()" class="text-white hover:text-gray-200 text-2xl"><i class="fas fa-times"></i></button>
      </div>
      <div class="p-6 space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
          <h4 class="text-sm font-bold text-gray-900 mb-3">Reservation Logs Information</h4>
          <div class="space-y-2 text-sm text-gray-700">
            <div><span class="text-gray-600">Reservation ID:</span> <span id="detailReserveID" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Vehicle:</span> <span id="detailVehicle" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Model:</span> <span id="detailModel" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Date:</span> <span id="detailDate" class="inline-block px-2 py-1 rounded-full text-xs font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Destination:</span> <span id="detailDestination" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Status:</span> <span id="detailStatus" class="px-3 py-1 rounded-full text-xs font-semibold ml-2">-</span></div>
          </div>
        </div>

        <div class="flex gap-3 mt-4">
          <button onclick="closeDetailReserveModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300">Close</button>
        </div>
      </div>
    </div>
  </div>

 
<!-- FIXED FILTER SCRIPT -->

<script>

function escapeHtml(text) {
    if (!text) return "-";
    return text.replace(/[\"&'\/<>]/g, function (a) {
        return {
            '"': '&quot;',
            '&': '&amp;',
            "'": '&#39;',
            '/': '&#47;',
            '<': '&lt;',
            '>': '&gt;'
        }[a];
    });
}

function filterTable() {
    const search = document.getElementById("searchInput").value.toLowerCase();
    const vehicleSel = document.getElementById("vehicleFilter").value.toLowerCase();

    document.querySelectorAll("#tableBody tr").forEach(row => {

        const vehicle = row.cells[1].textContent.toLowerCase();
        const date = row.cells[2].textContent.toLowerCase();
        const destination = row.cells[3].textContent.toLowerCase();
        const status = row.cells[4].textContent.toLowerCase();

        const searchMatch =
            !search ||
            vehicle.includes(search) ||
            date.includes(search) ||
            destination.includes(search) ||
            status.includes(search);

        const vehicleMatch =
            !vehicleSel || vehicle.includes(vehicleSel);

        row.style.display = (searchMatch && vehicleMatch) ? "" : "none";
    });
}

/*  WORKING MODAL FUNCTIONS*/

function viewReserveDetail(reserve) {

    document.getElementById('detailReserveID').textContent = "#" + String(reserve.id).padStart(4, '0');
    document.getElementById('detailVehicle').textContent = escapeHtml(reserve.vehicle);
    document.getElementById('detailModel').textContent = escapeHtml(reserve.model);
    
    document.getElementById('detailDate').textContent =
        reserve.date ? new Date(reserve.date).toLocaleDateString() : "-";

    document.getElementById('detailDestination').textContent = escapeHtml(reserve.destination);
        const statusEl = document.getElementById('detailStatus');
    const status = reserve.status;

    // Set text
    statusEl.textContent = status;

    // Reset classes
    statusEl.className = "px-3 py-1 rounded-full text-xs font-semibold ml-2";

    // Add correct color
    if (status === "Approved") {
        statusEl.classList.add("bg-green-100", "text-green-800");
    } else if (status === "Pending") {
        statusEl.classList.add("bg-yellow-100", "text-yellow-800");
    } else {
        statusEl.classList.add("bg-red-100", "text-red-800");
    }

    // Show modal
    const modal = document.getElementById('detailReserveModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDetailReserveModal() {
    const modal = document.getElementById('detailReserveModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener("DOMContentLoaded", () => {
    const pageTitle = document.getElementById("pageTitle");
    if (pageTitle) pageTitle.textContent = "My Reservation";
});

</script>



</body>
</html>
