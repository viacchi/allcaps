<?php

include '../staff-includes/staff-functions.php';

$entries = getTrack(); 
$total = 0.0;
foreach ($entries as $e) {
    $raw = isset($e['cost']) ? $e['cost'] : '0';
    // keep digits and dot only (remove currency/commas). This handles "2,1600.00" -> "21600.00"
    $num = preg_replace('/[^0-9.]/', '', $raw);
    $total += (float)$num;
}

// Compute a simple average mileage display (attempt to extract numeric values)
$mileages = [];
foreach ($entries as $e) {
    if (!empty($e['mileage'])) {
        // extract numbers from mileage string
        preg_match_all('/\d+/', $e['mileage'], $m);
        if (!empty($m[0])) {
            // join digits into a single number estimate
            $mile = (int)implode('', $m[0]);
            $mileages[] = $mile;
        }
    }
}
$avgMileage = count($mileages) ? round(array_sum($mileages) / count($mileages)) : 0;
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Fuel, Mileage & Expense Tracking - Logistics Staff (Fixed)</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Make sure modal overlays everything */
    .modal-backdrop { z-index: 1200; }
    /* Small tweak so table text doesn't wrap awkwardly */
    table td, table th { white-space: nowrap; }
  </style>
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
  <?php include '../staff-includes/staff-sidebar.php'; ?>

  <div class="min-h-screen ml-0 md:ml-[280px]">

    <!-- Header -->
    <?php include '../staff-includes/staff-header.php'; ?>

    <main class="p-6">
      <div class="mb-6">
        <p class="text-gray-600 mt-2">Tracks fuel consumption, mileage efficiency and vehicle-related expenses</p>
      </div>

      <!-- KPI cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-purple-500">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-600 text-sm font-medium">Total Fuel Cost</div>
              <div class="text-3xl font-bold text-gray-900 my-2">₱<?php echo number_format($total, 2); ?></div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-tint text-purple-600 text-xl"></i>
            </div>
          </div>
          <div class="text-xs font-medium text-purple-600"><i class="fas fa-gas-pump"></i> Fuel average</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-600 text-sm font-medium">Average Mileage</div>
              <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $avgMileage ? number_format($avgMileage) . ' km' : '—'; ?></div>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-road text-blue-600 text-xl"></i>
            </div>
          </div>
          <div class="text-xs font-medium text-blue-600"><i class="fas fa-map-location-dot"></i> Covered Mileage</div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-600 text-sm font-medium">Entries</div>
              <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($entries); ?></div>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <i class="fas fa-list text-green-600 text-xl"></i>
            </div>
          </div>
          <div class="text-xs font-medium text-green-600"><i class="fas fa-percent"></i> Rate Trends</div>
        </div>
      </div>

      <!-- Controls -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
          <h2 class="text-lg font-bold text-gray-900">Fuel, Mileage & Expense Tracking</h2>
          <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition inline-flex items-center gap-2" onclick="openAddEntryModal()">
              <i class="fas fa-plus"></i> Add Entry
            </button>
          </div>
        </div>

        <!-- Search & filters -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex gap-3 flex-wrap">
            <input type="text" id="searchInput" placeholder="Search by plate, date, or remarks..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">
            <select id="fuelFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
              <option value="">All Fuel Types</option>
              <option value="Gasoline">Gasoline</option>
              <option value="Diesel">Diesel</option>
              <option value="Hybrid">Hybrid</option>
              <option value="Bio-Diesel">Bio-Diesel</option>
            </select>
            <select id="plateFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
              <option value="">All Plates</option>
              <?php
                // produce list of plates dynamically
                $plates = array_unique(array_map(fn($r) => $r['plate'], $entries));
                foreach ($plates as $p) {
                  echo '<option value="'.htmlspecialchars($p).'">'.htmlspecialchars($p).'</option>';
                }
              ?>
            </select>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full border-collapse" id="TrackTable">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Plate</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Fuel Type</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Liters</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Cost</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Mileage</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Remarks</th>
                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              <?php foreach ($entries as $i => $row): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors" data-index="<?php echo $i; ?>">
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars(date('M d, Y', strtotime($row['date']))); ?></td>
                  <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo htmlspecialchars($row['plate']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['fuel-type']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['liters']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700">₱ <?php echo htmlspecialchars($row['cost']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['mileage']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['remarks']); ?></td>
                  <td class="px-5 py-4 text-sm text-center">
                    <div class="inline-flex gap-2">
                      <button onclick="editEntry(<?php echo $i; ?>)" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 inline-flex items-center gap-1.5">
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <button onclick="promptDelete(<?php echo $i; ?>)" class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 inline-flex items-center gap-1.5">
                        <i class="fas fa-trash"></i> Delete
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

  <!-- ENTRY MODAL (add/edit) -->
  <div id="entryModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 id="entryModalTitle" class="text-xl font-bold text-gray-900">Add Entry</h3>
        <button onclick="closeEntryModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>

      <form id="entryForm" class="space-y-4" onsubmit="saveEntry(event)">
        <input type="hidden" id="entryIndex" value="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
            <input type="date" id="entryDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Plate Number</label>
            <input type="text" id="entryPlate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., ABC-1234" required>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Fuel Type</label>
            <select id="entryFuelType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
              <option value="">Select</option>
              <option>Gasoline</option>
              <option>Diesel</option>
              <option>Hybrid</option>
              <option>Bio-Diesel</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Liters</label>
            <input type="text" id="entryLiters" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., 32.8 L">
          </div>
          <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Entry Image</label>
        <input type="file" id="entryImage" accept="image/*"
            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                   focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
        </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Cost</label>
            <input type="text" id="entryCost" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., 21600.00">
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Mileage</label>
            <input type="text" id="entryMileage" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g., 125,450 km">
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Remarks</label>
          <textarea id="entryRemarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"></textarea>
        </div>

        <div class="flex gap-3 justify-end">
          <button type="button" onclick="closeEntryModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-primary-green text-white rounded-md font-semibold">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- CONFIRM DELETE MODAL -->
  <div id="confirmModal" class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md shadow-2xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold">Confirm Delete</h3>
        <button onclick="closeConfirmModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>
      <p class="text-gray-700 mb-6" id="confirmMessage">Are you sure you want to delete this entry?</p>
      <div class="flex gap-3 justify-end">
        <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold">Cancel</button>
        <button onclick="confirmDeleteNow()" class="px-4 py-2 bg-red-500 text-white rounded-md font-semibold">Delete</button>
      </div>
    </div>
  </div>

  <script>
    // Data passed from PHP: entries array
    const entries = <?php echo json_encode($entries, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>;
    let deletingIndex = null;

    // Set page title (header include should have element with id pageTitle)
    document.addEventListener('DOMContentLoaded', () => {
      const pageTitle = document.getElementById('pageTitle');
      if (pageTitle) pageTitle.textContent = 'Fuel, Mileage & Expense Tracking';
    });

    // OPEN add modal
    function openAddEntryModal(){
      document.getElementById('entryModalTitle').textContent = 'Add Entry';
      document.getElementById('entryIndex').value = '';
      // clear form
      document.getElementById('entryForm').reset();
      showModal('entryModal');
    }

    // EDIT entry - populate form with data from entries array
    function editEntry(index){
      const row = entries[index];
      if (!row) return;
      document.getElementById('entryModalTitle').textContent = 'Edit Entry';
      document.getElementById('entryIndex').value = index;
      document.getElementById('entryDate').value = row.date || '';
      document.getElementById('entryPlate').value = row.plate || '';
      document.getElementById('entryFuelType').value = row['fuel-type'] || '';
      document.getElementById('entryLiters').value = row.liters || '';
      document.getElementById('entryCost').value = row.cost || '';
      document.getElementById('entryMileage').value = row.mileage || '';
      document.getElementById('entryRemarks').value = row.remarks || '';
      showModal('entryModal');
    }

    function saveEntry(e){
      e.preventDefault();
      const idx = document.getElementById('entryIndex').value;
      const newData = {
        date: document.getElementById('entryDate').value,
        plate: document.getElementById('entryPlate').value,
        'fuel-type': document.getElementById('entryFuelType').value,
        liters: document.getElementById('entryLiters').value,
        cost: document.getElementById('entryCost').value,
        mileage: document.getElementById('entryMileage').value,
        remarks: document.getElementById('entryRemarks').value
      };

      // Simulate save: either update existing row or add new row (frontend only)
      if (idx !== '') {
        entries[idx] = newData;
        updateRowInTable(idx, newData);
        alert('Entry updated (frontend)');
      } else {
        entries.push(newData);
        appendRowToTable(entries.length - 1, newData);
        alert('Entry added (frontend)');
      }

      closeEntryModal();
    }

    // Delete flow
    function promptDelete(index){
      deletingIndex = index;
      document.getElementById('confirmMessage').textContent = 'Are you sure you want to delete this entry?';
      showModal('confirmModal');
    }
    function confirmDeleteNow(){
      if (deletingIndex === null) { closeConfirmModal(); return; }
      // remove from front-end array and remove row from DOM
      entries.splice(deletingIndex, 1);
      const tr = document.querySelector('#tableBody tr[data-index="'+deletingIndex+'"]');
      if (tr) tr.remove();
      // re-index remaining rows' data-index attributes
      document.querySelectorAll('#tableBody tr').forEach((r, i) => r.setAttribute('data-index', i));
      alert('Entry deleted (frontend)');
      deletingIndex = null;
      closeConfirmModal();
    }

    // Utility: update table row content after edit
    function updateRowInTable(index, data){
      const tr = document.querySelector('#tableBody tr[data-index="'+index+'"]');
      if (!tr) return;
      tr.cells[0].textContent = formatDateForDisplay(data.date);
      tr.cells[1].innerHTML = '<strong>'+escapeHtml(data.plate)+'</strong>';
      tr.cells[2].textContent = data['fuel-type'] || '';
      tr.cells[3].textContent = data.liters || '';
      tr.cells[4].textContent = '₱ ' + (data.cost || '');
      tr.cells[5].textContent = data.mileage || '';
      tr.cells[6].textContent = data.remarks || '';
    }

    // Utility: append a new row to table
    function appendRowToTable(index, data){
      const tbody = document.getElementById('tableBody');
      const tr = document.createElement('tr');
      tr.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';
      tr.setAttribute('data-index', index);
      tr.innerHTML = `
        <td class="px-5 py-4 text-sm text-gray-700">${formatDateForDisplay(data.date)}</td>
        <td class="px-5 py-4 text-sm text-gray-700"><strong>${escapeHtml(data.plate)}</strong></td>
        <td class="px-5 py-4 text-sm text-gray-700">${escapeHtml(data['fuel-type']||'')}</td>
        <td class="px-5 py-4 text-sm text-gray-700">${escapeHtml(data.liters||'')}</td>
        <td class="px-5 py-4 text-sm text-gray-700">₱ ${escapeHtml(data.cost||'')}</td>
        <td class="px-5 py-4 text-sm text-gray-700">${escapeHtml(data.mileage||'')}</td>
        <td class="px-5 py-4 text-sm text-gray-700">${escapeHtml(data.remarks||'')}</td>
        <td class="px-5 py-4 text-sm text-center">
          <div class="inline-flex gap-2">
            <button onclick="editEntry(${index})" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 inline-flex items-center gap-1.5">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button onclick="promptDelete(${index})" class="px-3 py-1.5 bg-red-500 text-white rounded-md text-xs font-semibold hover:bg-red-600 inline-flex items-center gap-1.5">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </td>
      `;
      tbody.appendChild(tr);
    }

    // Modal helpers
    function showModal(id){
      const el = document.getElementById(id);
      if (!el) return;
      el.classList.remove('hidden');
      // ensure it's flex container for centering
      el.classList.add('flex');
      // disable page scroll
      document.documentElement.style.overflow = 'hidden';
    }
    function closeEntryModal(){
      const el = document.getElementById('entryModal');
      if (!el) return;
      el.classList.add('hidden');
      el.classList.remove('flex');
      document.documentElement.style.overflow = '';
    }
    function closeConfirmModal(){
      const el = document.getElementById('confirmModal');
      if (!el) return;
      el.classList.add('hidden');
      el.classList.remove('flex');
      document.documentElement.style.overflow = '';
      deletingIndex = null;
    }

    // Click outside to close modals (safe checks)
    document.addEventListener('click', function(e){
      const entryModal = document.getElementById('entryModal');
      if (entryModal && !entryModal.classList.contains('hidden') && e.target === entryModal) closeEntryModal();
      const confirmModal = document.getElementById('confirmModal');
      if (confirmModal && !confirmModal.classList.contains('hidden') && e.target === confirmModal) closeConfirmModal();
    });

    // Simple filter function for the table
    function filterTable(){
      const q = document.getElementById('searchInput').value.toLowerCase();
      const fuel = document.getElementById('fuelFilter').value;
      const plateSel = document.getElementById('plateFilter').value;
      document.querySelectorAll('#tableBody tr').forEach(tr => {
        const date = tr.cells[0].textContent.toLowerCase();
        const plate = tr.cells[1].textContent.toLowerCase();
        const fuelType = tr.cells[2].textContent;
        const remarks = tr.cells[6].textContent.toLowerCase();
        const matches = (date + ' ' + plate + ' ' + remarks).includes(q)
                        && (!fuel || fuelType === fuel)
                        && (!plateSel || plate.includes(plateSel.toLowerCase()));
        tr.style.display = matches ? '' : 'none';
      });
    }

    // small helpers
    function formatDateForDisplay(d){
      if (!d) return '';
      try {
        const dt = new Date(d);
        if (isNaN(dt)) return d;
        return dt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
      } catch(e){
        return d;
      }
    }
    function escapeHtml(s){
      if (!s) return '';
      return String(s).replaceAll('&', '&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'", '&#39;');
    }
  </script>
</body>
</html>
