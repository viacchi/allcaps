<?php
include '../includes/functions.php';
$trips = getTripPerformanceReports();
$stats = getTripStatistics();
$drivers = getAvailableDrivers();
$vehicles = getVehicles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Performance Reports - Logistics Admin</title>
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
                <p class="text-gray-600">Review trip efficiency, compare planned vs. actual performance, and evaluate driver productivity</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Trips</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $stats['total_trips']; ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-route"></i> This period
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">On-Time Trips</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $stats['on_time_trips']; ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> <?php echo round(($stats['on_time_trips'] / $stats['total_trips']) * 100); ?>% success rate
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Distance</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo number_format($stats['total_distance']); ?><span class="text-lg"> km</span></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-road"></i> Covered distance
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marked-alt text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Fuel Efficiency</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">
                                <?php echo number_format($stats['total_distance'] / $stats['total_fuel'], 1); ?>
                                <span class="text-lg">km/L</span>
                            </div>
                            <div class="text-xs font-medium text-purple-600">
                                <i class="fas fa-gas-pump"></i> Fleet average
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tachometer-alt text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Trip Status Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-primary-green"></i>
                        Trip Status Distribution
                    </h3>
                    <div class="h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Fuel Efficiency Trends -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-primary-green"></i>
                        Fuel Efficiency Trends
                    </h3>
                    <div class="h-64">
                        <canvas id="fuelChart"></canvas>
                    </div>
                </div>

                <!-- Average Trip Duration -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-primary-green"></i>
                        Avg Duration by Driver
                    </h3>
                    <div class="h-64">
                        <canvas id="durationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Trip Performance Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-table text-primary-green"></i>
                            Trip Performance Records
                        </h2>
                        <div class="flex gap-3">
                            <button class="px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center gap-2" onclick="exportPDF()">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                            <button class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition-all duration-300 inline-flex items-center gap-2" onclick="exportExcel()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Date Range</label>
                            <input type="date" id="filterDateStart" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">To</label>
                            <input type="date" id="filterDateEnd" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Driver</label>
                            <select id="filterDriver" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                <option value="">All Drivers</option>
                                <?php foreach ($drivers as $driver): ?>
                                <option value="<?php echo $driver['name']; ?>"><?php echo $driver['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Vehicle</label>
                            <select id="filterVehicle" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                <option value="">All Vehicles</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?php echo $vehicle['plate']; ?>"><?php echo $vehicle['plate']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                            <select id="filterStatus" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                <option value="">All Status</option>
                                <option value="On-Time">On-Time</option>
                                <option value="Delayed">Delayed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <input type="text" id="searchInput" placeholder="Search by trip ID, route..." onkeyup="filterTable()" class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="tripTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Trip ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Distance</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Duration</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Fuel Used</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($trips as $trip): 
                                $statusColors = [
                                    'On-Time' => 'bg-green-100 text-green-800',
                                    'Delayed' => 'bg-yellow-100 text-yellow-800',
                                    'Cancelled' => 'bg-red-100 text-red-800'
                                ];
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $trip['trip_id']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($trip['date'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="font-semibold text-gray-900"><?php echo $trip['vehicle']; ?></div>
                                    <div class="text-xs text-gray-500"><?php echo $trip['vehicle_model']; ?></div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-primary-green rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            <?php echo strtoupper(substr($trip['driver'], 0, 2)); ?>
                                        </div>
                                        <span class="text-gray-900"><?php echo $trip['driver']; ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="font-semibold text-gray-900"><?php echo $trip['actual_distance']; ?> km</div>
                                    <?php if ($trip['actual_distance'] != $trip['planned_distance']): ?>
                                    <div class="text-xs text-gray-500">Planned: <?php echo $trip['planned_distance']; ?> km</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $trip['actual_duration']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="font-semibold text-gray-900"><?php echo $trip['fuel_used']; ?> L</div>
                                    <div class="text-xs text-gray-500">₱<?php echo number_format($trip['fuel_cost']); ?></div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $statusColors[$trip['status']]; ?>">
                                        <?php echo $trip['status']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <button class="px-3 py-1.5 bg-primary-green text-white rounded-md text-xs font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-1.5" onclick='viewReport(<?php echo json_encode($trip); ?>)'>
                                        <i class="fas fa-eye"></i> View Report
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Trip Report Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" id="reportModal">
        <div class="bg-white rounded-lg w-full max-w-6xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <div>
                    <h3 class="text-2xl font-bold" id="modalTripId">Trip Report</h3>
                    <p class="text-sm text-white text-opacity-80" id="modalRoute">Route Information</p>
                </div>
                <button onclick="closeReportModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Trip Status Banner -->
                <div class="mb-6 p-4 rounded-lg" id="statusBanner">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-3xl" id="statusIcon"></i>
                            <div>
                                <div class="text-lg font-bold" id="statusText">On-Time Delivery</div>
                                <div class="text-sm opacity-80" id="statusSubtext">Completed within scheduled time</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold" id="onTimePercentage">95%</div>
                            <div class="text-xs">On-Time Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Trip Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Basic Trip Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary-green"></i>
                            Trip Details
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Trip ID:</span>
                                <span class="font-semibold text-gray-900" id="detailTripId">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-semibold text-gray-900" id="detailDate">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Start Location:</span>
                                <span class="font-semibold text-gray-900" id="detailStartLocation">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">End Location:</span>
                                <span class="font-semibold text-gray-900" id="detailEndLocation">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Departure Time:</span>
                                <span class="font-semibold text-gray-900" id="detailDepartureTime">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Arrival Time:</span>
                                <span class="font-semibold text-gray-900" id="detailArrivalTime">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Driver & Vehicle Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fas fa-user-tag text-primary-green"></i>
                            Driver & Vehicle
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-primary-green rounded-full flex items-center justify-center text-white text-lg font-bold" id="detailDriverInitials">
                                    DS
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900" id="detailDriver">-</div>
                                    <div class="text-xs text-gray-500">Driver</div>
                                </div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Vehicle:</span>
                                <span class="font-semibold text-gray-900" id="detailVehicle">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Vehicle Model:</span>
                                <span class="text-gray-900" id="detailVehicleModel">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-tachometer-alt text-primary-green"></i>
                        Performance Metrics
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600" id="metricDistance">-</div>
                            <div class="text-xs text-gray-600 mt-1">Distance (km)</div>
                            <div class="text-xs text-gray-500 mt-1" id="metricDistanceComparison">vs planned</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-green-600" id="metricDuration">-</div>
                            <div class="text-xs text-gray-600 mt-1">Duration</div>
                            <div class="text-xs text-gray-500 mt-1" id="metricDurationComparison">vs planned</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600" id="metricIdleTime">-</div>
                            <div class="text-xs text-gray-600 mt-1">Idle Time (min)</div>
                            <div class="text-xs text-gray-500 mt-1">Total stoppage</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-purple-600" id="metricDeviation">-</div>
                            <div class="text-xs text-gray-600 mt-1">Route Deviation (km)</div>
                            <div class="text-xs text-gray-500 mt-1">From planned route</div>
                        </div>
                    </div>
                </div>

                <!-- Fuel Consumption & Expenses -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-gas-pump text-primary-green"></i>
                        Fuel Consumption & Expenses
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Fuel Used</div>
                                <div class="text-2xl font-bold text-gray-900" id="fuelUsed">-</div>
                                <div class="text-xs text-gray-500">Liters</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Fuel Cost</div>
                                <div class="text-2xl font-bold text-gray-900" id="fuelCost">-</div>
                                <div class="text-xs text-gray-500">Total expense</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-600 mb-1">Fuel Efficiency</div>
                                <div class="text-2xl font-bold text-gray-900" id="fuelEfficiency">-</div>
                                <div class="text-xs text-gray-500">km/L</div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="#" class="text-sm text-primary-green hover:text-dark-green font-semibold inline-flex items-center gap-2">
                                <i class="fas fa-link"></i> View Full Fuel & Expense Records
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Performance Analysis -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-primary-green"></i>
                        Performance Analysis
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- On-Time Performance -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">On-Time %</span>
                                <span class="text-lg font-bold text-gray-900" id="analysisOnTime">-</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full" id="analysisOnTimeBar" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2" id="analysisOnTimeText">-</p>
                        </div>

                        <!-- Efficiency Score -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Efficiency Score</span>
                                <span class="text-lg font-bold text-gray-900" id="analysisEfficiency">-</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-500 h-3 rounded-full" id="analysisEfficiencyBar" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Based on fuel, time, and route optimization</p>
                        </div>

                        <!-- Overall Rating -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Overall Rating</span>
                                <span class="text-lg font-bold text-gray-900" id="analysisRating">-</span>
                            </div>
                            <div class="flex items-center gap-1 text-2xl" id="analysisStars">
                                ⭐⭐⭐⭐⭐
                            </div>
                            <p class="text-xs text-gray-600 mt-2">Excellent performance</p>
                        </div>
                    </div>
                </div>

                <!-- Notes & Remarks -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-sticky-note text-primary-green"></i>
                        Notes & Remarks
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="mb-4">
                            <div class="text-xs font-semibold text-gray-600 mb-2">Driver Notes:</div>
                            <p class="text-sm text-gray-900" id="notesDriver">-</p>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-600 mb-2">Admin Remarks:</div>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" rows="3" placeholder="Add admin remarks..." id="notesAdmin"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeReportModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-md text-sm font-semibold hover:bg-blue-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="saveRemarks()">
                        <i class="fas fa-save"></i> Save Remarks
                    </button>
                    <button class="flex-1 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="exportTripPDF()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="printReport()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let currentTrip = null;

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['On-Time', 'Delayed', 'Cancelled'],
                datasets: [{
                    data: [<?php echo $stats['on_time_trips']; ?>, <?php echo $stats['delayed_trips']; ?>, <?php echo $stats['cancelled_trips']; ?>],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Fuel Efficiency Chart
        const fuelCtx = document.getElementById('fuelChart').getContext('2d');
        new Chart(fuelCtx, {
            type: 'line',
            data: {
                labels: ['Trip 1', 'Trip 2', 'Trip 3', 'Trip 4', 'Trip 5', 'Trip 6', 'Trip 7'],
                datasets: [{
                    label: 'km/L',
                    data: [3.6, 1.9, 8.8, 1.7, 2.8, 0, 2.9],
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Average Duration Chart
        const durationCtx = document.getElementById('durationChart').getContext('2d');
        new Chart(durationCtx, {
            type: 'bar',
            data: {
                labels: ['John S.', 'Jane D.', 'Mike J.', 'David B.', 'Sarah W.', 'Emma W.'],
                datasets: [{
                    label: 'Hours',
                    data: [2.2, 1.8, 1.5, 2.0, 1.6, 1.4],
                    backgroundColor: '#2D7A5C'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                }
            }
        });

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const driverFilter = document.getElementById('filterDriver').value;
            const vehicleFilter = document.getElementById('filterVehicle').value;
            const statusFilter = document.getElementById('filterStatus').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const tripId = row.cells[0].textContent.toLowerCase();
                const vehicle = row.cells[2].textContent;
                const driver = row.cells[3].textContent;
                const status = row.cells[7].textContent.trim();

                const matchesSearch = tripId.includes(searchInput);
                const matchesDriver = !driverFilter || driver.includes(driverFilter);
                const matchesVehicle = !vehicleFilter || vehicle.includes(vehicleFilter);
                const matchesStatus = !statusFilter || status === statusFilter;

                row.style.display = matchesSearch && matchesDriver && matchesVehicle && matchesStatus ? '' : 'none';
            });
        }

        function viewReport(trip) {
            currentTrip = trip;

            // Set modal title
            document.getElementById('modalTripId').textContent = `Trip Report - ${trip.trip_id}`;
            document.getElementById('modalRoute').textContent = trip.route;

            // Status Banner
            const statusBanner = document.getElementById('statusBanner');
            const statusIcon = document.getElementById('statusIcon');
            const statusText = document.getElementById('statusText');
            const statusSubtext = document.getElementById('statusSubtext');
            
            if (trip.status === 'On-Time') {
                statusBanner.className = 'mb-6 p-4 rounded-lg bg-green-50 text-green-800';
                statusIcon.className = 'fas fa-check-circle text-3xl text-green-600';
                statusText.textContent = 'On-Time Delivery';
                statusSubtext.textContent = 'Completed within scheduled time';
            } else if (trip.status === 'Delayed') {
                statusBanner.className = 'mb-6 p-4 rounded-lg bg-yellow-50 text-yellow-800';
                statusIcon.className = 'fas fa-exclamation-triangle text-3xl text-yellow-600';
                statusText.textContent = 'Delayed Delivery';
                statusSubtext.textContent = 'Arrived later than scheduled';
            } else {
                statusBanner.className = 'mb-6 p-4 rounded-lg bg-red-50 text-red-800';
                statusIcon.className = 'fas fa-times-circle text-3xl text-red-600';
                statusText.textContent = 'Cancelled Trip';
                statusSubtext.textContent = 'Trip was cancelled';
            }

            document.getElementById('onTimePercentage').textContent = trip.on_time_percentage + '%';

            // Trip Details
            document.getElementById('detailTripId').textContent = trip.trip_id;
            document.getElementById('detailDate').textContent = new Date(trip.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            document.getElementById('detailStartLocation').textContent = trip.start_location;
            document.getElementById('detailEndLocation').textContent = trip.end_location;
            document.getElementById('detailDepartureTime').textContent = trip.departure_time;
            document.getElementById('detailArrivalTime').textContent = trip.arrival_time;

            // Driver & Vehicle
            const initials = trip.driver.split(' ').map(n => n[0]).join('');
            document.getElementById('detailDriverInitials').textContent = initials;
            document.getElementById('detailDriver').textContent = trip.driver;
            document.getElementById('detailVehicle').textContent = trip.vehicle;
            document.getElementById('detailVehicleModel').textContent = trip.vehicle_model;

            // Performance Metrics
            document.getElementById('metricDistance').textContent = trip.actual_distance + ' km';
            document.getElementById('metricDistanceComparison').textContent = `Planned: ${trip.planned_distance} km`;
            document.getElementById('metricDuration').textContent = trip.actual_duration;
            document.getElementById('metricDurationComparison').textContent = `Planned: ${trip.planned_duration}`;
            document.getElementById('metricIdleTime').textContent = trip.idle_time + ' min';
            document.getElementById('metricDeviation').textContent = trip.route_deviation + ' km';

            // Fuel Consumption
            document.getElementById('fuelUsed').textContent = trip.fuel_used + ' L';
            document.getElementById('fuelCost').textContent = '₱' + trip.fuel_cost.toLocaleString();
            const efficiency = trip.actual_distance > 0 ? (trip.actual_distance / trip.fuel_used).toFixed(1) : 0;
            document.getElementById('fuelEfficiency').textContent = efficiency + ' km/L';

            // Performance Analysis
            document.getElementById('analysisOnTime').textContent = trip.on_time_percentage + '%';
            const onTimeBar = document.getElementById('analysisOnTimeBar');
            onTimeBar.style.width = trip.on_time_percentage + '%';
            if (trip.on_time_percentage >= 90) {
                onTimeBar.className = 'bg-green-500 h-3 rounded-full';
                document.getElementById('analysisOnTimeText').textContent = 'Excellent punctuality';
            } else if (trip.on_time_percentage >= 70) {
                onTimeBar.className = 'bg-yellow-500 h-3 rounded-full';
                document.getElementById('analysisOnTimeText').textContent = 'Good, minor delays';
            } else {
                onTimeBar.className = 'bg-red-500 h-3 rounded-full';
                document.getElementById('analysisOnTimeText').textContent = 'Needs improvement';
            }

            // Efficiency Score (calculated based on multiple factors)
            const efficiencyScore = Math.min(100, Math.round((trip.on_time_percentage + (efficiency * 10)) / 2));
            document.getElementById('analysisEfficiency').textContent = efficiencyScore + '%';
            document.getElementById('analysisEfficiencyBar').style.width = efficiencyScore + '%';

            // Overall Rating
            const rating = Math.min(5, Math.round((trip.on_time_percentage / 20)));
            document.getElementById('analysisRating').textContent = rating + '.0';
            document.getElementById('analysisStars').textContent = '⭐'.repeat(rating);

            // Notes
            document.getElementById('notesDriver').textContent = trip.notes;

            // Show modal
            document.getElementById('reportModal').classList.remove('hidden');
            document.getElementById('reportModal').classList.add('flex');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.getElementById('reportModal').classList.remove('flex');
        }

        function saveRemarks() {
            const remarks = document.getElementById('notesAdmin').value;
            if (!remarks.trim()) {
                alert('Please enter admin remarks before saving.');
                return;
            }
            alert('Admin remarks saved successfully!');
        }

        function exportPDF() {
            alert('Exporting all trips to PDF...');
        }

        function exportExcel() {
            alert('Exporting all trips to Excel...');
        }

        function exportTripPDF() {
            alert(`Exporting ${currentTrip.trip_id} report to PDF...`);
        }

        function printReport() {
            window.print();
        }

        // Close modal when clicking outside
        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) closeReportModal();
        });

        // Set date filters to current month
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            document.getElementById('filterDateStart').value = firstDay.toISOString().split('T')[0];
            document.getElementById('filterDateEnd').value = lastDay.toISOString().split('T')[0];
        });
    </script>
</body>
</html>