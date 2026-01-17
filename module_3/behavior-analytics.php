<?php
include '../includes/functions.php';
$behaviorData = getDriverBehaviorData();
$incidents = getBehaviorIncidents();
$trends = getMonthlyBehaviorTrends();

// Calculate totals
$totalSpeeding = array_sum(array_column($behaviorData, 'speeding'));
$totalHarshBraking = array_sum(array_column($behaviorData, 'harsh_braking'));
$avgIdleTime = round(array_sum(array_column($behaviorData, 'idle_time')) / count($behaviorData));
$avgScore = round(array_sum(array_column($behaviorData, 'score')) / count($behaviorData));

// Sort for leaderboard
usort($behaviorData, fn($a, $b) => $b['score'] - $a['score']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behavior Analytics - Logistics Admin</title>
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
                <p class="text-gray-600">Analytics on driver behavior and safety performance</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Speeding Incidents</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $totalSpeeding; ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-exclamation-triangle"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tachometer-alt text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Harsh Braking</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $totalHarshBraking; ?></div>
                            <div class="text-xs font-medium text-yellow-600">
                                <i class="fas fa-hand-paper"></i> This month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hand-rock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Idle Time</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $avgIdleTime; ?><span class="text-lg">min</span></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-clock"></i> Per driver
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Safety Score</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $avgScore; ?><span class="text-lg">%</span></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-shield-alt"></i> Fleet average
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Behavior Trends Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-primary-green"></i>
                        Behavior Trends (6 Months)
                    </h3>
                    <div class="h-80">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>

                <!-- Incident Distribution Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-primary-green"></i>
                        Incident Distribution
                    </h3>
                    <div class="h-80 flex items-center justify-center">
                        <canvas id="incidentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Leaderboard and Recent Incidents -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Driver Leaderboard -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="fas fa-trophy"></i>
                            Top Performing Drivers
                        </h3>
                        <p class="text-sm text-yellow-100 mt-1">Based on safety score this month</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php 
                            $medals = ['🥇', '🥈', '🥉'];
                            foreach (array_slice($behaviorData, 0, 5) as $index => $driver): 
                                $scoreColor = $driver['score'] >= 95 ? 'text-green-600' : ($driver['score'] >= 85 ? 'text-yellow-600' : 'text-red-600');
                            ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="text-2xl">
                                        <?php echo $index < 3 ? $medals[$index] : '<span class="text-gray-400 font-bold">#' . ($index + 1) . '</span>'; ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900"><?php echo $driver['driver']; ?></div>
                                        <div class="text-xs text-gray-500"><?php echo $driver['trips']; ?> trips completed</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold <?php echo $scoreColor; ?>"><?php echo $driver['score']; ?></div>
                                    <div class="text-xs text-gray-500">Safety Score</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="w-full mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200 transition-all">
                            <i class="fas fa-users"></i> View All Drivers
                        </button>
                    </div>
                </div>

                <!-- Recent Incidents -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                            Recent Behavior Incidents
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($incidents as $incident): 
                                $severityColors = [
                                    'High' => 'bg-red-100 text-red-800 border-red-300',
                                    'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'Low' => 'bg-blue-100 text-blue-800 border-blue-300'
                                ];
                                $severityColor = $severityColors[$incident['severity']];
                            ?>
                            <div class="border-l-4 <?php echo str_replace('bg-', 'border-', explode(' ', $severityColor)[0]); ?> p-4 bg-gray-50 rounded-r-lg">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <div class="font-semibold text-gray-900"><?php echo $incident['driver']; ?></div>
                                        <div class="text-sm text-gray-600"><?php echo $incident['type']; ?></div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border <?php echo $severityColor; ?>">
                                        <?php echo $incident['severity']; ?>
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $incident['location']; ?>
                                    <?php if ($incident['speed']): ?>
                                        • <i class="fas fa-tachometer-alt"></i> <?php echo $incident['speed']; ?> km/h
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-clock"></i> <?php echo date('M d, Y', strtotime($incident['date'])); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="w-full mt-4 px-4 py-2 bg-red-500 text-white rounded-md text-sm font-semibold hover:bg-red-600 transition-all" onclick="viewAllIncidents()">
                            <i class="fas fa-list"></i> View All Incidents
                        </button>
                    </div>
                </div>
            </div>

            <!-- Driver Behavior Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user-shield text-primary-green"></i>
                            Driver Behavior Overview
                        </h2>
                        <div class="flex gap-3">
                            <input type="text" id="searchInput" placeholder="Search driver..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm">
                            <select id="filterScore" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                <option value="">All Scores</option>
                                <option value="excellent">Excellent (95-100)</option>
                                <option value="good">Good (85-94)</option>
                                <option value="needs-improvement">Needs Improvement (&lt;85)</option>
                            </select>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center gap-2" onclick="exportReport()">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Safety Score</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Speeding</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Harsh Braking</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Idle Time</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Total Trips</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($behaviorData as $driver): 
                                $scoreColor = $driver['score'] >= 95 ? 'bg-green-100 text-green-800' : ($driver['score'] >= 85 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-primary-green rounded-full flex items-center justify-center text-white font-bold">
                                            <?php echo strtoupper(substr($driver['driver'], 0, 2)); ?>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900"><?php echo $driver['driver']; ?></div>
                                            <div class="text-xs text-gray-500">Active Driver</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full <?php echo $driver['score'] >= 95 ? 'bg-green-500' : ($driver['score'] >= 85 ? 'bg-yellow-500' : 'bg-red-500'); ?>" style="width: <?php echo $driver['score']; ?>%"></div>
                                        </div>
                                        <span class="font-semibold text-gray-900"><?php echo $driver['score']; ?>%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 text-red-700 rounded text-xs font-semibold">
                                        <i class="fas fa-tachometer-alt"></i> <?php echo $driver['speeding']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-50 text-yellow-700 rounded text-xs font-semibold">
                                        <i class="fas fa-hand-paper"></i> <?php echo $driver['harsh_braking']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <?php echo $driver['idle_time']; ?> min
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <?php echo $driver['trips']; ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <button class="px-3 py-1.5 bg-blue-500 text-white rounded-md text-xs font-semibold hover:bg-blue-600 transition-all inline-flex items-center gap-1.5" onclick="viewDriverDetails('<?php echo $driver['driver']; ?>')">
                                        <i class="fas fa-chart-bar"></i> View Details
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

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Trends Chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($trends, 'month')); ?>,
                datasets: [
                    {
                        label: 'Speeding Incidents',
                        data: <?php echo json_encode(array_column($trends, 'speeding')); ?>,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Harsh Braking',
                        data: <?php echo json_encode(array_column($trends, 'harsh_braking')); ?>,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Incident Distribution Chart
        const incidentCtx = document.getElementById('incidentChart').getContext('2d');
        new Chart(incidentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Speeding', 'Harsh Braking', 'Excessive Idle'],
                datasets: [{
                    data: [<?php echo $totalSpeeding; ?>, <?php echo $totalHarshBraking; ?>, <?php echo count($behaviorData); ?>],
                    backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6']
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

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const scoreFilter = document.getElementById('filterScore').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const driver = row.cells[0].textContent.toLowerCase();
                const score = parseInt(row.cells[1].textContent);

                const matchesSearch = driver.includes(searchInput);
                let matchesScore = true;
                
                if (scoreFilter === 'excellent') matchesScore = score >= 95;
                else if (scoreFilter === 'good') matchesScore = score >= 85 && score < 95;
                else if (scoreFilter === 'needs-improvement') matchesScore = score < 85;

                row.style.display = matchesSearch && matchesScore ? '' : 'none';
            });
        }

        function viewDriverDetails(driver) {
            alert(`Viewing detailed behavior analytics for ${driver}...`);
            // Navigate to driver profile page
        }

        function viewAllIncidents() {
            alert('Opening Incident Case Management...');
            // Navigate to incident management page
        }

        function exportReport() {
            alert('Exporting behavior analytics report...');
        }
    </script>
</body>
</html>