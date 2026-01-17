<?php
include 'includes/functions.php';
$kpi = getKPIData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Logistics Admin</title>
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
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
                <p class="text-gray-600 mt-2">Welcome to your logistics management system</p>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Total Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $kpi['total_vehicles']; ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-up"></i> 4.5% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-car text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Active Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $kpi['active_vehicles']; ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-up"></i> 2.1% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Maintenance Due</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $kpi['maintenance_due']; ?></div>
                            <div class="text-xs font-medium text-red-600">
                                <i class="fas fa-arrow-up"></i> 0.8% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Inactive Vehicles</div>
                            <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $kpi['inactive_vehicles']; ?></div>
                            <div class="text-xs font-medium text-green-600">
                                <i class="fas fa-arrow-down"></i> 1.2% vs last month
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-ban text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Fleet Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Fleet Distribution</h3>
                    <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <div class="text-6xl mb-2">📊</div>
                            <p class="text-gray-600 font-medium">Chart Placeholder</p>
                            <p class="text-sm text-gray-500 mt-1">Integration ready</p>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Trend -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Trend</h3>
                    <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <div class="text-6xl mb-2">📈</div>
                            <p class="text-gray-600 font-medium">Chart Placeholder</p>
                            <p class="text-sm text-gray-500 mt-1">Integration ready</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                    <a href="#" class="text-primary-green hover:text-dark-green text-sm font-semibold">View All</a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-plus text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">New vehicle registered</p>
                            <p class="text-sm text-gray-600 mt-1">Vehicle ABC-1234 added to fleet</p>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap">2 hours ago</span>
                    </div>
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-wrench text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Maintenance completed</p>
                            <p class="text-sm text-gray-600 mt-1">Oil change for XYZ-5678 completed</p>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap">5 hours ago</span>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation text-yellow-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Maintenance approval pending</p>
                            <p class="text-sm text-gray-600 mt-1">Awaiting approval for DEF-9012 repair</p>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap">1 day ago</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Set active page title
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('pageTitle').textContent = 'Dashboard';
        });
    </script>
</body>
</html>