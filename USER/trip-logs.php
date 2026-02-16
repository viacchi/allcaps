<?php
include 'includes/functions.php';
$history = getTripHistory();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Trip History</title>
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
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Trip History</h2>
            <p class="text-gray-500 mb-6 text-sm">Your past completed routes.</p>

            <div class="space-y-4">
                <?php if (empty($history)): ?>
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <i class="fas fa-history text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">No completed trips yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($history as $row): 
                        $isSuccess = $row['status'] === 'Completed';
                        // Check if a proof image exists for this specific trip
                        $proof_path = !empty($row['proof_image']) ? '../uploads/' . $row['proof_image'] : '';
                    ?>
                    <div class="bg-white rounded-2xl p-5 shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-gray-100 flex flex-col sm:flex-row gap-4">
                        <div class="flex sm:flex-col items-center justify-center bg-gray-50 rounded-xl p-3 sm:w-20 min-w-[80px]">
                            <span class="text-xs font-bold text-gray-400 uppercase"><?php echo date('M', strtotime($row['dispatch_date'])); ?></span>
                            <span class="text-xl font-bold text-gray-800"><?php echo date('d', strtotime($row['dispatch_date'])); ?></span>
                        </div>

                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-gray-800">#<?php echo $row['tracking_id']; ?></h3>
                                <span class="text-[10px] font-bold px-2 py-1 rounded <?php echo $isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                    <?php echo strtoupper($row['status']); ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                                <i class="fas fa-map-marker-alt text-red-500"></i>
                                <span><?php echo htmlspecialchars($row['destination']); ?></span>
                            </div>
                            <p class="text-xs text-gray-400">Vehicle: <?php echo htmlspecialchars($row['plate'] ?? 'Unknown'); ?></p>
                        </div>

                        <div class="flex items-center">
                            <button onclick="showReceipt('<?php echo $proof_path; ?>', '<?php echo $row['tracking_id']; ?>')" class="w-full sm:w-auto px-4 py-2 <?php echo !empty($proof_path) ? 'bg-blue-50 hover:bg-blue-100 text-blue-600' : 'bg-gray-100 hover:bg-gray-200 text-gray-600'; ?> rounded-lg text-xs font-bold transition flex items-center justify-center gap-2">
                                <i class="fas fa-image"></i> View Receipt
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div id="receipt-modal" class="fixed inset-0 bg-black/80 z-[9999] hidden flex-col items-center justify-center p-4 backdrop-blur-sm transition-all duration-300 opacity-0">
        <div class="w-full max-w-lg bg-white rounded-2xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300" id="receipt-content">
            
            <div class="px-5 py-4 flex justify-between items-center border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-camera text-blue-500"></i>
                    Proof of Delivery <span id="modal-tracking-id" class="text-xs text-gray-400 ml-2"></span>
                </h3>
                <button onclick="closeReceipt()" class="text-gray-400 hover:text-red-500 text-xl transition bg-white h-8 w-8 rounded-full shadow-sm flex items-center justify-center border border-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6 bg-gray-100 flex items-center justify-center min-h-[300px]">
                <img id="receipt-image" src="" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-md hidden border-4 border-white">
                
                <div id="no-receipt-msg" class="text-gray-400 font-medium flex flex-col items-center hidden">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-image-slash text-2xl text-gray-400"></i>
                    </div>
                    <p>No proof image uploaded for this trip.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Modal Logic
        const modal = document.getElementById('receipt-modal');
        const modalContent = document.getElementById('receipt-content');
        const img = document.getElementById('receipt-image');
        const msg = document.getElementById('no-receipt-msg');
        const trackTitle = document.getElementById('modal-tracking-id');

        function showReceipt(imageUrl, trackingId) {
            trackTitle.innerText = "#" + trackingId;
            
            // Check if URL is not empty
            if (imageUrl !== '') {
                img.src = imageUrl;
                img.classList.remove('hidden');
                msg.classList.add('hidden');
            } else {
                img.src = '';
                img.classList.add('hidden');
                msg.classList.remove('hidden');
            }
            
            // Animate In
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
        }

        function closeReceipt() {
            // Animate Out
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300); // Wait for transition to finish
        }

        // Close when clicking outside the image
        modal.addEventListener('click', function(e) {
            if (e.target === modal) { closeReceipt(); }
        });
    </script>
</body>
</html>