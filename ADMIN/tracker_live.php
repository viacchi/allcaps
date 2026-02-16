<?php
session_start();
include 'includes/db.php';

// -----------------------------------------
// 1. HANDLE FORM SUBMISSION (WITH SYNC FIX)
// -----------------------------------------
$message = "";
$msg_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dispatch_btn'])) {
    $driver_id = intval($_POST['driver_id']);
    $destination = trim($_POST['destination']);
    $start_location = "Headquarters (Main Hub)"; 

    if ($driver_id && $destination) {
        $veh_query = $conn->query("SELECT id FROM vehicles WHERE status = 'Active' LIMIT 1");
        
        if ($veh_query->num_rows > 0) {
            $vehicle_id = $veh_query->fetch_assoc()['id'];
            $tracking_id = "TRK-" . strtoupper(substr(md5(time()), 0, 6));

            $stmt = $conn->prepare("INSERT INTO dispatches (tracking_id, vehicle_id, driver_id, start_location, destination, status, dispatch_date) VALUES (?, ?, ?, ?, ?, 'Assigned', NOW())");
            $stmt->bind_param("siiss", $tracking_id, $vehicle_id, $driver_id, $start_location, $destination);

            if ($stmt->execute()) {
                header("Location: tracker_live.php?success=1&id=".$tracking_id);
                exit();
            } else {
                $message = "Database Error: " . $conn->error;
                $msg_type = "error";
            }
        } else {
            $conn->query("INSERT INTO vehicles (plate, model, type, status) VALUES ('TEMP-001', 'Generic Truck', 'Truck', 'Active')");
            $vehicle_id = $conn->insert_id;
            
            $tracking_id = "TRK-" . strtoupper(substr(md5(time()), 0, 6));
            $stmt = $conn->prepare("INSERT INTO dispatches (tracking_id, vehicle_id, driver_id, start_location, destination, status, dispatch_date) VALUES (?, ?, ?, ?, ?, 'Assigned', NOW())");
            $stmt->bind_param("siiss", $tracking_id, $vehicle_id, $driver_id, $start_location, $destination);
            $stmt->execute();
            
            header("Location: tracker_live.php?success=1&id=".$tracking_id);
            exit();
        }
    } else {
        $message = "Please select a driver and destination.";
        $msg_type = "error";
    }
}

if(isset($_GET['success'])) {
    $message = "Dispatch Successful! ID: <strong>".$_GET['id']."</strong>";
    $msg_type = "success";
}

// -----------------------------------------
// 2. FETCH DATA FOR UI
// -----------------------------------------
$drivers = $conn->query("SELECT id, full_name, license FROM drivers WHERE status='Active'");

// AI RISK SERVICE CALL (Timeout optimized for 3s to allow for server wake-up)
$ai_url = "https://capstone-ai-service.onrender.com/predict_risk?rain=0"; 
$ctx = stream_context_create(['http' => ['timeout' => 3]]); 
$ai_res = @file_get_contents($ai_url, false, $ctx);
$ai_data = json_decode($ai_res, true);
$global_risk = $ai_data['risk_level'] ?? "Low";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Fleet Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'primary-green': '#2D7A5C', 'dark-green': '#1F5240' } } }
        }
    </script>
    <style>
        #search-results { max-height: 250px; overflow-y: auto; z-index: 1000; }
        /* Added specific map height constraints to prevent collapsing */
        .map-container { min-height: 50vh; }
        @media (min-width: 1024px) {
            .map-container { height: 100%; min-height: auto; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">

    <?php include 'includes/sidebar.php'; ?>

    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <?php include 'includes/header.php'; ?>

        <main class="p-4 md:p-6">
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">Fleet Command Center</h1>
                    <p class="text-gray-600 text-sm">Monitoring <?php echo $drivers->num_rows; ?> active units</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                        <i class="fas fa-microchip text-purple-600"></i>
                        <span class="text-xs font-bold text-gray-500 uppercase">AI Status: 
                            <span class="text-green-600">Online</span>
                        </span>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                        <i class="fas fa-brain <?php echo ($global_risk == 'High') ? 'text-red-500' : 'text-primary-green'; ?>"></i>
                        <span class="text-xs font-bold text-gray-500 uppercase">Delay Risk: 
                            <span class="<?php echo ($global_risk == 'High') ? 'text-red-600' : 'text-green-600'; ?>">
                                <?php echo $global_risk; ?>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-xl flex items-center gap-3 <?php echo $msg_type == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'; ?>">
                <i class="fas <?php echo $msg_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> text-xl"></i>
                <div class="text-sm font-semibold"><?php echo $message; ?></div>
            </div>
            <?php endif; ?>

            <div class="flex flex-col lg:flex-row gap-6 min-h-[75vh] lg:h-[75vh]">
                
                <div class="w-full map-container lg:flex-1 bg-white rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden shrink-0">
                    <div id="map" class="absolute inset-0 bg-gray-50 z-0"></div>
                </div>

                <div class="w-full lg:w-96 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col shrink-0 overflow-visible">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                        <h2 class="font-bold text-gray-800 flex items-center gap-2 text-lg">
                            <i class="fas fa-plus-circle text-primary-green"></i> Create Dispatch
                        </h2>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="" class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Assign Driver</label>
                                <select name="driver_id" required class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none appearance-none transition-all">
                                    <option value="" disabled selected>Select an available driver...</option>
                                    <?php 
                                    $drivers->data_seek(0); // Reset pointer
                                    while($d = $drivers->fetch_assoc()): ?>
                                        <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['full_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Delivery Destination</label>
                                <div class="relative">
                                    <input type="text" id="destination-input" name="destination" required placeholder="Search address..." autocomplete="off"
                                           class="w-full pl-10 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none transition-all">
                                    <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
                                    <i id="search-spinner" class="fas fa-spinner fa-spin absolute right-4 top-4 text-primary-green hidden"></i>
                                </div>
                                <div class="relative w-full">
                                    <ul id="search-results" class="hidden absolute top-1 left-0 right-0 bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden"></ul>
                                </div>
                            </div>

                            <button type="submit" name="dispatch_btn" class="w-full bg-primary-green hover:bg-dark-green text-white font-bold py-4 rounded-xl shadow-lg shadow-green-100 transition transform active:scale-[0.97] flex items-center justify-center gap-3">
                                <span>INITIALIZE DISPATCH</span>
                                <i class="fas fa-location-arrow"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. Map Initialization
        var map = L.map('map', { zoomControl: false }).setView([14.5995, 120.9842], 12);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        // Fix map rendering issue when layout changes
        setTimeout(function(){ map.invalidateSize(); }, 500);

        // 2. High-Speed Search Logic
        const searchInput = document.getElementById('destination-input');
        const resultsBox = document.getElementById('search-results');
        const spinner = document.getElementById('search-spinner');
        let debounceTimer;

        searchInput.addEventListener('input', function() {
            const query = this.value;
            clearTimeout(debounceTimer);
            if (query.length < 2) { resultsBox.classList.add('hidden'); return; }
            spinner.classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ph&limit=5`)
                .then(res => res.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    spinner.classList.add('hidden');
                    if (data.length > 0) {
                        resultsBox.classList.remove('hidden');
                        data.forEach(place => {
                            const li = document.createElement('li');
                            li.className = "px-4 py-3 hover:bg-green-50 cursor-pointer border-b border-gray-50 last:border-0 text-sm flex items-center gap-3";
                            li.innerHTML = `<i class="fas fa-map-marker-alt text-gray-300"></i> <span class="truncate">${place.display_name}</span>`;
                            li.onclick = () => {
                                searchInput.value = place.display_name;
                                resultsBox.classList.add('hidden');
                                map.flyTo([place.lat, place.lon], 15);
                                L.marker([place.lat, place.lon]).addTo(map).bindPopup(place.display_name).openPopup();
                            };
                            resultsBox.appendChild(li);
                        });
                    }
                });
            }, 200); 
        });

        document.addEventListener('click', e => { if (!searchInput.contains(e.target)) resultsBox.classList.add('hidden'); });
    </script>
</body>
</html>