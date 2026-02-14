<?php
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vehicle_id = intval($_POST['vehicle_id']);
    $driver_id  = intval($_POST['driver_id']);
    $dispatch_date = $_POST['start_datetime'];
    $return_date   = $_POST['end_datetime'];

    if (!$vehicle_id || !$driver_id || !$dispatch_date) {
        die("Error: Missing required fields.");
    }

    if ($return_date && strtotime($return_date) <= strtotime($dispatch_date)) {
        die("Error: Return time must be after dispatch time.");
    }

    if (!isVehicleAvailableBetween($vehicle_id, $dispatch_date, $return_date)) {
        die("Vehicle not available.");
    }

    if (!isDriverAvailableBetween($driver_id, $dispatch_date, $return_date)) {
        die("Driver not available.");
    }

    $tracking_id = 'DSP-' . strtoupper(substr(md5(time()), 0, 6));

    $stmt = $conn->prepare("
        INSERT INTO dispatches
        (tracking_id, vehicle_id, driver_id, dispatch_date, return_date, status)
        VALUES (?, ?, ?, ?, ?, 'Assigned')
    ");

    $stmt->bind_param(
        "siiss",
        $tracking_id,
        $vehicle_id,
        $driver_id,
        $dispatch_date,
        $return_date
    );

    if ($stmt->execute()) {
        header("Location: dispatch-management.php");
        exit;
    } else {
        die("Error: " . $stmt->error);
    }
}


$trips  = getDispatches();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trip Management</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
<?php include '../includes/sidebar.php'; ?>
<div class="ml-0 md:ml-[280px] min-h-screen">
    <?php include '../includes/header.php'; ?>

    <main class="p-6">
        <div class="mb-6 text-gray-600">Record and track all vehicle trips</div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <?php
            $statuses = [
                'Pending'=>'blue',
                'Assigned'=>'yellow',
                'On Duty'=>'primary-green',
                'Completed'=>'green',
                'Cancelled'=>'red'
            ];
            foreach ($statuses as $status=>$color):
                $count = count(array_filter($trips, fn($r)=>($r['status']??'')==$status));
            ?>
            <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-<?php echo $color; ?>-500">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-600 text-sm font-medium"><?php echo $status; ?></div>
                        <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $count; ?></div>
                    </div>
                    <div class="w-12 h-12 bg-<?php echo $color; ?>-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-<?php echo $status=='Cancelled'?'times-circle':($status=='Completed'?'check-double':'clipboard-check'); ?> text-<?php echo $color; ?>-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Trips Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900"><i class="fas fa-truck text-primary-green"></i> Trip Records</h2>
                <button class="px-4 py-2 bg-green-600 text-white rounded-md" onclick="openNewTripModal()">+ New Trip</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">ID</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Start</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">End</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Purpose</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($trips as $t): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-4 text-sm text-primary-green">#<?php echo $t['tracking_id']; ?>
Replace:
<?php echo str_pad($t['id'],4,'0',STR_PAD_LEFT); ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo $t['plate'].' ('.$t['type'].')'; ?>
</td>
                            <td class="px-5 py-4 text-sm"><?php echo $t['driver'] ?? '<span class="text-gray-400 italic">Not assigned</span>'; ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo date('M d, Y H:i', strtotime($t['dispatch_date'])); ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo date('M d, Y H:i', strtotime($t['return_date'])); ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo $t['route'] ?? '—'; ?>
</td>
                            <td class="px-5 py-4 text-sm">
                                <?php $color = $statuses[$t['status']] ?? 'gray'; ?>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800"><?php echo $t['status']; ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- New Trip Modal -->
<div id="newTripModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-11/12 max-w-2xl p-6">
        <h3 class="text-xl font-bold mb-4">Create New Trip</h3>
        <form method="POST" action="trip-management.php">
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Vehicle *</label>
                <select name="vehicle_id" id="tripVehicle" required>
                    <option value="" disabled selected>Select date/time first</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Driver (optional)</label>
                <select name="driver_id" id="tripDriver">
                    <option value="">-- Select date/time first --</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Start Time *</label>
                <input type="datetime-local" name="start_datetime" id="tripStart" required class="w-full border px-3 py-2 rounded-md">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">End Time *</label>
                <input type="datetime-local" name="end_datetime" id="tripEnd" required class="w-full border px-3 py-2 rounded-md">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Purpose *</label>
                <input type="text" name="purpose" required class="w-full border px-3 py-2 rounded-md">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" class="w-full border px-3 py-2 rounded-md"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeNewTripModal()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary-green-600 text-white rounded">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewTripModal() {
    const modal = document.getElementById('newTripModal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
    document.getElementById('tripStart').value = '';
    document.getElementById('tripEnd').value = '';
    document.getElementById('tripVehicle').innerHTML = '<option value="" disabled selected>Select date/time first</option>';
    document.getElementById('tripDriver').innerHTML = '<option value="" disabled selected>-- Select date/time first --</option>';
}

function closeNewTripModal() {
    const modal = document.getElementById('newTripModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
}

function updateAvailability() {
    const start = document.getElementById('tripStart').value;
    const end   = document.getElementById('tripEnd').value;
    if (!start || !end) return;

    fetch(`trip-management.php?action=getAvailability&start=${start}&end=${end}`)
        .then(res=>res.json())
        .then(data=>{
            const vehicleSelect = document.getElementById('tripVehicle');
            const driverSelect  = document.getElementById('tripDriver');

            vehicleSelect.innerHTML = '<option value="">Select Vehicle</option>';
            data.vehicles.forEach(v=>{
                vehicleSelect.innerHTML += `<option value="${v.id}">${v.plate} (${v.type})</option>`;
            });
            driverSelect.innerHTML = '<option value="">-- None --</option>';
            data.drivers.forEach(d=>{
                driverSelect.innerHTML += `<option value="${d.user_id}">${d.full_name}</option>`;
            });
        });
}

document.getElementById('tripStart').addEventListener('change', updateAvailability);
document.getElementById('tripEnd').addEventListener('change', updateAvailability);
</script>
</body>
</html>
