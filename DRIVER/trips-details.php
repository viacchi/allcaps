<?php


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Assigned Trips - Logistics Driver</title>
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
    <?php include 'driver-includes/driver-sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <!-- Header -->
        <?php include 'driver-includes/driver-header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Optional Subtitle -->
            <div class="mb-6">
                <p class="text-gray-600"> Shows all trips assigned to the driver </p>
            </div>

        
         <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full border-collapse" id="TripTable">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Trip ID</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Pickup Location</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Destination</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                  <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Status</th>
                  <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach ($trip as $i => $row): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors" data-index="<?php echo $i; ?>">
                  <td class="px-5 py-4 text-sm font-semibold text-primary-green">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                  <td class="px-5 py-4 text-sm">
                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['vehicle']); ?></div>
                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($row['model'] ?? ''); ?></div>
                  </td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo $row['start'] ? '<i class="fa-solid fa-map-pin"></i>'.htmlspecialchars($row['pickup-location']) : '<span class="text-gray-400">-</span>'; ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo $row['destination'] ? '<i class="fas fa-route mr-1"></i>'.htmlspecialchars($row['destination']) : '<span class="text-gray-400">-</span>'; ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['distance']); ?></td>
                  <td class="px-5 py-4 text-sm text-gray-700"><?php echo htmlspecialchars(date('M d, Y', strtotime($row['date']))); ?></td>
                   <td class="status-cell px-5 py-4 text-sm">
                                  <span class="status-cell px-3 py-1 rouded-full text-xs font-semibold
                                    <?php
                                      echo $row['status'] === 'Resolved' ? 'bg-green-100 text-green-800' :
                                           ($row['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-red-100 text-red-800');
                                    ?>">
                     <?php echo trim($row['status']); ?>
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
            <div><span class="text-gray-600">Pickup Location:</span><span id="detailPickup" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Destination:</span> <span id="detailDestination" class="font-semibold ml-2">-</span></div>
            <div><span class="text-gray-600">Date:</span> <span id="detailDate" class="inline-block px-2 py-1 rounded-full text-xs font-semibold ml-2">-</span></div>
            <div><strong>Status:</strong><span id="detailStatus" class="px-3 py-1 rounded-full text-xs font-semibold">-</span></div>
          </div>
        </div>

        <div class="flex gap-3 mt-4">
          <button onclick="closeDetailTripModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300">Close</button>
        </div>
      </div>
    </div>
</div>

<script>
    
    // DETAIL modal controls
    function viewTripsDetail(trip) {
      // trip is an object passed via JSON on button click
      // fill values safely
      document.getElementById('detailTripID').textContent = escapeHtml(trip.id || '-');
      document.getElementById('detailVehicle').textContent = escapeHtml(trip.vehicle || '-');
      document.getElementById('detailModel').textContent = escapeHtml(trip.model || '-');
      document.getElementById('detailPickup').textContent = escapeHtml(trip.pickup || '-');
      document.getElementById('detailDestination').textContent = escapeHtml(trip.destination || '-');
      document.getElementById('detailDate').textContent = trip.date ? (new Date(trip.date)).toLocaleDateString() : '-';
            <div><strong>Status:</strong><span id="detailStatus" class="px-3 py-1 rounded-full text-xs font-semibold">-</span></div>t
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

    

</script>