<?php
include '../includes/functions.php';
$expenses = getTransportExpenses();
$summary = getTransportCostSummary();
$fuelTrends = getFuelConsumptionTrends();
$vehicleCostsData = getVehicleCostComparison();
$insights = getOptimizationInsights();
$drivers = getAvailableDrivers();
$vehicles = getVehicles();

// 1️⃣ Expense Breakdown Chart
$expenseLabels = [];
$expenseData = [];
$expenseColors = ['#3B82F6', '#10B981', '#EF4444', '#F59E0B', '#8B5CF6'];

if (!empty($summary['category_breakdown'])) {
    foreach ($summary['category_breakdown'] as $category => $amount) {
        $expenseLabels[] = $category;
        $expenseData[] = $amount;
    }
}

// 2️⃣ Fuel Consumption Trends Chart
$months = [];
$consumption = [];
$costs = [];

if (!empty($fuelTrends)) {
    foreach ($fuelTrends as $trend) {
        $months[] = $trend['month'] ?? 'N/A';
        $consumption[] = $trend['consumption'] ?? 0;
        $costs[] = $trend['cost'] ?? 0;
    }
}

// 3️⃣ Vehicle Cost Comparison Chart
$vehicleLabels = [];
$vehicleCostData = [];
$fleetAverage = 0;

if (!empty($vehicleCostsData['vehicle_costs'])) {
    foreach ($vehicleCostsData['vehicle_costs'] as $vehicle => $cost) {
        $vehicleLabels[] = $vehicle;
        $vehicleCostData[] = $cost;
    }
    $fleetAverage = $vehicleCostsData['fleet_average'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Cost & Optimization</title>
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
                <p class="text-gray-600">Analyze transport costs and get AI-powered optimization insights</p>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Expense Breakdown -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-primary-green"></i> Expense Breakdown
                    </h3>
                    <div class="h-64">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>

                <!-- Fuel Consumption Trends -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-primary-green"></i> Fuel Consumption Trends
                    </h3>
                    <div class="h-64">
                        <canvas id="fuelTrendChart"></canvas>
                    </div>
                </div>

                <!-- Vehicle Cost Comparison -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-primary-green"></i> Cost per Vehicle
                    </h3>
                    <div class="h-64">
                        <canvas id="vehicleCostChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Expense Breakdown Chart
        new Chart(document.getElementById('expenseChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($expenseLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($expenseData); ?>,
                    backgroundColor: <?php echo json_encode($expenseColors); ?>
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ₱' + context.parsed.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Fuel Consumption Trends Chart
        new Chart(document.getElementById('fuelTrendChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [
                    {
                        label: 'Consumption (L)',
                        data: <?php echo json_encode($consumption); ?>,
                        borderColor: '#2D7A5C',
                        backgroundColor: 'rgba(45, 122, 92, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Cost (₱)',
                        data: <?php echo json_encode($costs); ?>,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Liters' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Cost (₱)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });

        // Vehicle Cost Comparison Chart
        new Chart(document.getElementById('vehicleCostChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($vehicleLabels); ?>,
                datasets: [
                    {
                        label: 'Vehicle Cost',
                        data: <?php echo json_encode($vehicleCostData); ?>,
                        backgroundColor: '#2D7A5C'
                    },
                    {
                        label: 'Fleet Average',
                        data: Array(<?php echo count($vehicleLabels); ?>).fill(<?php echo $fleetAverage; ?>),
                        type: 'line',
                        borderColor: '#EF4444',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '₱' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
