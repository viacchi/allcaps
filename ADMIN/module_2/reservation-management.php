<?php
include '../includes/functions.php';

// --------------------
// AJAX: get available vehicles/drivers
// --------------------
if (isset($_GET['action']) && $_GET['action'] === 'getAvailability') {
    $start = $_GET['start'] ?? null;
    $end   = $_GET['end'] ?? null;

    $vehicles = getAvailableVehiclesBetween($start, $end);
    $drivers  = getAvailableDriversBetween($start, $end);

    echo json_encode(['vehicles'=>$vehicles,'drivers'=>$drivers]);
    exit;
}

// --------------------
// POST: assign driver or create new reservation
// --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vehicle_id = intval($_POST['vehicle_id']);
    $driver_id  = isset($_POST['driver_id']) && $_POST['driver_id'] !== '' 
                ? intval($_POST['driver_id']) 
                : null;
    $start_datetime = $_POST['start_datetime'];
    $end_datetime   = $_POST['end_datetime'];
    $purpose    = $_POST['purpose'];
    $notes      = $_POST['notes'];

    // 1️⃣ Validate inputs first
    if (!$vehicle_id || !$start_datetime || !$end_datetime || !$purpose) {
        die("Error: Missing required fields.");
    }

    // 2️⃣ Check availability BEFORE INSERT
    if (!isVehicleAvailable($vehicle_id, $start_datetime, $end_datetime)) {
        die("Error: Vehicle not available during this period.");
    }

    if ($driver_id && !isDriverAvailable($driver_id, $start_datetime, $end_datetime)) {
        die("Error: Driver not available during this period.");
    }

    // 3️⃣ Determine status
    $status = $driver_id ? 'Assigned' : 'Pending Dispatch';

    // 4️⃣ Safe transaction
    $conn->begin_transaction();
    try {
$driver_id = isset($_POST['driver_id']) && $_POST['driver_id'] !== '' 
             ? intval($_POST['driver_id']) 
             : null;


        // 4a. Insert reservation
        $stmt = $conn->prepare("
            INSERT INTO reservations
            (vehicle_id, driver_id, start_datetime, end_datetime, purpose, notes, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssss", 
            $vehicle_id, 
            $driver_id, 
            $start_datetime, 
            $end_datetime, 
            $purpose, 
            $notes,
            $status
        );
        $stmt->execute();
        $newReservationId = $stmt->insert_id;

        // 4b. Insert trip if driver assigned
        if ($driver_id) {
            $tripStmt = $conn->prepare("
                INSERT INTO trips
                (reservation_id, vehicle_id, driver_id, dispatch_date, purpose, status)
                VALUES (?, ?, ?, ?, ?, 'Pending')
            ");

            $tripStmt->bind_param(
                "iiiss",
                $newReservationId,
                $vehicle_id,
                $driver_id,
                $start_datetime,
                $purpose
            );
            $tripStmt->execute();
        }

        $conn->commit();
        header("Location: reservation-management.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}
// --------------------
// FETCH DATA
// --------------------
$reservations = getReservations(); // join vehicles and drivers
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservation Management</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">

<?php include '../includes/sidebar.php'; ?>
<div class="ml-0 md:ml-[280px] min-h-screen">
    <?php include '../includes/header.php'; ?>

    <main class="p-6">
        <div class="mb-6 text-gray-600">Record and track all vehicle dispatches and reservations</div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <?php
            $statuses = ['Pending Dispatch'=>'blue','Assigned'=>'yellow','In Use'=>'primary-green','Completed'=>'green','Cancelled'=>'red'];
            foreach ($statuses as $status=>$color):
                $count = count(array_filter($reservations, fn($r)=>($r['status']??'')==$status));
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

        <!-- Reservations Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900"><i class="fas fa-tasks text-primary-green"></i> Reservation Records</h2>
                <button class="px-4 py-2 bg-green-600 text-white rounded-md" onclick="openNewReservationModal()">+ New Reservation</button>
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
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-4 text-sm text-primary-green">#RES-<?php echo str_pad($r['id'],4,'0',STR_PAD_LEFT); ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo $r['vehicle'].' ('.$r['type'].')'; ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo $r['driver'] ?? '<span class="text-gray-400 italic">Not assigned</span>'; ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo date('M d, Y H:i', strtotime($r['start_datetime'])); ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo date('M d, Y H:i', strtotime($r['end_datetime'])); ?></td>
                            <td class="px-5 py-4 text-sm"><?php echo $r['purpose']; ?></td>
                            <td class="px-5 py-4 text-sm">
                                <?php
                                $color = $statuses[$r['status']] ?? 'gray';
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-<?php echo $color; ?>-100 text-<?php echo $color; ?>-800"><?php echo $r['status']; ?></span>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <button onclick="viewDetails(<?php echo $r['id']; ?>)" class="px-2 py-1 bg-gray-200 rounded">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- New Reservation Modal -->
<div id="newReservationModal" 
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-11/12 max-w-2xl p-6">
        <h3 class="text-xl font-bold mb-4">Create New Reservation</h3>
        <form method="POST" action="reservation-management.php">
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Vehicle *</label>
<select name="vehicle_id" id="newVehicle" required>
    <option value="" disabled selected>Select date/time first</option>
</select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Driver (optional)</label>
<select name="driver_id" id="newDriver">
    <option value="">-- Select date/time first --</option>
</select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Start Time *</label>
                <input type="datetime-local" name="start_datetime" id="startDatetime" required class="w-full border px-3 py-2 rounded-md">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">End Time *</label>
                <input type="datetime-local" name="end_datetime" id="endDatetime" required class="w-full border px-3 py-2 rounded-md">
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
                <button type="button" onclick="closeNewReservationModal()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary-green text-white rounded">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewReservationModal() {
    const modal = document.getElementById('newReservationModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Reset form inputs
    document.getElementById('startDatetime').value = '';
    document.getElementById('endDatetime').value = '';
    document.getElementById('newVehicle').innerHTML = '<option value="" disabled selected>Select date/time first</option>';
    document.getElementById('newDriver').innerHTML = '<option value="" disabled selected>-- Select date/time first --</option>';
}

function closeNewReservationModal() {
    document.getElementById('newReservationModal').classList.add('hidden');
    document.getElementById('newReservationModal').classList.remove('flex');
}

function updateAvailability() {
    const start = document.getElementById('startDatetime').value;
    const end   = document.getElementById('endDatetime').value;
    if (!start || !end) return;

    const vehicleSelect = document.getElementById('newVehicle');
    const driverSelect  = document.getElementById('newDriver');

    vehicleSelect.innerHTML = '<option>Loading...</option>';
    driverSelect.innerHTML  = '<option>Loading...</option>';

    fetch(`reservation-management.php?action=getAvailability&start=${start}&end=${end}`)
        .then(res=>res.json())
        .then(data=>{
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

document.getElementById('startDatetime').addEventListener('change', updateAvailability);
document.getElementById('endDatetime').addEventListener('change', updateAvailability);
</script>
</body>
</html>
