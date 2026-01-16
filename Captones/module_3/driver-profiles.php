<?php
include '../includes/functions.php';
$drivers = getDriverProfiles();
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
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <!-- Header -->
        <?php include '../includes/header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Optional Subtitle -->
            <div class="mb-6">
                <p class="text-gray-600">Track training, certifications, and performance ratings</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Drivers</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($drivers); ?></div>
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
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($drivers, fn($d) => $d['status'] === 'Active')); ?></div>
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
                                <?php echo number_format(array_sum(array_column($drivers, 'rating')) / count($drivers), 1); ?>
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
                                <?php echo round(array_sum(array_column($drivers, 'safety_score')) / count($drivers)); ?>%
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

            <!-- Driver Profiles Grid -->
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
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center gap-2" onclick="addDriver()">
                                <i class="fas fa-user-plus"></i> Add Driver
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Driver Cards Grid -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="driverGrid">
                    <?php foreach ($drivers as $driver): 
                        $statusColors = [
                            'Active' => 'bg-green-100 text-green-800',
                            'On Leave' => 'bg-yellow-100 text-yellow-800',
                            'Inactive' => 'bg-red-100 text-red-800'
                        ];
                        $ratingStars = str_repeat('⭐', floor($driver['rating']));
                    ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-all cursor-pointer" onclick='viewDriverProfile(<?php echo json_encode($driver); ?>)'>
                        <!-- Driver Header -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-green to-dark-green rounded-full flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                <?php echo strtoupper(substr($driver['name'], 0, 2)); ?>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-lg"><?php echo $driver['name']; ?></h3>
                                <p class="text-xs text-gray-500"><?php echo $driver['license']; ?></p>
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold mt-1 <?php echo $statusColors[$driver['status']]; ?>">
                                    <?php echo $driver['status']; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Rating</span>
                                <div class="flex items-center gap-1">
                                    <span class="text-lg"><?php echo $ratingStars; ?></span>
                                    <span class="text-sm font-semibold text-gray-900"><?php echo $driver['rating']; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
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

                        <!-- Quick Actions -->
                        <div class="flex gap-2">
                            <button class="flex-1 px-3 py-1.5 bg-primary-green text-white rounded-md text-xs font-semibold hover:bg-dark-green transition-all" onclick="event.stopPropagation(); viewDriverProfile(<?php echo htmlspecialchars(json_encode($driver)); ?>)">
                                <i class="fas fa-eye"></i> View Profile
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Driver Profile Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="profileModal">
        <div class="bg-white rounded-lg w-11/12 max-w-5xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        <span id="modalInitials">JS</span>
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

            <!-- Tabs -->
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

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Personal Info Tab -->
                <div id="contentPersonal">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Information -->
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
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Blood Type:</span>
                                    <span class="font-semibold text-red-600" id="modalBloodType">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- License & Employment -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-id-badge text-primary-green"></i>
                                License & Employment
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">License Number:</span>
                                    <span class="font-semibold text-gray-900" id="modalLicenseNum">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">License Expiry:</span>
                                    <span class="font-semibold text-gray-900" id="modalExpiry">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Join Date:</span>
                                    <span class="font-semibold text-gray-900" id="modalJoinDate">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" id="modalStatus">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Metrics -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-chart-bar text-primary-green"></i>
                                Performance Metrics
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Safety Score</span>
                                        <span class="font-semibold text-gray-900" id="modalSafetyScore">-</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" id="modalSafetyBar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">On-Time Rate</span>
                                        <span class="font-semibold text-gray-900" id="modalOnTimeRate">-</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" id="modalOnTimeBar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <div class="text-xs text-gray-600 mb-1">Total Trips</div>
                                        <div class="text-2xl font-bold text-gray-900" id="modalTotalTrips">-</div>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-600 mb-1">Total Distance</div>
                                        <div class="text-2xl font-bold text-gray-900" id="modalTotalDistance">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Certifications & Training -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <i class="fas fa-certificate text-primary-green"></i>
                                Certifications & Training
                            </h4>
                            <div class="space-y-2" id="modalCertifications">
                                <!-- Certifications will be loaded here -->
                            </div>
                            <button class="mt-4 w-full px-3 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all">
                                <i class="fas fa-plus"></i> Add Certification
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Trips Tab -->
                <div id="contentTrips" class="hidden">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900">Trip History</h4>
                        <select class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option>Last 30 Days</option>
                            <option>Last 3 Months</option>
                            <option>Last 6 Months</option>
                            <option>All Time</option>
                        </select>
                    </div>

                    <!-- Trip Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600" id="modalTripsCompleted">-</div>
                            <div class="text-sm text-gray-600 mt-1">Completed Trips</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600" id="modalTripsOnTime">-</div>
                            <div class="text-sm text-gray-600 mt-1">On-Time Deliveries</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600">12.5K</div>
                            <div class="text-sm text-gray-600 mt-1">Total KM</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-purple-600">450</div>
                            <div class="text-sm text-gray-600 mt-1">Hours Driven</div>
                        </div>
                    </div>

                    <!-- Recent Trips Table -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Route</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Distance</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">Oct 28, 2024</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">ABC-1234</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">Manila - Quezon City</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">45 km</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Completed</span>
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">Oct 27, 2024</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">ABC-1234</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">Manila - Makati</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">32 km</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Completed</span>
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-200 hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">Oct 26, 2024</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">XYZ-5678</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">Manila - Taguig</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">28 km</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Completed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Incidents Tab -->
                <div id="contentIncidents" class="hidden">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-lg font-semibold text-gray-900">Incident History</h4>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold" id="modalIncidentCount">0 Incidents</span>
                    </div>

                    <div id="modalIncidentsList" class="space-y-4">
                        <!-- Incidents will be loaded here -->
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
                            <p class="text-lg font-semibold text-gray-700">No Incidents Recorded</p>
                            <p class="text-sm text-gray-500 mt-2">This driver has a clean record!</p>
                        </div>
                    </div>
                </div>

                <!-- Ratings Tab -->
                <div id="contentRatings" class="hidden">
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Overall Rating</h4>
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <div class="text-6xl font-bold text-gray-900 mb-2" id="modalRatingNum">-</div>
                            <div class="text-3xl mb-2" id="modalRatingStars">⭐⭐⭐⭐⭐</div>
                            <p class="text-sm text-gray-600">Based on customer and supervisor feedback</p>
                        </div>
                    </div>

                    <!-- Rating Breakdown -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Rating Breakdown</h4>
                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Professionalism</span>
                                    <span class="font-semibold text-gray-900">4.9 ⭐</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 98%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Punctuality</span>
                                    <span class="font-semibold text-gray-900">4.8 ⭐</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 96%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Vehicle Care</span>
                                    <span class="font-semibold text-gray-900">4.7 ⭐</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 94%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Customer Service</span>
                                    <span class="font-semibold text-gray-900">4.9 ⭐</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 98%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Reviews -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Recent Reviews</h4>
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            CM
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm text-gray-900">Customer</div>
                                            <div class="text-xs text-gray-500">Oct 25, 2024</div>
                                        </div>
                                    </div>
                                    <div class="text-yellow-500">⭐⭐⭐⭐⭐</div>
                                </div>
                                <p class="text-sm text-gray-700">"Very professional and courteous driver. Delivered on time and handled our items with care."</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            SP
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm text-gray-900">Supervisor</div>
                                            <div class="text-xs text-gray-500">Oct 20, 2024</div>
                                        </div>
                                    </div>
                                    <div class="text-yellow-500">⭐⭐⭐⭐⭐</div>
                                </div>
                                <p class="text-sm text-gray-700">"Excellent driver with great attention to safety protocols. Always maintains vehicle in good condition."</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeProfileModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="editDriver()">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="exportDriverReport()">
                        <i class="fas fa-download"></i> Export Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            
            // Set profile header
            const initials = driver.name.split(' ').map(n => n[0]).join('');
            document.getElementById('modalInitials').textContent = initials;
            document.getElementById('modalName').textContent = driver.name;
            document.getElementById('modalLicense').textContent = 'License: ' + driver.license;

            // Personal Info Tab
            document.getElementById('modalEmail').textContent = driver.email;
            document.getElementById('modalPhone').textContent = driver.phone;
            document.getElementById('modalAddress').textContent = driver.address;
            document.getElementById('modalEmergency').textContent = driver.emergency_contact;
            document.getElementById('modalBloodType').textContent = driver.blood_type;
            document.getElementById('modalLicenseNum').textContent = driver.license;
            document.getElementById('modalExpiry').textContent = new Date(driver.expiry).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('modalJoinDate').textContent = new Date(driver.join_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            const statusColors = {
                'Active': 'bg-green-100 text-green-800',
                'On Leave': 'bg-yellow-100 text-yellow-800',
                'Inactive': 'bg-red-100 text-red-800'
            };
            const statusEl = document.getElementById('modalStatus');
            statusEl.textContent = driver.status;
            statusEl.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold ' + statusColors[driver.status];

            // Performance Metrics
            document.getElementById('modalSafetyScore').textContent = driver.safety_score + '%';
            document.getElementById('modalSafetyBar').style.width = driver.safety_score + '%';
            document.getElementById('modalOnTimeRate').textContent = driver.on_time_rate + '%';
            document.getElementById('modalOnTimeBar').style.width = driver.on_time_rate + '%';
            document.getElementById('modalTotalTrips').textContent = driver.total_trips;
            document.getElementById('modalTotalDistance').textContent = driver.total_distance.toLocaleString() + ' km';

            // Certifications
            const certsHtml = driver.certifications.map(cert => `
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span class="text-gray-900">${cert}</span>
                </div>
            `).join('');
            document.getElementById('modalCertifications').innerHTML = certsHtml;

            // Trips Tab
            document.getElementById('modalTripsCompleted').textContent = driver.total_trips;
            document.getElementById('modalTripsOnTime').textContent = Math.round(driver.total_trips * (driver.on_time_rate / 100));

            // Incidents Tab
            document.getElementById('modalIncidentCount').textContent = driver.incidents + ' Incident' + (driver.incidents !== 1 ? 's' : '');
            
            if (driver.incidents > 0) {
                // Show incidents - in real app, fetch from getIncidentCases()
                document.getElementById('modalIncidentsList').innerHTML = `
                    <div class="border-l-4 border-red-500 p-4 bg-red-50 rounded-r-lg">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-semibold text-gray-900">Minor Collision</div>
                                <div class="text-sm text-gray-600">At EDSA-Quezon Ave intersection</div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">High</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-clock"></i> Oct 28, 2024
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('modalIncidentsList').innerHTML = `
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-700">No Incidents Recorded</p>
                        <p class="text-sm text-gray-500 mt-2">This driver has a clean record!</p>
                    </div>
                `;
            }

            // Ratings Tab
            document.getElementById('modalRatingNum').textContent = driver.rating;
            const stars = '⭐'.repeat(Math.floor(driver.rating));
            document.getElementById('modalRatingStars').textContent = stars;

            // Show modal
            document.getElementById('profileModal').classList.remove('hidden');
            document.getElementById('profileModal').classList.add('flex');

            // Reset to first tab
            switchTab('personal');
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
            document.getElementById('profileModal').classList.remove('flex');
        }

        function switchTab(tabName) {
            // Hide all tabs
            document.getElementById('contentPersonal').classList.add('hidden');
            document.getElementById('contentTrips').classList.add('hidden');
            document.getElementById('contentIncidents').classList.add('hidden');
            document.getElementById('contentRatings').classList.add('hidden');

            // Reset all tab buttons
            document.getElementById('tabPersonal').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            document.getElementById('tabTrips').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            document.getElementById('tabIncidents').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            document.getElementById('tabRatings').className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';

            // Show selected tab
            document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).classList.remove('hidden');
            document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1)).className = 'px-6 py-3 text-sm font-semibold border-b-2 border-primary-green text-primary-green';
        }

        function addDriver() {
            alert('Opening Add Driver form...');
        }

        function editDriver() {
            alert('Opening Edit Driver form for ' + currentDriver.name);
        }

        function exportDriverReport() {
            alert('Exporting driver report for ' + currentDriver.name);
        }

        // Close modal when clicking outside
        document.getElementById('profileModal').addEventListener('click', function(e) {
            if (e.target === this) closeProfileModal();
        });
    </script>
</body>
</html>