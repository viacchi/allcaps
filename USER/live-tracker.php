<?php
include 'includes/functions.php';

$activeTrip = getCurrentActiveTrip();
$driver_id = getCurrentDriverId();

// 1. Handle Trip Actions, SOS, & Proof Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Start Trip
    if (isset($_POST['start_trip']) && $activeTrip) {
        $tripID = $activeTrip['id'];
        $conn->query("UPDATE dispatches SET status = 'On Duty' WHERE id = $tripID");
        header("Location: live-tracker.php");
        exit();
    }
    
    // TRIGGER EMERGENCY SOS TO ADMIN
    if (isset($_POST['trigger_sos']) && $activeTrip) {
        // Grab a short snippet of the destination for the alert
        $dest = substr($activeTrip['destination'], 0, 30) . '...';
        
        $title = "🚨 SOS EMERGENCY";
        $message = "Driver triggered an SOS alert on route to " . $conn->real_escape_string($dest) . "! Immediate attention required.";
        $link = "tracker_live.php";
        $color = "red";
        $icon = "fa-triangle-exclamation";
        
        // Inject directly into the Admin's notification table
        $stmt = $conn->prepare("INSERT INTO notifications (title, message, link, color, icon, read_status, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
        $stmt->bind_param("sssss", $title, $message, $link, $color, $icon);
        $stmt->execute();
        
        echo "<script>alert('SOS TRANSMITTED! HQ has been alerted. Please pull over and stay safe.'); window.location.href='live-tracker.php';</script>";
        exit();
    }
    
    // Upload Proof Image
    if (isset($_POST['upload_proof']) && isset($_FILES['proof_image']) && $activeTrip) {
        $tripID = $activeTrip['id'];
        $target_dir = "../uploads/"; 
        
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $filename = time() . '_' . basename($_FILES["proof_image"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file)) {
            $conn->query("UPDATE dispatches SET proof_image = '$filename' WHERE id = $tripID");
            echo "<script>alert('Proof of Delivery uploaded successfully!'); window.location.href='live-tracker.php';</script>";
            exit();
        }
    }

    // Complete Trip
    if (isset($_POST['complete_trip']) && $activeTrip) {
        $tripID = $activeTrip['id'];
        $conn->query("UPDATE dispatches SET status = 'Completed', return_date = NOW() WHERE id = $tripID");
        header("Location: assigned-trips.php?status=completed");
        exit();
    }
}

// 2. Fetch AI Risk
$risk_level = "Normal";
if ($activeTrip) {
    $ai_url = "https://capstone-ai-service.onrender.com/predict_risk?rain=0"; 
    $ctx = stream_context_create(['http' => ['timeout' => 3]]); 
    $ai_res = @file_get_contents($ai_url, false, $ctx);
    if ($ai_res) {
        $ai_data = json_decode($ai_res, true);
        $risk_level = $ai_data['risk_level'] ?? "Normal";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Tracker - Driver</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        #map-container { position: relative; height: calc(100dvh - 64px); width: 100%; z-index: 0; }
        #map { position: absolute; inset: 0; z-index: 1; }
        .pulse-marker { background-color: #2D7A5C; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 10px rgba(47, 133, 90, 0.4); animation: pulse-ring 2s infinite; }
        @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(47, 133, 90, 0.7); } 70% { box-shadow: 0 0 0 20px rgba(47, 133, 90, 0); } 100% { box-shadow: 0 0 0 0 rgba(47, 133, 90, 0); } }
        .leaflet-routing-container { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans overflow-hidden">
    
    <div id="loading-screen" class="fixed inset-0 bg-white/90 backdrop-blur-sm z-[9999] hidden flex flex-col items-center justify-center">
        <i class="fas fa-truck-fast text-4xl text-primary-green animate-bounce mb-4"></i>
        <h2 class="text-xl font-bold text-gray-800">Processing...</h2>
        <p class="text-gray-500 text-sm">Please wait</p>
    </div>

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main id="map-container">
            <div id="map"></div>

            <div class="absolute top-4 right-4 z-[999] flex flex-col items-end gap-2">
                <div class="bg-white px-3 py-1.5 rounded-lg shadow-md flex items-center gap-2 border border-gray-100">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-gray-600 uppercase">GPS Active</span>
                </div>
                
                <?php if($risk_level == "High"): ?>
                    <div class="bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg shadow-md flex items-center gap-2">
                        <i class="fas fa-robot text-red-600"></i>
                        <div class="text-right">
                            <p class="text-[8px] font-bold uppercase text-red-400 leading-none">AI Prediction</p>
                            <p class="text-[10px] font-bold text-red-700 leading-none mt-0.5">High Traffic</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg shadow-md flex items-center gap-2">
                        <i class="fas fa-brain text-emerald-600"></i>
                        <div class="text-right">
                            <p class="text-[8px] font-bold uppercase text-emerald-400 leading-none">AI Prediction</p>
                            <p class="text-[10px] font-bold text-emerald-700 leading-none mt-0.5">Optimal Route</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <button onclick="recenterMap()" class="absolute right-4 bottom-64 md:bottom-56 z-[999] bg-white h-12 w-12 rounded-full shadow-lg flex items-center justify-center text-gray-600 transition hover:bg-gray-50 active:scale-95">
                <i class="fas fa-crosshairs text-xl"></i>
            </button>

            <div id="ui-overlay" class="fixed bottom-0 left-0 right-0 md:left-72 z-[1000] pointer-events-none">
                <?php if ($activeTrip): ?>
                <div class="bg-white rounded-t-[2rem] p-6 shadow-[0_-10px_40px_rgba(0,0,0,0.15)] pointer-events-auto">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Current Order</p>
                            <h1 class="text-xl font-extrabold text-gray-800 leading-tight truncate w-56 sm:w-64">
                                <?php echo htmlspecialchars($activeTrip['destination']); ?>
                            </h1>
                            <p class="text-sm text-gray-500 mt-1" id="etaText">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Calculating Route...
                            </p>
                        </div>
                        <div class="px-3 py-1 rounded-lg text-xs font-bold shrink-0 <?php echo ($activeTrip['status'] == 'On Duty') ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600'; ?>">
                            <?php echo $activeTrip['status']; ?>
                        </div>
                    </div>

                    <?php if ($activeTrip['status'] == 'Assigned'): ?>
                        <form method="POST" onsubmit="document.getElementById('loading-screen').classList.remove('hidden');">
                            <button type="submit" name="start_trip" class="w-full bg-green-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg active:scale-95 transition flex items-center justify-center gap-2">
                                <i class="fas fa-play"></i> START TRIP
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            <a href="fuel-logs.php" class="flex flex-col items-center justify-center p-3 bg-orange-50 text-orange-600 rounded-xl hover:bg-orange-100 transition active:scale-95 border border-orange-100 text-center">
                                <i class="fas fa-gas-pump text-lg mb-1"></i>
                                <span class="text-[10px] font-bold">Fuel</span>
                            </a>
                            
                            <form method="POST" class="m-0 p-0" onsubmit="return confirm('🚨 TRIGGER EMERGENCY SOS? This will alert HQ immediately!');">
                                <button type="submit" name="trigger_sos" class="w-full h-full flex flex-col items-center justify-center p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition active:scale-95 border border-red-100">
                                    <i class="fas fa-triangle-exclamation text-lg mb-1"></i>
                                    <span class="text-[10px] font-bold">SOS</span>
                                </button>
                            </form>
                            
                            <a href="driver-contact.php" class="flex flex-col items-center justify-center p-3 bg-purple-50 text-purple-600 rounded-xl hover:bg-purple-100 transition active:scale-95 border border-purple-100 text-center">
                                <i class="fas fa-file-alt text-lg mb-1"></i>
                                <span class="text-[10px] font-bold">Report</span>
                            </a>
                            
                            <button onclick="document.getElementById('proof-modal').classList.remove('hidden')" class="flex flex-col items-center justify-center p-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition active:scale-95 border border-blue-100">
                                <i class="fas fa-camera text-lg mb-1"></i>
                                <span class="text-[10px] font-bold">Proof</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($activeTrip['destination']); ?>" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-center flex items-center justify-center gap-2 transition">
                                <i class="fas fa-location-arrow"></i> Waze
                            </a>
                            <form method="POST" onsubmit="return confirm('Did you upload the Proof of Delivery? Confirm arrival?');">
                                <button type="submit" name="complete_trip" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold shadow-lg transition flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i> Complete
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                    <div class="bg-white p-8 rounded-t-[2rem] md:rounded-2xl md:mb-10 md:mx-10 shadow-[0_-10px_40px_rgba(0,0,0,0.15)] pointer-events-auto text-center">
                        <h2 class="font-bold text-gray-800 text-lg">No Active Route</h2>
                        <p class="text-sm text-gray-400 mt-1">Wait for dispatch or check history.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="proof-modal" class="fixed inset-0 bg-black/60 z-[9999] hidden flex flex-col items-center justify-end md:justify-center p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mb-4 md:mb-0 shadow-2xl transform transition-all">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Upload Proof</h3>
            <p class="text-xs text-gray-500 mb-6">Take a photo of the receipt or delivery location.</p>
            
            <form method="POST" enctype="multipart/form-data" onsubmit="document.getElementById('loading-screen').classList.remove('hidden');">
                <div class="mb-6 relative border-2 border-dashed border-gray-300 rounded-xl p-6 hover:bg-gray-50 transition text-center cursor-pointer">
                    <input type="file" name="proof_image" accept="image/*" capture="environment" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-2"></i>
                    <p class="text-sm font-semibold text-gray-700">Tap to open Camera/Gallery</p>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('proof-modal').classList.add('hidden')" class="flex-1 bg-gray-100 hover:bg-gray-200 py-3 rounded-xl font-bold text-gray-700 transition">Cancel</button>
                    <button type="submit" name="upload_proof" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition">Save Proof</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        var map = L.map('map', { zoomControl: false }).setView([14.5995, 120.9842], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        var driverMarker, routeControl;
        var destStr = "<?php echo $activeTrip ? addslashes($activeTrip['destination']) : ''; ?>";
        var aiRisk = "<?php echo $risk_level; ?>";
        
        var pulseIcon = L.divIcon({ className: 'pulse-marker', iconSize: [20, 20], iconAnchor: [10, 10] });
        
        var destIcon = L.divIcon({ 
            html: "<div class='text-red-600 text-4xl drop-shadow-md'><i class='fas fa-map-marker-alt'></i></div>", 
            className: 'bg-transparent', iconSize: [30, 42], iconAnchor: [15, 42] 
        });

        // 1. WATCH GPS
        map.locate({watch: true, setView: false, maxZoom: 16, enableHighAccuracy: true});

        map.on('locationfound', function(e) {
            if (!driverMarker) {
                driverMarker = L.marker(e.latlng, {icon: pulseIcon}).addTo(map);
                map.flyTo(e.latlng, 16); 
                if (destStr) calculateRoute(e.latlng);
            } else {
                driverMarker.setLatLng(e.latlng);
            }
        });

        // 2. ROUTE CALCULATION
        function calculateRoute(startLatLng) {
            document.getElementById('etaText').innerHTML = "<i class='fas fa-spinner fa-spin mr-1'></i> Contacting Map Server...";
            
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(destStr)}&limit=1`)
                .then(res => res.json())
                .then(data => {
                    if(data.length > 0) {
                        var targetLatLng = L.latLng(data[0].lat, data[0].lon);
                        L.marker(targetLatLng, {icon: destIcon}).addTo(map).bindPopup(destStr).openPopup();
                        var routeColor = (aiRisk === 'High') ? '#DC2626' : '#2D7A5C';
                        
                        if(routeControl) map.removeControl(routeControl);

                        routeControl = L.Routing.control({
                            waypoints: [startLatLng, targetLatLng],
                            lineOptions: { styles: [{color: routeColor, opacity: 0.9, weight: 6}] },
                            serviceUrl: 'https://routing.openstreetmap.de/routed-car/route/v1',
                            createMarker: function() { return null; },
                            show: false, fitSelectedRoutes: true
                        }).on('routesfound', function(e) {
                            var route = e.routes[0];
                            document.getElementById('etaText').innerHTML = Math.round(route.summary.totalTime / 60) + " min (" + (route.summary.totalDistance/1000).toFixed(1) + " km)";
                        }).on('routingerror', function(e) {
                            document.getElementById('etaText').innerHTML = "<span class='text-yellow-600 font-bold'>Routing server busy. Direct path shown.</span>";
                            L.polyline([startLatLng, targetLatLng], {color: routeColor, weight: 5, dashArray: '10, 10'}).addTo(map);
                            map.fitBounds([startLatLng, targetLatLng]);
                        }).addTo(map);
                    } else {
                        document.getElementById('etaText').innerHTML = "<span class='text-red-500 font-bold'>Address not recognized by map.</span>";
                    }
                });
        }

        function recenterMap() { if(driverMarker) map.flyTo(driverMarker.getLatLng(), 17); }
    </script>
</body>
</html>