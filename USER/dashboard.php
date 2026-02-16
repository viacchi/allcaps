<?php
// START THE SESSION FIRST to fix the "Undefined array key" warning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/functions.php';

// Fetch Standard Data
$kpi = getUserKPI();
$currentTrip = getCurrentActiveTrip();

// SAFETY CHECK: Get driver ID from the correct nested session array!
$driver_id = isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 1; 

// Fetch the permanently assigned vehicle
$assignedVehicle = getAssignedVehicle($driver_id); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Driver Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="bg-brand-background-main text-brand-text-primary font-sans pb-20 md:pb-0 min-h-screen flex flex-col">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 mt-2">
                
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-brand-border flex flex-col md:flex-row items-center justify-between gap-4">
                        <?php if (!empty($assignedVehicle)): ?>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-brand-background-main rounded-xl flex items-center justify-center">
                                    <i class="fas fa-truck text-3xl text-brand-primary"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Assigned Vehicle</p>
                                    <h2 class="text-2xl font-black text-gray-800"><?php echo htmlspecialchars($assignedVehicle['plate'] ?? 'N/A'); ?></h2>
                                    <p class="text-sm text-gray-600">
                                        <?php echo htmlspecialchars(($assignedVehicle['model'] ?? '')); ?> 
                                        <span class="mx-2">•</span> 
                                        <span class="capitalize"><?php echo htmlspecialchars($assignedVehicle['type'] ?? 'Truck'); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> Status: Active / Ready
                                </span>
                            </div>
                        <?php else: ?>
                             <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-truck text-3xl text-gray-400"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-600">No Vehicle Assigned</h2>
                                    <p class="text-sm text-gray-500">Please contact dispatch to get a vehicle assignment.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <?php if ($currentTrip): ?>
                    <div class="flex justify-between items-end mb-3">
                        <h3 class="font-bold text-gray-700 text-lg">Current Trip</h3>
                        <span class="text-xs text-brand-primary font-bold animate-pulse flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand-primary block"></span> Live Tracking
                        </span>
                    </div>
                    
                    <div class="bg-gradient-to-br from-brand-primary to-brand-primary-hover rounded-2xl p-6 text-white shadow-lg relative overflow-hidden h-[90%] flex flex-col justify-between">
                        <div class="absolute -right-4 -bottom-4 opacity-10 pointer-events-none">
                            <i class="fas fa-map-marked-alt text-9xl"></i>
                        </div>
                        
                        <div class="relative z-10 flex flex-col h-full justify-between">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-emerald-100 text-xs uppercase tracking-widest font-bold mb-1">Ref: #<?php echo htmlspecialchars($currentTrip['tracking_id'] ?? 'N/A'); ?></p>
                                    <h2 class="text-2xl lg:text-3xl font-bold mt-1 leading-tight drop-shadow-md">
                                        <?php echo htmlspecialchars($currentTrip['start_location'] ?? 'Origin'); ?> 
                                        <i class="fas fa-arrow-right text-sm mx-2 opacity-75"></i> 
                                        <?php echo htmlspecialchars($currentTrip['destination'] ?? 'Destination'); ?>
                                    </h2>
                                </div>
                                <span class="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border border-white/20 shadow-sm">
                                    <?php echo htmlspecialchars($currentTrip['status'] ?? 'Active'); ?>
                                </span>
                            </div>

                            <div class="mt-8 flex gap-4">
                                <div class="flex-1 bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/10">
                                    <p class="text-[10px] text-emerald-100 uppercase tracking-wider font-semibold">Dispatch Time</p>
                                    <p class="text-lg font-bold mt-1">
                                        <?php echo isset($currentTrip['dispatch_date']) ? date('h:i A', strtotime($currentTrip['dispatch_date'])) : '--:--'; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-white/20">
                                <a href="live-tracker.php" class="w-full bg-white text-brand-primary font-bold py-3.5 rounded-xl hover:bg-brand-background-main transition shadow-lg text-center block active:scale-[0.99]">
                                    <i class="fas fa-location-arrow mr-2"></i> Open GPS Tracker
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-brand-border text-center h-full flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-brand-background-main rounded-full flex items-center justify-center mb-4 text-brand-primary/50">
                            <i class="fas fa-route text-4xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">No Active Trips</h3>
                        <p class="text-sm text-brand-text-secondary mt-1">Wait for dispatch or check your schedule.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="lg:col-span-1">
                    <div class="flex justify-between items-end mb-3">
                        <h3 class="font-bold text-gray-700 text-lg">AI Risk Assessment</h3>
                        <span id="ai-status-badge" class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-1 rounded font-bold tracking-wider border border-indigo-100 animate-pulse">CONNECTING...</span>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-border h-[90%] flex flex-col justify-between relative overflow-hidden">
                        <div>
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Delay Probability</p>
                                    <h2 id="ai-delay-prob" class="text-4xl font-extrabold text-gray-300">
                                        <i class="fas fa-circle-notch fa-spin text-3xl"></i>
                                    </h2>
                                </div>
                                <div id="ai-icon-bg" class="w-14 h-14 bg-gray-100 text-gray-300 rounded-2xl flex items-center justify-center text-2xl shadow-inner transition-colors duration-500">
                                    <i class="fas fa-brain"></i>
                                </div>
                            </div>
                            
                            <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden mb-6">
                                <div id="ai-progress-bar" class="h-full bg-brand-primary transition-all duration-1000 ease-out" style="width: 0%"></div>
                            </div>

                            <div id="ai-action-bg" class="bg-gray-50 p-4 rounded-xl border border-gray-100 transition-colors duration-500">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">AI Recommendation</p>
                                <div id="ai-action-text" class="flex items-center gap-2 font-bold text-gray-400 text-sm">
                                    Analyzing Live Data...
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-[10px] text-gray-400 mt-6 text-center tracking-widest uppercase font-semibold">Scikit-Learn Random Forest</p>
                    </div>
                </div>

            </div>

            <h3 class="font-bold text-gray-700 text-lg mb-4">Performance Overview</h3>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl mb-3">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <span class="text-3xl font-black text-gray-800"><?php echo $kpi['upcoming_trips']; ?></span>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Pending</span>
                </div>
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl mb-3">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="text-3xl font-black text-gray-800"><?php echo $kpi['completed_trips']; ?></span>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Completed</span>
                </div>
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center text-xl mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span class="text-3xl font-black text-gray-800"><?php echo $kpi['safety_score']; ?>%</span>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Safety</span>
                </div>
                
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-brand-border flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-xl mb-3">
                        <i class="fas fa-gas-pump"></i>
                    </div>
                    <span class="text-3xl font-black text-gray-800"><?php echo $kpi['fuel_used']; ?>L</span>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Fuel Used</span>
                </div>
                
            </div>

        </main>
    </div>

    <script src="../app.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAIPrediction();
        });

        function fetchAIPrediction() {
            // Get exact time for accurate prediction
            const currentHour = new Date().getHours();
            const isRaining = 0; // Set to 1 if you integrate a weather API later
            const aiUrl = `https://capstone-ai-service.onrender.com/predict_risk?hour=${currentHour}&rain=${isRaining}`;

            // Fetch data silently in the background
            fetch(aiUrl)
                .then(response => response.json())
                .then(data => {
                    const prob = data.delay_probability;
                    const risk = data.risk_level;
                    const action = data.suggested_action;

                    // Update Top Badge
                    const badge = document.getElementById('ai-status-badge');
                    badge.innerText = 'LIVE PREDICTION';
                    badge.classList.remove('animate-pulse');

                    // Set Default Theme (Safe/Low Risk)
                    let textColor = 'text-brand-primary';
                    let bgColor = 'bg-brand-primary';
                    let lightBgColor = 'bg-brand-background-main';
                    let actionIcon = '<i class="fas fa-check-circle text-lg"></i>';

                    // Change Theme based on Risk Level
                    if (risk === 'High') {
                        textColor = 'text-red-600';
                        bgColor = 'bg-red-500';
                        lightBgColor = 'bg-red-50';
                        actionIcon = '<i class="fas fa-exclamation-triangle text-lg"></i>';
                    } else if (risk === 'Medium') {
                        textColor = 'text-yellow-600';
                        bgColor = 'bg-yellow-500';
                        lightBgColor = 'bg-yellow-50';
                        actionIcon = '<i class="fas fa-exclamation-circle text-lg"></i>';
                    }

                    // Push Updates to Screen HTML
                    document.getElementById('ai-delay-prob').className = `text-4xl font-extrabold ${textColor}`;
                    document.getElementById('ai-delay-prob').innerHTML = `${prob}%`;

                    document.getElementById('ai-icon-bg').className = `w-14 h-14 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg transition-colors duration-500 ${bgColor}`;

                    document.getElementById('ai-progress-bar').className = `h-full transition-all duration-1000 ease-out ${bgColor}`;
                    document.getElementById('ai-progress-bar').style.width = `${prob}%`;

                    document.getElementById('ai-action-bg').className = `p-4 rounded-xl border border-gray-100 transition-colors duration-500 ${lightBgColor}`;
                    
                    document.getElementById('ai-action-text').className = `flex items-center gap-2 font-bold text-sm ${textColor}`;
                    document.getElementById('ai-action-text').innerHTML = `${actionIcon} ${action}`;
                })
                .catch(error => {
                    // Fallback if Render is completely offline
                    document.getElementById('ai-status-badge').innerText = 'OFFLINE';
                    document.getElementById('ai-status-badge').classList.remove('animate-pulse');
                    document.getElementById('ai-delay-prob').innerHTML = '<i class="fas fa-times-circle text-red-400"></i>';
                    document.getElementById('ai-action-text').innerHTML = 'System Offline';
                });
        }
    </script>
</body>
</html>