<?php
include '../includes/functions.php';
$expenses = getTransportExpenses();
$summary = getTransportCostSummary();
$fuelTrends = getFuelConsumptionTrends();
$vehicleCosts = getVehicleCostComparison();
$insights = getOptimizationInsights();
$drivers = getAvailableDrivers();
$vehicles = getVehicles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Cost & Optimization - Logistics Admin</title>
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
                <p class="text-gray-600">Analyze transport costs, spot inefficiencies, and get AI-powered optimization insights</p>
            </div>

            <!-- Summary Panel -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Cost -->
                <div class="bg-gradient-to-br from-primary-green to-dark-green text-white rounded-lg p-5 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-peso-sign text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-xs opacity-80">Total Cost</div>
                            <div class="text-xs opacity-60">Selected Period</div>
                        </div>
                    </div>
                    <div class="text-3xl font-bold mb-2">₱<?php echo number_format($summary['total_cost']); ?></div>
                    <div class="flex items-center gap-2 text-sm">
                        <?php if ($summary['monthly_change'] < 0): ?>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-500 bg-opacity-20 rounded-full">
                                <i class="fas fa-arrow-down text-xs"></i>
                                <?php echo abs($summary['monthly_change']); ?>%
                            </span>
                            <span class="text-xs opacity-80">vs last month</span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-500 bg-opacity-20 rounded-full">
                                <i class="fas fa-arrow-up text-xs"></i>
                                <?php echo $summary['monthly_change']; ?>%
                            </span>
                            <span class="text-xs opacity-80">vs last month</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Top Expense Categories -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-blue-500 col-span-1 md:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-blue-500"></i>
                        Top 3 Expense Categories
                    </h3>
                    <div class="space-y-3">
                        <?php 
                        $colors = ['bg-blue-500', 'bg-yellow-500', 'bg-purple-500'];
                        $index = 0;
                        foreach ($summary['top_categories'] as $category => $amount): 
                            $percentage = round(($amount / $summary['total_cost']) * 100);
                        ?>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700"><?php echo $category; ?></span>
                                <span class="font-bold text-gray-900">₱<?php echo number_format($amount); ?> (<?php echo $percentage; ?>%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="<?php echo $colors[$index]; ?> h-2 rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                        </div>
                        <?php 
                        $index++;
                        endforeach; 
                        ?>
                    </div>
                </div>

                <!-- Average Daily Cost -->
                <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-600 text-sm font-medium">Avg Daily Cost</div>
                            <div class="text-3xl font-bold text-gray-900 my-2">₱<?php echo number_format($summary['avg_daily_cost']); ?></div>
                            <div class="text-xs font-medium text-purple-600">
                                <i class="fas fa-calendar-day"></i> Per day
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calculator text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Optimization Insights -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-lg p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-lightbulb text-white text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">AI-Powered Optimization Insights</h3>
                        <p class="text-sm text-gray-700 mb-4">We've identified <strong><?php echo count($insights); ?> opportunities</strong> to reduce costs and improve efficiency. Potential savings: <strong>₱<?php echo number_format(array_sum(array_column($insights, 'potential_savings'))); ?></strong></p>
                        <button class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm font-semibold hover:bg-yellow-600 transition-all inline-flex items-center gap-2" onclick="viewAllInsights()">
                            <i class="fas fa-eye"></i> View All Insights
                        </button>
                    </div>
                </div>
            </div>

            <!-- Charts & Graphs -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Expense Breakdown -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-primary-green"></i>
                        Expense Breakdown
                    </h3>
                    <div class="h-64">
                        <canvas id="expenseChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <?php foreach ($summary['category_breakdown'] as $category => $amount): ?>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: <?php 
                                    $categoryColors = ['Fuel' => '#3B82F6', 'Maintenance' => '#10B981', 'Repairs' => '#EF4444', 'Licensing' => '#F59E0B', 'Misc' => '#8B5CF6'];
                                    echo $categoryColors[$category] ?? '#6B7280';
                                ?>"></div>
                                <span class="text-gray-700"><?php echo $category; ?>: ₱<?php echo number_format($amount); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Fuel Consumption Trends -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-line text-primary-green"></i>
                        Fuel Consumption Trends
                    </h3>
                    <div class="h-64">
                        <canvas id="fuelTrendChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">6-Month Average:</span>
                            <span class="font-semibold text-gray-900"><?php echo round(array_sum(array_column($fuelTrends, 'consumption')) / count($fuelTrends)); ?> L</span>
                        </div>
                        <div class="flex justify-between text-sm mt-2">
                            <span class="text-gray-600">Total Cost:</span>
                            <span class="font-semibold text-gray-900">₱<?php echo number_format(array_sum(array_column($fuelTrends, 'cost'))); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Cost per Vehicle -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-bar text-primary-green"></i>
                        Cost per Vehicle
                    </h3>
                    <div class="h-64">
                        <canvas id="vehicleCostChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Fleet Average:</span>
                            <span class="font-semibold text-gray-900">₱<?php echo number_format($vehicleCosts['fleet_average']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-primary-green"></i>
                    Filter Expenses
                </h3>
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
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Vehicle</label>
                        <select id="filterVehicle" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Vehicles</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?php echo $vehicle['plate']; ?>"><?php echo $vehicle['plate']; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                        <select id="filterCategory" onchange="filterTable()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-white">
                            <option value="">All Categories</option>
                            <option value="Fuel">Fuel</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Repairs">Repairs</option>
                            <option value="Licensing">Licensing</option>
                            <option value="Misc">Miscellaneous</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 flex gap-3">
                    <input type="text" id="searchInput" placeholder="Search expenses..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">
                    <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-2" onclick="applyFilters()">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-2" onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Expense Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-table text-primary-green"></i>
                            Expense Records
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

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse" id="expenseTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Expense ID</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Category</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Amount</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Vehicle</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Description</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($expenses as $expense): 
                                $categoryColors = [
                                    'Fuel' => 'bg-blue-100 text-blue-800',
                                    'Maintenance' => 'bg-green-100 text-green-800',
                                    'Repairs' => 'bg-red-100 text-red-800',
                                    'Licensing' => 'bg-yellow-100 text-yellow-800',
                                    'Misc' => 'bg-purple-100 text-purple-800'
                                ];
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 text-sm font-semibold text-primary-green"><?php echo $expense['expense_id']; ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo date('M d, Y', strtotime($expense['date'])); ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo $categoryColors[$expense['category']]; ?>">
                                        <?php echo $expense['category']; ?>
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm font-bold text-gray-900">₱<?php echo number_format($expense['amount']); ?></td>
                                <td class="px-5 py-4 text-sm text-gray-700"><?php echo $expense['vehicle']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-primary-green rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            <?php echo strtoupper(substr($expense['driver'], 0, 2)); ?>
                                        </div>
                                        <span class="text-gray-900"><?php echo $expense['driver']; ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600"><?php echo $expense['description']; ?></td>
                                <td class="px-5 py-4 text-sm">
                                    <button class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-1.5" onclick='viewExpenseDetail(<?php echo json_encode($expense); ?>)'>
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing <strong>1-<?php echo count($expenses); ?></strong> of <strong><?php echo count($expenses); ?></strong> results
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <button class="px-3 py-1.5 bg-primary-green text-white rounded-md text-sm font-semibold">1</button>
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">2</button>
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Optimization Insights Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" id="insightsModal">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <i class="fas fa-lightbulb text-3xl"></i>
                    <div>
                        <h3 class="text-2xl font-bold">Optimization Insights</h3>
                        <p class="text-sm text-white text-opacity-80">AI-powered recommendations to reduce costs</p>
                    </div>
                </div>
                <button onclick="closeInsightsModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Potential Savings Summary -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 mb-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Total Potential Savings</div>
                            <div class="text-4xl font-bold text-green-600">₱<?php echo number_format(array_sum(array_column($insights, 'potential_savings'))); ?></div>
                            <div class="text-sm text-gray-600 mt-1">By implementing all recommendations</div>
                        </div>
                        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-piggy-bank text-white text-3xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Insights List -->
                <div class="space-y-4">
                    <?php foreach ($insights as $insight): 
                        $priorityColors = [
                            'High' => 'bg-red-100 text-red-800 border-red-300',
                            'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'Low' => 'bg-blue-100 text-blue-800 border-blue-300'
                        ];
                        $iconColors = [
                            'red' => 'bg-red-100 text-red-600',
                            'yellow' => 'bg-yellow-100 text-yellow-600',
                            'blue' => 'bg-blue-100 text-blue-600',
                            'green' => 'bg-green-100 text-green-600'
                        ];
                    ?>
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-5 hover:shadow-lg transition-all">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 <?php echo $iconColors[$insight['color']]; ?> rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas <?php echo $insight['icon']; ?> text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="text-lg font-bold text-gray-900"><?php echo $insight['title']; ?></h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border <?php echo $priorityColors[$insight['priority']]; ?>">
                                        <?php echo $insight['priority']; ?> Priority
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-3"><?php echo $insight['description']; ?></p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-green-50 px-3 py-1 rounded-full">
                                            <span class="text-sm font-bold text-green-600">Potential Savings: ₱<?php echo number_format($insight['potential_savings']); ?></span>
                                        </div>
                                    </div>
                                    <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-2">
                                        <i class="fas fa-play"></i> Take Action
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="closeInsightsModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2" onclick="exportInsights()">
                        <i class="fas fa-download"></i> Export Insights Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Detail Modal -->
    <div class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" id="expenseModal">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold">Expense Details</h3>
                <button onclick="closeExpenseModal()" class="text-white hover:text-gray-200 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Basic Information</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Expense ID:</span>
                                <span class="font-semibold text-gray-900" id="detailExpenseId">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-semibold text-gray-900" id="detailDate">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Category:</span>
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold" id="detailCategory">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-bold text-gray-900 text-lg" id="detailAmount">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Vehicle & Driver</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Vehicle:</span>
                                <span class="font-semibold text-gray-900" id="detailVehicle">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Driver:</span>
                                <span class="font-semibold text-gray-900" id="detailDriver">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                    <p class="text-sm text-gray-900" id="detailDescription">-</p>
                </div>

                <div class="flex gap-3">
                    <button class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all duration-300" onclick="closeExpenseModal()">
                        Close
                    </button>
                    <button class="flex-1 px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all duration-300 inline-flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i> Edit Expense
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Expense Breakdown Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($summary['category_breakdown'])); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($summary['category_breakdown'])); ?>,
                    backgroundColor: ['#3B82F6', '#10B981', '#EF4444', '#F59E0B', '#8B5CF6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '₱' + context.parsed.toLocaleString();
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Fuel Consumption Trends Chart
        const fuelTrendCtx = document.getElementById('fuelTrendChart').getContext('2d');
        new Chart(fuelTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($fuelTrends, 'month')); ?>,
                datasets: [
                    {
                        label: 'Consumption (L)',
                        data: <?php echo json_encode(array_column($fuelTrends, 'consumption')); ?>,
                        borderColor: '#2D7A5C',
                        backgroundColor: 'rgba(45, 122, 92, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Cost (₱)',
                        data: <?php echo json_encode(array_column($fuelTrends, 'cost')); ?>,
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
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Liters'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Cost (₱)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });

        // Vehicle Cost Comparison Chart
        const vehicleCostCtx = document.getElementById('vehicleCostChart').getContext('2d');
        new Chart(vehicleCostCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($vehicleCosts['vehicle_costs'])); ?>,
                datasets: [
                    {
                        label: 'Vehicle Cost',
                        data: <?php echo json_encode(array_values($vehicleCosts['vehicle_costs'])); ?>,
                        backgroundColor: '#2D7A5C'
                    },
                    {
                        label: 'Fleet Average',
                        data: Array(<?php echo count($vehicleCosts['vehicle_costs']); ?>).fill(<?php echo $vehicleCosts['fleet_average']; ?>),
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
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '₱' + context.parsed.y.toLocaleString();
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const vehicleFilter = document.getElementById('filterVehicle').value;
            const driverFilter = document.getElementById('filterDriver').value;
            const categoryFilter = document.getElementById('filterCategory').value;

            const rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                const expenseId = row.cells[0].textContent.toLowerCase();
                const vehicle = row.cells[4].textContent;
                const driver = row.cells[5].textContent;
                const category = row.cells[2].textContent.trim();
                const description = row.cells[6].textContent.toLowerCase();

                const matchesSearch = expenseId.includes(searchInput) || description.includes(searchInput);
                const matchesVehicle = !vehicleFilter || vehicle.includes(vehicleFilter);
                const matchesDriver = !driverFilter || driver.includes(driverFilter);
                const matchesCategory = !categoryFilter || category === categoryFilter;

                row.style.display = matchesSearch && matchesVehicle && matchesDriver && matchesCategory ? '' : 'none';
            });
        }

        function viewAllInsights() {
            document.getElementById('insightsModal').classList.remove('hidden');
            document.getElementById('insightsModal').classList.add('flex');
        }

        function closeInsightsModal() {
            document.getElementById('insightsModal').classList.add('hidden');
            document.getElementById('insightsModal').classList.remove('flex');
        }

        function viewExpenseDetail(expense) {
            const categoryColors = {
                'Fuel': 'bg-blue-100 text-blue-800',
                'Maintenance': 'bg-green-100 text-green-800',
                'Repairs': 'bg-red-100 text-red-800',
                'Licensing': 'bg-yellow-100 text-yellow-800',
                'Misc': 'bg-purple-100 text-purple-800'
            };

            document.getElementById('detailExpenseId').textContent = expense.expense_id;
            document.getElementById('detailDate').textContent = new Date(expense.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            const categoryEl = document.getElementById('detailCategory');
            categoryEl.textContent = expense.category;
            categoryEl.className = 'inline-block px-2 py-1 rounded-full text-xs font-semibold ' + categoryColors[expense.category];
            
            document.getElementById('detailAmount').textContent = '₱' + expense.amount.toLocaleString();
            document.getElementById('detailVehicle').textContent = expense.vehicle;
            document.getElementById('detailDriver').textContent = expense.driver;
            document.getElementById('detailDescription').textContent = expense.description;

            document.getElementById('expenseModal').classList.remove('hidden');
            document.getElementById('expenseModal').classList.add('flex');
        }

        function closeExpenseModal() {
            document.getElementById('expenseModal').classList.add('hidden');
            document.getElementById('expenseModal').classList.remove('flex');
        }

        function applyFilters() {
            filterTable();
            alert('Filters applied successfully!');
        }

        function resetFilters() {
            document.getElementById('filterDateStart').value = '';
            document.getElementById('filterDateEnd').value = '';
            document.getElementById('filterVehicle').value = '';
            document.getElementById('filterDriver').value = '';
            document.getElementById('filterCategory').value = '';
            document.getElementById('searchInput').value = '';
            filterTable();
        }

        function exportPDF() {
            alert('Exporting expense report to PDF...');
        }

        function exportExcel() {
            alert('Exporting expense report to Excel...');
        }

        function exportInsights() {
            alert('Exporting optimization insights report...');
        }

        // Close modals when clicking outside
        document.getElementById('insightsModal').addEventListener('click', function(e) {
            if (e.target === this) closeInsightsModal();
        });

        document.getElementById('expenseModal').addEventListener('click', function(e) {
            if (e.target === this) closeExpenseModal();
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