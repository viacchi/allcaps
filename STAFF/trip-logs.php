<?php
include '../staff-includes/staff-functions.php';
$trips = getLogs(); // ensure this function returns an array of trips
$total = count($trips);

// compute distance and longest trip like you already had
$distance = [];
foreach ($trips as $t) {
    if (!empty($t['distance'])) {
        preg_match_all('/\d+/', $t['distance'], $d);
        if (!empty($d[0])) {
            $dist = (int)implode('', $d[0]);
            $distance[] = $dist;
        }
    }
}

$longestTrip = null;
$maxMinutes = -1;
foreach ($trips as $t) {
    $min = durationToMinutes($t['duration'] ?? null); // keep your helper
    if ($min > $maxMinutes) {
        $maxMinutes = $min;
        $longestTrip = $t;
    }
}
$longestLabel = $longestTrip ? htmlspecialchars($longestTrip['duration']) : '—';
$avgDistance = count($distance) ? round(array_sum($distance) / count($distance)) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Trip Logs - Logistics Staff</title>
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
  <style>
    /* optional: ensure modal sits above header/sidebar */
    .modal-backdrop { z-index: 9999; }
  </style>
</head>
<body class="bg-gray-50 font-sans">
  <?php include '../staff-includes/staff-sidebar.php'; ?>

  <div class="ml-0 md:ml-[280px] min-h-screen">
    <?php include '../staff-includes/staff-header.php'; ?>

    <main class="p-6">
      <div class="mb-8">
        <p class="text-gray-600 mt-2">Records completed trips by staff for documentation and mileage tracking</p>
      </div>

      <!-- KPI cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-amber-500">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-600 text-sm font-medium">Total Trips</div>
              <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $total; ?></div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-road text-amber-600 text-xl"></i>
            </div>
          </div>
          <div class="text-xs font-medium text-amber-600"><i class="fas fa-route"></i> Trips average</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-rose-500">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-600 text-sm font-medium">Average Distance</div>
              <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $avgDistance ? number_format($avgDistance) . ' km' : '—'; ?></div>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <i class="fa-solid fa-chart-line text-rose-600 text-xl"></i>
            </div>
          </div>
          <div class="text-xs font-medium text-rose-600"><i class="fa-solid fa-chart-column"></i> Covered distance</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-lime-500">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-600 text-sm font-medium">Longest Trip</div>
              <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $longestLabel; ?></div>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <i class="fa-solid fa-flag-checkered text-lime-600 text-xl"></i>
            </div>
          </div>
          <div class="text-xs font-medium text-lime-600"><i class="fa-solid fa-medal"></i> Trip Duration</div>
        </div>
      </div>

      <!-- Controls & table container -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
          <h2 class="text-lg font-bold text-gray-900">Trip Logs</h2>
          <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition inline-flex items-center gap-2" onclick="openAddTripModal()">
              <i class="fas fa-plus"></i> Add Trip
            </button>
          </div>
        </div>

        <!-- Search & filters -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex gap-3 flex-wrap">
            <input type="text" id="searchInput" placeholder="Search by plate, date, or remarks..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">
            <select id="vehicleFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
              <option value="">All Vehicle</option>
              <?php
                // produce list of plates dynamically
                $vehicle = array_unique(array_map(fn($r) => $r['vehicle'], $trips));
                foreach ($vehicle as $v) {
                  echo '<option value="'.htmlspecialchars($v).'">'.htmlspecialchars($v).'</option>';
                }
              ?>
            </select>

            </select>
          </div>
        </div>

        <!-- Table: horizontal scroll and vertical max height -->
        <div class="overflow-x-auto">
          <div class="max-h-[52vh] overflow-auto">
            <table class="w-full border-collapse" id="LogsTable">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Trip ID</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Start</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Destination</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Distance</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Duration</th>
                  <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                </tr>
              </thead>
              <tbody id="tableBody">
                <?php foreach ($trips as $i => $row): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors" data-index="<?php echo $i; ?>">
                  <td class="px-5 py-4 text-sm font-semibold text-primary-green">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                  <td class="px-5 py-4 text-sm">
                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['vehicle']); ?></div>
                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['model'] ?? ''); ?></div>
                  </td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars(date('M d, Y', strtotime($row['date']))); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo $row['start'] ? '<i class="fas fa-route mr-1"></i>'.htmlspecialchars($row['start']) : '<span class="text-gray-400">-</span>'; ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo $row['destination'] ? '<i class="fas fa-route mr-1"></i>'.htmlspecialchars($row['destination']) : '<span class="text-gray-400">-</span>'; ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['distance']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['duration']); ?></td>
                  <td class="px-5 py-4 text-sm text-center">
                    <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 inline-flex items-center gap-1.5" onclick='viewTripsDetail(<?php echo json_encode($row, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>)'>
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

  <!-- ADD TRIP MODAL -->
  <div id="addTripModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 id="addTripTitle" class="text-xl font-bold text-gray-900">Add Trip</h3>
        <button onclick="closeAddTripModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>

      <form id="addTripForm" class="space-y-4" onsubmit="saveTrip(event)">
        <input type="hidden" id="tripIndex" value="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle</label>
            <input type="text" id="tripVehicle" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., ABC-1234" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Model</label>
            <input type="text" id="tripModel" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., Hiace" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
            <input type="date" id="tripDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Start</label>
            <input type="text" id="tripStart" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="Start location" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Destination</label>
            <input type="text" id="tripDestination" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="Destination" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Distance</label>
            <input type="text" id="tripDistance" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., 120 km" required>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Duration</label>
            <input type="text" id="tripDuration" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., 2h 30m" required>
          </div>

        </div>
        <div class="flex gap-3 justify-end">
          <button type="button" onclick="closeAddTripModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-primary-green text-white rounded-md font-semibold">Save Trip</button>
        </div>
      </form>
    </div>
  </div>

  <!-- DETAIL TRIP MODAL -->
  <div id="detailTripModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
      <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
        <h3 class="text-xl font-bold">Trip Logs Details</h3>
        <button onclick="closeDetailTripModal()" class="text-white hover:text-gray-200 text-2xl"><i class="fas fa-times"></i></button>
      </div>
      <div class="p-6 space-y-6">
        <div class="bg-gray-50 rounded-lg p-4">
          <h4 class="text-sm font-bold text-gray-900 mb-3">Trip Logs Information</h4>
          <div class="space-y-2 text-sm text-gray-700">
            <div><span class="text-gray-600">Trip ID:</span> <span id="detailTripID" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Vehicle:</span> <span id="detailVehicle" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Model:</span> <span id="detailModel" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Date:</span> <span id="detailDate" class="inline-block px-2 py-1 rounded-full text-xs font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Start:</span><span id="detailStart" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Destination:</span> <span id="detailDestination" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Distance:</span> <span id="detailDistance" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Duration:</span> <span id="detailDuration" class="font-semibold ml-2">-</span></div>
          </div>
        </div>

        <div class="flex gap-3 mt-4">
          <button onclick="closeDetailTripModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Utility: escape html when injecting strings
    function escapeHtml(str) {
      if (str === null || str === undefined) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    // Filtering table rows
    function filterTable() {
      const q = document.getElementById('searchInput').value.toLowerCase();
      const plateFilter = document.getElementById('plateFilter').value;
      const rows = document.querySelectorAll('#tableBody tr');
      rows.forEach(row => {
        const vehicle = (row.querySelector('td:nth-child(2) .font-semibold') || {textContent:''}).textContent.toLowerCase();
        const model = (row.querySelector('td:nth-child(2) .text-xs') || {textContent:''}).textContent.toLowerCase();
        const matches = (!q || vehicle.includes(q) || model.includes(q)) && (!plateFilter || vehicle.includes(plateFilter.toLowerCase()));
        row.style.display = matches ? '' : 'none';
      });
    }

    // ADD modal controls
    function openAddTripModal() {
      document.getElementById('addTripModal').classList.remove('hidden');
      document.getElementById('addTripModal').classList.add('flex');
    }
    function closeAddTripModal() {
      document.getElementById('addTripModal').classList.remove('flex');
      document.getElementById('addTripModal').classList.add('hidden');
    }

    // Save trip (demo: just alert and close - replace with AJAX)
    function saveTrip(e) {
      e.preventDefault();
      // Collect form values (you can submit via AJAX here)
      const vehicle = document.getElementById('tripVehicle').value;
      // ...other fields
      alert('Trip saved (demo). Vehicle: ' + vehicle);
      closeAddTripModal();
      // optionally reload page or update DOM
    }

    // DETAIL modal controls
    function viewTripsDetail(trip) {
      // trip is an object passed via JSON on button click
      // fill values safely
      document.getElementById('detailTripID').textContent = escapeHtml(trip.id || '-');
      document.getElementById('detailVehicle').textContent = escapeHtml(trip.vehicle || '-');
      document.getElementById('detailModel').textContent = escapeHtml(trip.model || '-');
      document.getElementById('detailDate').textContent = trip.date ? (new Date(trip.date)).toLocaleDateString() : '-';
      document.getElementById('detailStart').textContent = escapeHtml(trip.start || '-');
      document.getElementById('detailDestination').textContent = escapeHtml(trip.destination || '-');
      document.getElementById('detailDistance').textContent = escapeHtml(trip.distance || '-');
      document.getElementById('detailDuration').textContent = escapeHtml(trip.duration || '-');

      document.getElementById('detailTripModal').classList.remove('hidden');
      document.getElementById('detailTripModal').classList.add('flex');
    }

    function closeDetailTripModal() {
      document.getElementById('detailTripModal').classList.remove('flex');
      document.getElementById('detailTripModal').classList.add('hidden');
    }

    // Close modals on clicking outside and pressing Esc
    document.addEventListener('click', (e) => {
      // Close add modal when backdrop clicked
      const addModal = document.getElementById('addTripModal');
      if (addModal && !addModal.classList.contains('hidden') && e.target === addModal) closeAddTripModal();

      const detailModal = document.getElementById('detailTripModal');
      if (detailModal && !detailModal.classList.contains('hidden') && e.target === detailModal) closeDetailTripModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        if (!document.getElementById('addTripModal').classList.contains('hidden')) closeAddTripModal();
        if (!document.getElementById('detailTripModal').classList.contains('hidden')) closeDetailTripModal();
      }
    });

    function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const vehicleSel = document.getElementById('vehicleFilter').value.toLowerCase();

  document.querySelectorAll('#tableBody tr').forEach(tr => {
    const date = tr.cells[0].textContent.toLowerCase();
    const vehicle = tr.cells[1].textContent.toLowerCase();
    const remarks = tr.cells[6].textContent.toLowerCase();

    const simpleSearch = (date + ' ' + vehicle + ' ' + remarks).includes(q);
    const vehicleMatch = !vehicleSel || vehicle.includes(vehicleSel);

    tr.style.display = (simpleSearch && vehicleMatch) ? '' : 'none';
  });
}


    // ensure page title element exists on header include, otherwise skip
    document.addEventListener('DOMContentLoaded', () => {
      const pageTitle = document.getElementById('pageTitle');
      if (pageTitle) pageTitle.textContent = 'Trip Logs';
    });
  </script>
</body>
</html>
