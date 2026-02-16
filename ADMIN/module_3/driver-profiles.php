<?php
include '../includes/functions.php';
$drivers = getDriverProfiles();
$vehicles = getAllVehicles(); 
// FIXED: Changed to getDispatches() to match the active tracking system
$allTrips = getDispatches(); 

// KPI calculations safely
$totalDrivers = count($drivers);
$avgRating = $totalDrivers > 0 ? array_sum(array_column($drivers, 'rating')) / $totalDrivers : 0;
$avgSafety = $totalDrivers > 0 ? array_sum(array_column($drivers, 'safety_score')) / $totalDrivers : 0;
$activeDrivers = count(array_filter($drivers, fn($d) => $d['status'] === 'Active'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Profiles - Logistics Admin</title>
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
    <?php include '../includes/sidebar.php'; ?>

    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <?php include '../includes/header.php'; ?>

        <main class="p-6">
            <div class="mb-6">
                <p class="text-gray-600">Track training, certifications, and performance ratings</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Drivers</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo number_format($avgRating, 1); ?>
                                <i class="fas fa-star text-yellow-500 text-lg"></i>
                            </div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-users"></i> In fleet
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-id-card text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Active Drivers</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $activeDrivers; ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> On duty
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Rating</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo number_format($avgRating, 1); ?>
                                <i class="fas fa-star text-yellow-500 text-lg"></i>
                            </div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-chart-line"></i> Fleet average
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Safety Score</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo round($avgSafety); ?>%
                            </div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-shield-alt"></i> Performance
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-address-card text-primary-green"></i>
                            Driver Profiles
                        </h2>
                        <div class="flex gap-3">
                            <input type="text" id="searchInput" placeholder="Search drivers..." onkeyup="filterDrivers()" class="px-4 py-2 border border-gray-300 rounded-md text-sm">
                            <select id="filterStatus" onchange="filterDrivers()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="driverGrid">
                    <?php foreach ($drivers as $driver): 
                        $statusColors = [
                            'Active' => 'bg-green-100 text-green-800',
                            'On Leave' => 'bg-yellow-100 text-yellow-800',
                            'Inactive' => 'bg-red-100 text-red-800'
                        ];
                        $ratingStars = str_repeat('⭐', floor($driver['rating']));
                        
                        // FIXED: Replaced '/' with '../../' so it routes to the correct folder
                        $pic = !empty($driver['profile_picture']) ? '../../' . $driver['profile_picture'] : null;
                    ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-all cursor-pointer" data-driver='<?php echo htmlspecialchars(json_encode($driver), ENT_QUOTES, 'UTF-8'); ?>'
onclick="viewDriverProfile(JSON.parse(this.dataset.driver))">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-green to-dark-green rounded-full overflow-hidden flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                <?php if($pic): ?>
                                    <img src="<?php echo htmlspecialchars($pic); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo strtoupper(substr($driver['name'], 0, 2)); ?>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg"><?php echo $driver['name']; ?></h3>
                                <p class="text-xs text-gray-500">
                                    <?php echo $driver['assigned_plate'] ? '<i class="fas fa-truck text-primary-green mr-1"></i> ' . $driver['assigned_plate'] : '<i class="fas fa-exclamation-circle text-yellow-500 mr-1"></i> No Vehicle'; ?>
                                </p>
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold mt-1 <?php echo $statusColors[$driver['status']] ?? 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $driver['status']; ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Rating</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-lg"><?php echo $ratingStars; ?></span>
                                    <span class="text-sm font-semibold text-gray-900"><?php echo number_format($driver['rating'], 1); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Total Trips</div>
                                <div class="text-lg font-bold text-gray-900"><?php echo $driver['total_trips']; ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Distance</div>
                                <div class="text-lg font-bold text-gray-900"><?php echo number_format($driver['total_distance']); ?> km</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Safety Score</div>
                                <div class="text-lg font-bold text-green-600"><?php echo $driver['safety_score']; ?>%</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Incidents</div>
                                <div class="text-lg font-bold <?php echo $driver['incidents'] > 0 ? 'text-red-600' : 'text-green-600'; ?>">
                                    <?php echo $driver['incidents']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button 
                                class="flex-1 px-3 py-1.5 bg-primary-green text-white rounded-md text-xs font-semibold hover:bg-dark-green transition-all"
                                onclick="event.stopPropagation(); viewDriverProfile(JSON.parse(this.closest('[data-driver]').dataset.driver))">
                                <i class="fas fa-eye"></i> View Profile
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="profileModal">
        <div class="bg-white rounded-lg w-11/12 max-w-5xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white text-2xl font-bold overflow-hidden">
                        <span id="modalInitials" class="hidden">JS</span>
                        <img id="modalViewPic" src="" class="w-full h-full object-cover hidden">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold" id="modalName">John Smith</h3>
                        <p class="text-sm text-white text-opacity-80" id="modalLicense">License: N01-12-123456</p>
                    </div>
                </div>
                <button onclick="closeProfileModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="border-b border-gray-200">
                <nav class="flex">
                    <button class="px-6 py-3 text-sm font-semibold border-b-2 border-primary-green text-primary-green" onclick="switchTab('personal')" id="tabPersonal">
                        <i class="fas fa-user mr-2"></i>Personal Info
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="switchTab('trips')" id="tabTrips">
                        <i class="fas fa-route mr-2"></i>Trips
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="switchTab('incidents')" id="tabIncidents">
                        <i class="fas fa-exclamation-circle mr-2"></i>Incidents
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="switchTab('ratings')" id="tabRatings">
                        <i class="fas fa-star mr-2"></i>Ratings
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <div id="contentPersonal">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-address-card text-primary-green"></i>
                                Contact Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-semibold text-gray-900" id="modalEmail">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Phone:</span>
                                    <span class="font-semibold text-gray-900" id="modalPhone">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Address:</span>
                                    <span class="font-semibold text-gray-900 text-right" id="modalAddress">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Emergency Contact:</span>
                                    <span class="font-semibold text-gray-900" id="modalEmergency">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-id-badge text-primary-green"></i>
                                License & Assignment
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Assigned Vehicle:</span>
                                    <span class="font-bold text-primary-green" id="modalAssignedVehicle">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">License Number:</span>
                                    <span class="font-semibold text-gray-900" id="modalLicenseNum">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" id="modalStatus">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="contentTrips" class="hidden">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900">Trip History</h4>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tracking Ref</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Destination</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>

                <div id="contentIncidents" class="hidden">
                     <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-700">No Incidents Recorded</p>
                    </div>
                </div>

                <div id="contentRatings" class="hidden">
                     <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-star text-5xl text-yellow-400 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-700">Driver Ratings loading...</p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeProfileModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="openEditModal()">
                        <i class="fas fa-edit"></i> Edit Profile & Vehicle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden fixed inset-0 z-[60] bg-black bg-opacity-70 items-center justify-center" id="editModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl overflow-hidden shadow-2xl">
            <div class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-xl"><i class="fas fa-user-edit mr-2"></i>Edit Driver Details</h3>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="p-6">
                <div id="editStatusMessage" class="hidden mb-4 p-3 rounded text-sm font-bold"></div>

                <form id="editDriverForm" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="editUserId">
                    
                    <div class="flex items-center gap-6 mb-6 pb-6 border-b">
                        <div class="relative group">
                            <img id="editProfilePreview" src="https://via.placeholder.com/100" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 shadow-sm">
                            <input type="file" name="profile_picture" id="editProfilePic" accept="image/*" class="hidden">
                            <label for="editProfilePic" class="absolute inset-0 bg-black/50 text-white rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                                <i class="fas fa-camera text-xl mb-1"></i>
                                <span class="text-xs font-bold">Change</span>
                            </label>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-lg">Profile Picture</h4>
                            <p class="text-xs text-gray-500">JPG or PNG. Max 5MB.<br>Updates will sync to Driver's App.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Full Name</label>
                            <input type="text" name="name" id="editName" class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">License Number</label>
                            <input type="text" name="license" id="editLicense" class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Emergency Contact</label>
                            <input type="text" name="emergency_contact" id="editEmergency" class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Account Status</label>
                            <select name="status" id="editStatus" class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500">
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="col-span-2 mt-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Assigned Vehicle</label>
                            <select name="vehicle_id" id="editVehicle" class="w-full px-3 py-2 border rounded focus:ring-blue-500 focus:border-blue-500 bg-emerald-50 font-bold text-emerald-900 border-emerald-200">
                                <option value="">-- No Vehicle Assigned --</option>
                                <?php foreach ($vehicles as $v): ?>
                                    <option value="<?php echo $v['id']; ?>">
                                        <?php echo htmlspecialchars($v['plate'] . ' (' . $v['model'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="text-[10px] text-gray-500 mt-1">This will permanently assign this truck to the driver's dashboard.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300">Cancel</button>
                        <button type="submit" id="btnSaveDriver" class="px-6 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const allTrips = <?php echo json_encode($allTrips); ?>;
        let currentDriver = null;

        function filterDrivers() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;

            const cards = document.querySelectorAll('#driverGrid > div');
            cards.forEach(card => {
                const driverName = card.querySelector('h3').textContent.toLowerCase();
                const status = card.querySelector('span.inline-block').textContent.trim();

                const matchesSearch = driverName.includes(searchInput);
                const matchesStatus = !statusFilter || status === statusFilter;

                card.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        function viewDriverProfile(driver) {
            currentDriver = driver;
            
            const initials = driver.name.split(' ').map(n => n[0]).join('');
            
            // FIXED IMAGE PATH FOR VIEW MODAL
            if(driver.profile_picture) {
                document.getElementById('modalInitials').classList.add('hidden');
                document.getElementById('modalViewPic').src = '../../' + driver.profile_picture;
                document.getElementById('modalViewPic').classList.remove('hidden');
            } else {
                document.getElementById('modalInitials').textContent = initials;
                document.getElementById('modalInitials').classList.remove('hidden');
                document.getElementById('modalViewPic').classList.add('hidden');
            }
            
            document.getElementById('modalName').textContent = driver.name;
            document.getElementById('modalLicense').textContent = 'License: ' + driver.license;

            // Personal Info Tab
            document.getElementById('modalEmail').textContent = driver.email || 'N/A';
            document.getElementById('modalPhone').textContent = driver.phone || 'N/A';
            document.getElementById('modalAddress').textContent = driver.address || 'N/A';
            document.getElementById('modalEmergency').textContent = driver.emergency_contact || 'N/A';
            document.getElementById('modalLicenseNum').textContent = driver.license;
            
            // Show assigned vehicle
            document.getElementById('modalAssignedVehicle').textContent = driver.assigned_plate ? driver.assigned_plate + ' (' + driver.assigned_model + ')' : 'No Vehicle Assigned';
            
            const statusColors = {
                'Active': 'bg-green-100 text-green-800',
                'On Leave': 'bg-yellow-100 text-yellow-800',
                'Inactive': 'bg-red-100 text-red-800'
            };
            const statusEl = document.getElementById('modalStatus');
            statusEl.textContent = driver.status;
            statusEl.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ' + (statusColors[driver.status] || 'bg-gray-100 text-gray-800');

            // REAL TRIPS LOGIC
            const driverTrips = allTrips.filter(t => t.driver === driver.name);
            const tripsTableBody = document.querySelector('#contentTrips tbody');
            tripsTableBody.innerHTML = ''; 
            
            if(driverTrips.length === 0) {
                tripsTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">No active dispatches or trips recorded.</td></tr>';
            } else {
                // Update table headers
                document.querySelector('#contentTrips thead tr').innerHTML = `
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tracking Ref</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Destination</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                `;

                driverTrips.forEach(t => {
                    let sColor = 'bg-gray-100 text-gray-800';
                    if(t.status === 'Completed' || t.status === 'On-Time') sColor = 'bg-green-100 text-green-800';
                    if(t.status === 'Pending' || t.status === 'Assigned') sColor = 'bg-yellow-100 text-yellow-800';
                    if(t.status === 'Delayed' || t.status === 'Cancelled') sColor = 'bg-red-100 text-red-800';

                    const tripDate = t.dispatch_date ? new Date(t.dispatch_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'}) : 'N/A';
                    const ref = t.tracking_id ? '#' + t.tracking_id : 'N/A';
                    const destination = t.destination || t.route || 'N/A';

                    tripsTableBody.innerHTML += `
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">${tripDate}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 font-medium">${ref}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 truncate max-w-[200px]" title="${destination}">${destination}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-block px-2 py-1 ${sColor} rounded-full text-xs font-semibold">${t.status}</span>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('profileModal').classList.remove('hidden');
            document.getElementById('profileModal').classList.add('flex');
            switchTab('personal');
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
            document.getElementById('profileModal').classList.remove('flex');
        }

        function openEditModal() {
            if(!currentDriver) return;
            
            closeProfileModal();
            
            document.getElementById('editUserId').value = currentDriver.user_id || currentDriver.id;
            document.getElementById('editName').value = currentDriver.name;
            document.getElementById('editLicense').value = currentDriver.license;
            document.getElementById('editEmergency').value = currentDriver.emergency_contact || '';
            document.getElementById('editStatus').value = currentDriver.status;
            
            document.getElementById('editVehicle').value = currentDriver.assigned_vehicle_id || '';
            
            // FIXED IMAGE PATH FOR EDIT MODAL
            if(currentDriver.profile_picture) {
                document.getElementById('editProfilePreview').src = '../../' + currentDriver.profile_picture;
            } else {
                document.getElementById('editProfilePreview').src = 'https://ui-avatars.com/api/?name=' + currentDriver.name + '&background=2563EB&color=fff';
            }

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
            document.getElementById('editDriverForm').reset();
            document.getElementById('editStatusMessage').classList.add('hidden');
        }

        document.getElementById('editProfilePic').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('editProfilePreview').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        document.getElementById('editDriverForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveDriver');
            const msg = document.getElementById('editStatusMessage');
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            btn.disabled = true;
            msg.classList.add('hidden');

            const formData = new FormData(this);

            fetch('process_edit_driver.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                msg.classList.remove('hidden', 'bg-red-100', 'text-red-700');
                if (data.success) {
                    msg.classList.add('bg-green-100', 'text-green-700');
                    msg.innerHTML = '<i class="fas fa-check-circle"></i> Successfully updated! Reloading...';
                    setTimeout(() => window.location.reload(), 1500); 
                } else {
                    msg.classList.add('bg-red-100', 'text-red-700');
                    msg.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.message;
                    btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                msg.classList.remove('hidden');
                msg.classList.add('bg-red-100', 'text-red-700');
                msg.innerHTML = '<i class="fas fa-wifi"></i> Connection error. Try again.';
                btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                btn.disabled = false;
            });
        });

        function switchTab(tabName) {
            document.getElementById('contentPersonal').classList.add('hidden');
            document.getElementById('contentTrips').classList.add('hidden');
            document.getElementById('contentIncidents').classList.add('hidden');
            document.getElementById('contentRatings').classList.add('hidden');

            document.getElementById('tabPersonal').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            document.getElementById('tabTrips').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            document.getElementById('tabIncidents').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            document.getElementById('tabRatings').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';

            document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');
            document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).className = 'px-6 py-3 text-sm font-semibold border-b-2 border-primary-green text-primary-green';
        }

        document.getElementById('profileModal').addEventListener('click', function(e) {
            if (e.target === this) closeProfileModal();
        });
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
    </script>
</body>
</html>