<?php
include 'includes/functions.php';
$trips = getAssignedTrips();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>My Trips</title>
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
            
            <?php if (isset($_GET['status']) && $_GET['status'] == 'completed'): ?>
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl flex items-center gap-3 shadow-sm border border-green-200">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">Great job! Trip marked as completed successfully.</span>
            </div>
            <?php endif; ?>

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">My Trips</h2>
            </div>

            <div class="space-y-4">
                <?php if (empty($trips)): ?>
                    <div class="text-center py-10 bg-white rounded-2xl border border-gray-100">
                        <i class="fas fa-route text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">No trips history found.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($trips as $row): 
                        // DYNAMIC STATUS COLORS
                        $statusClass = match(strtolower($row['status'])) {
                            'on duty' => 'bg-blue-100 text-blue-700',
                            'assigned' => 'bg-yellow-100 text-yellow-700',
                            'completed' => 'bg-green-100 text-green-700', // Completed logic
                            'cancelled' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-600'
                        };
                    ?>
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-4 pl-3">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase">Trip ID</span>
                                <h3 class="text-lg font-bold text-gray-900">#<?php echo htmlspecialchars($row['tracking_id']); ?></h3>
                            </div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-4 pl-3 mb-4">
                            <div class="flex flex-col items-center gap-1">
                                <div class="w-3 h-3 rounded-full <?php echo ($row['status'] == 'Completed') ? 'bg-gray-400' : 'bg-primary-green'; ?>"></div>
                                <div class="w-0.5 h-8 bg-gray-200"></div>
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            </div>
                            <div class="flex-1 space-y-4">
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Pickup</p>
                                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($row['pickup']); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase">Destination</p>
                                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($row['destination']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="pl-3 pt-3 border-t border-gray-50 flex justify-end">
                            <?php if(in_array($row['status'], ['Assigned', 'On Duty'])): ?>
                                <a href="live-tracker.php" class="text-sm font-bold text-primary-green hover:underline">
                                    Continue Trip <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">Archived</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>