<?php
include '../includes/functions.php';
$predictions = getPredictiveData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predictive Maintenance - Logistics Admin</title>
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
                <p class="text-gray-600">AI-powered maintenance forecasting to prevent breakdowns</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Active Predictions</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count($predictions); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-brain"></i> AI-powered
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-primary-green/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-robot text-primary-green text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Critical Alerts</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_filter($predictions, fn($p) => $p['days'] <= 7)); ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-exclamation-triangle"></i> Urgent action
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bell text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Confidence</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php 
                                $avgConfidence = array_sum(array_column($predictions, 'confidence')) / count($predictions);
                                echo round($avgConfidence);
                            ?>%</div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-check-circle"></i> High accuracy
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Vehicles Monitored</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo count(array_unique(array_column($predictions, 'vehicle'))); ?></div>
                            <div class="text-xs font-medium text-blue-600">
                                <i class="fas fa-car"></i> Under watch
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Health Score Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-area text-primary-green"></i>
                        Vehicle Health Score Over Time
                    </h3>
                    <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 font-medium">Health Score Chart</p>
                            <p class="text-sm text-gray-500 mt-2">Integration with Chart.js or similar library</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-tachometer-alt text-primary-green"></i>
                        Risk Distribution
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700">High Risk</span>
                                <span class="font-semibold text-red-600">2 vehicles</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-red-500 h-3 rounded-full" style="width: 20%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700">Medium Risk</span>
                                <span class="font-semibold text-yellow-600">5 vehicles</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-yellow-500 h-3 rounded-full" style="width: 50%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="font-medium text-gray-700">Low Risk</span>
                                <span class="font-semibold text-green-600">3 vehicles</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-lightbulb text-blue-600 text-lg mt-1"></i>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">AI Insight</p>
                                <p class="text-xs text-blue-800">2 vehicles require immediate attention based on usage patterns and maintenance history.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Predictions Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-brain text-primary-green"></i>
                            AI Maintenance Predictions
                        </h2>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex gap-3">
                                <input type="text" id="searchInput" placeholder="Search by vehicle..." onkeyup="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm w-full sm:w-auto">
                                <select id="filterPriority" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
                                    <option value="">All Priority</option>
                                    <option value="high">High (≤7 days)</option>
                                    <option value="medium">Medium (8-15 days)</option>
                                    <option value="low">Low (>15 days)</option>
                                </select>
                            </div>
                            <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2 whitespace-nowrap" onclick="refreshPredictions()">
                                <i class="fas fa-sync"></i> Refresh AI
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="predictionsTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Priority</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Predicted Issue</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">AI Confidence</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Forecast Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Days Until</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($predictions as $prediction): 
                                $priority = $prediction['days'] <= 7 ? 'high' : ($prediction['days'] <= 15 ? 'medium' : 'low');
                                $priorityColor = $priority === 'high' ? 'bg-red-100 text-red-800' : ($priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                $priorityIcon = $priority === 'high' ? 'fa-exclamation-circle' : ($priority === 'medium' ? 'fa-exclamation-triangle' : 'fa-info-circle');
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $priorityColor; ?>">
                                        <i class="fas <?php echo $priorityIcon; ?>"></i>
                                        <?php echo strtoupper($priority); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $prediction['vehicle']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-wrench text-gray-400"></i>
                                        <span class="text-gray-700"><?php echo $prediction['prediction']; ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full <?php echo $prediction['confidence'] >= 90 ? 'bg-green-500' : ($prediction['confidence'] >= 70 ? 'bg-yellow-500' : 'bg-red-500'); ?>" style="width: <?php echo $prediction['confidence']; ?>%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900"><?php echo $prediction['confidence']; ?>%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-700">
                                    <?php 
                                    $forecastDate = new DateTime();
                                    $forecastDate->modify("+{$prediction['days']} days");
                                    echo $forecastDate->format('M d, Y');
                                    ?>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="font-semibold <?php echo $prediction['days'] <= 7 ? 'text-red-600' : ($prediction['days'] <= 15 ? 'text-yellow-600' : 'text-gray-700'); ?>">
                                        <?php echo $prediction['days']; ?> days
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1.5 bg-primary-green text-white rounded-md text-xs font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-1.5" onclick="scheduleMaintenance('<?php echo $prediction['vehicle']; ?>', '<?php echo $prediction['prediction']; ?>')">
                                            <i class="fas fa-calendar-plus"></i> Schedule
                                        </button>
                                        <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick="viewDetails('<?php echo $prediction['vehicle']; ?>')">
                                            <i class="fas fa-info-circle"></i> Details
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Schedule Maintenance Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center" id="scheduleModal">
        <div class="bg-white rounded-lg w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Schedule Predictive Maintenance</h3>
                <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="scheduleForm" onsubmit="saveSchedule(event)" class="p-6">
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-robot text-blue-600 text-2xl"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-900 mb-1">AI Recommendation</p>
                            <p class="text-sm text-blue-800" id="aiRecommendation">Schedule this maintenance within the next 5 days to prevent potential breakdown.</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle</label>
                    <input type="text" id="scheduleVehicle" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100" readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Maintenance Type</label>
                    <input type="text" id="scheduleType" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100" readonly>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Scheduled Date *</label>
                        <input type="date" id="scheduleDate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Estimated Cost *</label>
                        <input type="number" id="estimatedCost" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priority *</label>
                    <select id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" required>
                        <option value="high">High - Immediate attention required</option>
                        <option value="medium">Medium - Schedule within this week</option>
                        <option value="low">Low - Can be scheduled later</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent" placeholder="AI-suggested maintenance based on predictive analysis..." rows="3"></textarea>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeScheduleModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-calendar-check"></i> Schedule Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const priorityFilter = document.getElementById('filterPriority').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const vehicle = row.cells[1].textContent.toLowerCase();
                const priorityText = row.cells[0].textContent.toLowerCase();

                const matchesSearch = vehicle.includes(searchInput);
                const matchesPriority = !priorityFilter || priorityText.includes(priorityFilter);

                row.style.display = matchesSearch && matchesPriority ? '' : 'none';
            });
        }

        function scheduleMaintenance(vehicle, type) {
            document.getElementById('scheduleVehicle').value = vehicle;
            document.getElementById('scheduleType').value = type;
            document.getElementById('scheduleModal').classList.remove('hidden');
            document.getElementById('scheduleModal').classList.add('flex');
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
            document.getElementById('scheduleModal').classList.remove('flex');
            document.getElementById('scheduleForm').reset();
        }

        function saveSchedule(event) {
            event.preventDefault();
            alert('Predictive maintenance scheduled successfully!');
            closeScheduleModal();
            // Reload page or update table here
        }

        function viewDetails(vehicle) {
            alert(`Viewing detailed AI analysis for ${vehicle}...`);
        }

        function refreshPredictions() {
            alert('Refreshing AI predictions... This may take a moment.');
            // Reload predictions via AJAX
        }

        // Close modal when clicking outside
        document.getElementById('scheduleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeScheduleModal();
            }
        });
    </script>
</body>
</html>