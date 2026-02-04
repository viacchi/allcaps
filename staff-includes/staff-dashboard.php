<?php
include 'staff-includes/staff-functions.php';
$kpi = getKPIData();
$tracks = getTrack();
$logs = getLogs();
$driverReviews = getdriverReviews();
$performance = getPerform();

$totalFuelCost = 0;
$totalLiters = 0;
$count = count($tracks);

foreach ($tracks as $t) {
    $totalFuelCost += floatval(str_replace(',', '', $t['cost']));
    $totalLiters   += floatval(str_replace([' L',' '], '', $t['liters']));
}

$averageFuelCost = $count > 0 ? $totalFuelCost / $count : 0;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard - Logistics Staff</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
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
        <?php include 'staff-includes/staff-sidebar.php'; ?>

        <!-- Main Content -->
        <div class="ml-0 md:ml-[280px] min-h-screen">
            <!-- Header -->
            <?php include 'staff-includes/staff-header.php'; ?>

            <!-- Page Content -->
            <main class="p-6">
                <!-- Page Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
                    <p class="text-gray-600 mt-2">
                        Welcome to your logistics management system
                    </p>
                </div>

                <!-- KPI Cards -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Assigned Vehicles -->
                    <div
                        class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-primary-green">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-600 text-sm font-medium">
                                    Total Assigned Vehicles
                                </div>
                                <div
                                    class="text-3xl font-bold text-gray-900 my-2">
                                    <?php echo $kpi['assigned_total'];?>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium text-green-600">
                            <i class="fas fa-car"></i> In fleet
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-slate-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-600 text-sm font-medium">
                                    Total Drivers
                                </div>
                                <div class="text-3xl font-bold text-gray-900 my-2">
                                    <?php echo $kpi['total_Drivers']; ?>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-users text-slate-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-rose-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-600 text-sm font-medium">
                                    Total Incidents
                                </div>
                                <div class="text-3xl font-bold text-gray-900 my-2">
                                    <?php echo $kpi ['totalIncidents']; ?>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-folder-closed text-rose-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium text-rose-600">
                            <i class="fa-solid fa-clipboard-list"></i> Total Incidents Reports
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-amber-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-gray-600 text-sm font-medium">
                                    Total Trips
                                </div>
                                <div class="text-3xl font-bold text-gray-900 my-2">
                                    <?php echo $kpi ['total_trips']; ?>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-road text-amber-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium text-amber-600">
                            <i class="fas fa-route"></i> Trips average
                        </div>
                    </div>
                </div>


                <!-- WRAPPER GRID -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">


                <!-- LEFT: KPI Chart (2 columns wide) -->

                <div class="lg:col-span-2 bg-white rounded-lg p-4 shadow-sm">
                <h3 class="text-lg font-bold mb-4">
                Fuel, Mileage & Expense Tracking KPI Overview
            </h3>


            <div class="h-70">
            <canvas id="fuelKPIChart"></canvas>
        </div>
    </div>
    
    <!-- RIGHT: Pinned Locations -->
     <div class="bg-white rounded-lg p-5 shadow-sm">
        <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-red-500"></i>
            Pinned Locations
        </h3>

        <div class="space-y-4 max-h-[320px] overflow-y-auto">
            <?php foreach ($logs as $log): ?>
                <div class="border rounded-lg p-3 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                        <span class="font-semibold text-sm text-gray-800"><?= $log['vehicle']; ?></span>
                        <span class="text-xs px-2 py-0.5 rounded full bg-gray-100 text-gary-600"><?= $log['model'] ?></span>
                    </div>
                        <span class="text-xs text-gray-500">
                            <?= date('M d', strtotime($log['date'])); ?>
                        </span>
                    </div>

                    <div class="text-xs text-gray-600 mt-2">
                        <i class="fa-solid fa-play text-green-500"></i>
                        <?= $log['start']; ?>
                    </div>

                    <div class="text-xs text-gray-600 mt-1">
                        <i class="fa-solid fa-flag-checkered text-red-500"></i>
                        <?= $log['destination']; ?>
                    </div>

                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span><i class="fa-solid fa-road"></i> <?= $log['distance']; ?></span>
                        <span><i class="fa-solid fa-clock"></i> <?= $log['duration']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<div class="bg-white rounded-lg p-6 shadow-sm mb-8">
    <h3 class="text-lg font-bold mb-4">
        Driver Performance Overview
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($performance as $driver): ?>
            <div class="border rounded-lg p-4 hover:shadow-md transition">

                <!-- Driver Header -->
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">
                            <?= $driver['driver']; ?>
                        </h4>
                        <p class="text-xs text-gray-500">
                            ID #<?= str_pad($driver['id'], 3, '0', STR_PAD_LEFT); ?>
                        </p>
                    </div>

                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        <?= $driver['rating'] >= 4.5 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                        ⭐ <?= $driver['rating']; ?>
                    </span>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-2 text-center text-xs mb-4">
                    <div>
                        <p class="font-bold text-gray-800"><?= $driver['trips']; ?></p>
                        <p class="text-gray-500">Trips</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800"><?= $driver['feedback']; ?></p>
                        <p class="text-gray-500">Reviews</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800"><?= $driver['rating']; ?></p>
                        <p class="text-gray-500">Rating</p>
                    </div>
                </div>


                <!-- Action -->
                <button
                    onclick='openDriverReviews(<?= json_encode($driver["driver"]); ?>)'
                    class="w-full text-sm px-3 py-2 bg-primary-green text-white rounded-md hover:bg-dark-green transition">
                        <i class="fas fa-eye"></i> View Reviews
                </button>

            <div id="driverReviewModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg w-full max-w-xl p-6">
                     <div class="bg-primary-green text-white px-6 py-4 flex justify-between items-center rounded-t-lg">
                <h3 id="reviewDriverName" class="text-lg font-bold"></h3>
                   <button onclick="closeDriverReviews()" class="text-xl">&times;</button>
                </div>
            <div id="reviewList" class="space-y-4 max-h-[300px] overflow-y-auto"></div>
                   <button id="seeMoreBtn" onclick="loadMoreReviews()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">See More Feedback</button>     
            </div>
        </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>

</main>
</div>

        <script>
            // Set active page title
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('pageTitle').textContent = 'Dashboard';
            });

            const kpiCtx = document.getElementById('fuelKPIChart');
            
            new Chart(kpiCtx, {
                type: 'bar',
                
                data: {
        
                    labels: ['Total Cost (₱)', 'Total Liters', 'Avg Cost'],
        
                    datasets: [{
                        label: 'Fuel KPIs',
         
                        data: [
                            <?php echo $totalFuelCost; ?>,
                            <?php echo $totalLiters; ?>,
                            <?php echo $averageFuelCost; ?>
            
                        ],
            
                        backgroundColor: [
            
                            'rgba(147, 51, 234, 0.6)', 
                            'rgba(34, 197, 94, 0.6)',
                            'rgba(59, 130, 246, 0.6)'
                        ],
            
                        borderWidth: 1
                    }]
                 },
    
                 options: {
    
                    responsive: true,
                    plugins: {

                   legend: { display: false }
        
                },
        
                scales: {
        
                    y: { beginAtZero: true }
       
                }
            }
        });


        const driverReviews = <?= json_encode($driverReviews); ?>;

        function openDriverReviews(driverName) {
            document.getElementById('reviewDriverName').textContent = driverName;
            const list = document.getElementById('reviewList');
            list.innerHTML = '';

            if (!driverReviews[driverName]) {
                list.innerHTML = '<p class="text-sm text-gray-500">No reviews found.</p>';
            } else {
  
                driverReviews[driverName].forEach(r => {
                    list.innerHTML += `
                    <div class="border rounded-lg p-3">
                    <div class="flex justify-between mb-1">
                    <span class="font-semibold text-sm">${r.name}</span>
                        <span class="text-xs text-gray-500">${r.date}</span>
                    </div>
                    <div class="text-xs text-yellow-500 mb-1">
                        ⭐ ${r.rating}/5
                    </div>
                    <p class="text-sm text-gray-700">${r.message}</p>
                </div>
            `;
        });
    }

    document.getElementById('driverReviewModal').classList.remove('hidden');
    document.getElementById('seemoreBtn').style.display = "none";
    document.getElementById('seemoreBtn').style.display = allReviews[currentDriver].length > limit ? "block" : "none";
}

function closeDriverReviews() {
    document.getElementById('driverReviewModal').classList.add('hidden');

}

        </script>
    </body>
</html>