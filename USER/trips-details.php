<?php
// 1. Include the consolidated functions file
include 'includes/functions.php';

// 2. Fetch data (using the function we added to functions.php earlier)
$trips = getTrip(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Details - Driver Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "brand-primary": "#059669",
                        "brand-background-main": "#F0FDF4",
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 flex flex-col min-h-screen">
        
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-6 sm:p-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Trip Details</h2>
                    <p class="text-sm text-gray-500">View detailed information about your assigned routes.</p>
                </div>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Search trip ID..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent w-full sm:w-64">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                
                <?php if (empty($trips)): ?>
                    <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <i class="fas fa-route text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No trips assigned yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($trips as $row): 
                        // Status Colors
                        $status = strtolower($row['status']);
                        $badgeClass = match($status) {
                            'approved' => 'bg-emerald-100 text-emerald-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'rejected', 'cancelled' => 'bg-red-100 text-red-700',
                            'completed' => 'bg-blue-100 text-blue-700',
                            default => 'bg-gray-100 text-gray-600'
                        };
                    ?>
                    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-gray-100 hover:shadow-md transition-all duration-300 group">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-truck-moving"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">#<?php echo htmlspecialchars($row['id']); ?></h3>
                                    <p class="text-xs text-gray-500 font-medium"><?php echo htmlspecialchars($row['date']); ?></p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <div class="relative pl-4 border-l-2 border-dashed border-gray-200 space-y-6 mb-6 ml-2">
                            <div class="relative">
                                <span class="absolute -left-[23px] top-1 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white shadow-sm"></span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Pickup</p>
                                <p class="text-sm font-bold text-gray-800 leading-tight"><?php echo htmlspecialchars($row['pickup']); ?></p>
                            </div>
                            <div class="relative">
                                <span class="absolute -left-[23px] top-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white shadow-sm"></span>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-0.5">Destination</p>
                                <p class="text-sm font-bold text-gray-800 leading-tight"><?php echo htmlspecialchars($row['destination']); ?></p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="fas fa-car text-gray-400"></i>
                                <span><?php echo htmlspecialchars($row['vehicle']); ?></span>
                            </div>
                            
                            <button onclick='openDetailModal(<?php echo json_encode($row); ?>)' class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline transition-all">
                                View Details <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity opacity-0">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl transform scale-95 transition-transform duration-200 overflow-hidden" id="modalContent">
            
            <div class="bg-emerald-600 p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full"></div>
                <button onclick="closeModal()" class="absolute top-4 right-4 text-white/70 hover:text-white hover:bg-white/10 rounded-full w-8 h-8 flex items-center justify-center transition">
                    <i class="fas fa-times"></i>
                </button>
                
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider mb-1">Trip Details</p>
                <h3 class="text-2xl font-bold" id="modalID">#TRK-0000</h3>
            </div>

            <div class="p-6 space-y-4">
                
                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl">
                    <div>
                        <p class="text-xs text-gray-500">Scheduled Date</p>
                        <p class="text-sm font-bold text-gray-800" id="modalDate">--</p>
                    </div>
                    <span id="modalStatus" class="px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600">Pending</span>
                </div>

                <div class="flex items-center gap-4 border border-gray-100 p-4 rounded-xl">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl">
                        <i class="fas fa-truck-pickup"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800" id="modalVehicle">Vehicle Name</p>
                        <p class="text-xs text-gray-500" id="modalModel">Model Info</p>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <div class="w-0.5 h-full bg-gray-200 my-1"></div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Pickup Location</p>
                            <p class="text-sm font-semibold text-gray-800" id="modalPickup">--</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Destination</p>
                            <p class="text-sm font-semibold text-gray-800" id="modalDest">--</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Estimated Distance</span>
                    <span class="text-lg font-bold text-gray-900" id="modalDist">0 km</span>
                </div>

            </div>

            <div class="p-4 bg-gray-50 flex gap-3">
                <button onclick="closeModal()" class="flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-600 text-sm font-bold hover:bg-gray-100 transition">Close</button>
                <button class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition">
                    Start Trip <i class="fas fa-play ml-1 text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('detailModal');
        const modalContent = document.getElementById('modalContent');

        function openDetailModal(data) {
            // Populate Data
            document.getElementById('modalID').textContent = '#' + data.id;
            document.getElementById('modalDate').textContent = data.date;
            document.getElementById('modalVehicle').textContent = data.vehicle;
            document.getElementById('modalModel').textContent = data.model || 'Unknown Model';
            document.getElementById('modalPickup').textContent = data.pickup;
            document.getElementById('modalDest').textContent = data.destination;
            document.getElementById('modalDist').textContent = data.distance;

            // Status Styling
            const statusEl = document.getElementById('modalStatus');
            statusEl.textContent = data.status;
            statusEl.className = 'px-3 py-1 rounded-full text-xs font-bold uppercase ';
            
            const status = data.status.toLowerCase();
            if(status === 'approved') statusEl.classList.add('bg-emerald-100', 'text-emerald-700');
            else if(status === 'pending') statusEl.classList.add('bg-amber-100', 'text-amber-700');
            else if(status === 'rejected') statusEl.classList.add('bg-red-100', 'text-red-700');
            else statusEl.classList.add('bg-gray-200', 'text-gray-600');

            // Show Modal Animation
            modal.classList.remove('hidden');
            // Small timeout to allow removing 'hidden' before adding opacity for transition
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200); // Match CSS duration
        }

        // Close on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    </script>
</body>
</html>