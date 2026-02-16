<?php
include 'includes/functions.php';

$driver_id = getCurrentDriverId();
$activeTrip = getCurrentActiveTrip();
// Auto-select the vehicle if the driver is currently on a trip
$current_vehicle_id = $activeTrip ? $activeTrip['vehicle_id'] : null;

// Fetch vehicles for the dropdown
$vehicles = getVehicles();

$msg = '';
$msgType = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['log_fuel'])) {
    $vid = intval($_POST['vehicle_id']);
    $liters = floatval($_POST['liters']);
    $cost = floatval($_POST['cost']);
    $date = date('Y-m-d H:i:s'); // Current timestamp

    if ($vid && $liters > 0 && $cost > 0) {
        if (addFuelExpense($vid, $date, $liters, $cost, $driver_id)) {
            $msg = "Fuel expense logged successfully!";
            $msgType = "success";
        } else {
            $msg = "Database Error: Could not save fuel log.";
            $msgType = "error";
        }
    } else {
        $msg = "Please fill in all fields with valid numbers.";
        $msgType = "error";
    }
}

// Fetch ONLY this specific driver's past fuel logs (SAFELY)
$myLogs = [];
$driver_id_safe = intval($driver_id);
$logSql = "SELECT fe.*, v.plate, v.model 
           FROM fuel_expenses fe 
           LEFT JOIN vehicles v ON fe.vehicle_id = v.id 
           WHERE fe.driver_id = $driver_id_safe 
           ORDER BY fe.date DESC";

$result = $conn->query($logSql);

// Only fetch if the query was successful, preventing the boolean crash
if ($result) {
    $myLogs = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Fuel Logs - Driver</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'primary-green': '#2D7A5C' } } } }
    </script>
</head>
<body class="bg-gray-50 font-sans">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6 max-w-4xl mx-auto w-full">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Fuel Logs</h2>
            <p class="text-gray-500 mb-6 text-sm">Record your refuel expenses and track history.</p>

            <?php if ($msg): ?>
                <div class="mb-6 p-4 rounded-xl flex items-center gap-3 <?php echo $msgType == 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'; ?> border">
                    <i class="fas <?php echo $msgType == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> text-xl"></i>
                    <div class="text-sm font-semibold"><?php echo $msg; ?></div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-gas-pump text-primary-green"></i> New Fuel Entry
                        </h3>

                        <form method="POST" action="" class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Vehicle</label>
                                <select name="vehicle_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none appearance-none">
                                    <option value="" disabled <?php echo !$current_vehicle_id ? 'selected' : ''; ?>>Select Vehicle...</option>
                                    <?php foreach ($vehicles as $v): ?>
                                        <option value="<?php echo $v['id']; ?>" <?php echo ($current_vehicle_id == $v['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($v['plate'] . ' - ' . ($v['model'] ?? '')); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Amount Refueled (Liters)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" name="liters" required placeholder="0.00" class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none">
                                    <span class="absolute right-4 top-3 text-gray-400 font-bold text-sm">L</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Total Cost (PHP)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-400 font-bold text-sm">₱</span>
                                    <input type="number" step="0.01" name="cost" required placeholder="0.00" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none">
                                </div>
                            </div>

                            <button type="submit" name="log_fuel" class="w-full bg-primary-green hover:bg-green-800 text-white font-bold py-3.5 mt-2 rounded-xl shadow-lg shadow-green-100 transition active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Save Record
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-gray-800">My Fuel History</h3>
                            <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-xs font-bold"><?php echo count($myLogs); ?> Records</span>
                        </div>
                        
                        <div class="p-0">
                            <?php if (empty($myLogs)): ?>
                                <div class="p-12 text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                        <i class="fas fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No fuel logs found.</p>
                                    <p class="text-sm text-gray-400 mt-1">Your refuel records will appear here.</p>
                                </div>
                            <?php else: ?>
                                <div class="divide-y divide-gray-100">
                                    <?php foreach ($myLogs as $log): ?>
                                    <div class="p-5 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center hover:bg-gray-50 transition">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                                                <i class="fas fa-gas-pump text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-lg">₱<?php echo number_format($log['cost'], 2); ?></h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                                    <span class="bg-gray-100 px-2 py-0.5 rounded font-mono font-bold text-gray-600"><?php echo htmlspecialchars($log['plate'] ?? 'N/A'); ?></span>
                                                    <span>•</span>
                                                    <span><?php echo number_format($log['liters'], 2); ?> Liters</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right w-full sm:w-auto">
                                            <p class="text-sm font-semibold text-gray-700"><?php echo date('M d, Y', strtotime($log['date'])); ?></p>
                                            <p class="text-xs text-gray-400"><?php echo date('h:i A', strtotime($log['date'])); ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>