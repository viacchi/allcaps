<?php
include 'includes/functions.php';
$kpi = getUserKPI();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>My Performance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'primary-green': '#2D7A5C' } } } }
    </script>
</head>
<body class="bg-gray-50 font-sans">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Stats</h2>

            <div class="bg-gradient-to-r from-primary-green to-[#1F5240] rounded-2xl p-6 text-white shadow-lg mb-6 flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-xs font-bold uppercase mb-1">Overall Rating</p>
                    <h1 class="text-4xl font-bold"><?php echo $kpi['safety_score']; ?>/100</h1>
                    <p class="text-sm mt-1 opacity-90">Based on safe driving & delivery times.</p>
                </div>
                <div class="w-20 h-20 border-4 border-white/30 rounded-full flex items-center justify-center text-2xl font-bold bg-white/10 backdrop-blur-md">
                    A+
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center"><i class="fas fa-clock"></i></div>
                        <span class="text-xs font-bold text-gray-400">ON TIME</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">98%</h3>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2"><div class="bg-blue-500 h-1.5 rounded-full" style="width: 98%"></div></div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center"><i class="fas fa-gas-pump"></i></div>
                        <span class="text-xs font-bold text-gray-400">EFFICIENCY</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">4.5 km/L</h3>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full mt-2"><div class="bg-purple-500 h-1.5 rounded-full" style="width: 85%"></div></div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center"><i class="fas fa-check-circle"></i></div>
                        <span class="text-xs font-bold text-gray-400">COMPLETED</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800"><?php echo $kpi['completed_trips']; ?></h3>
                    <p class="text-xs text-gray-400 mt-2">Total successful trips</p>
                </div>
            </div>

        </main>
    </div>
</body>
</html>