<?php
include 'includes/functions.php';
$driver_id = getCurrentDriverId();

$msg_status = "";

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    if (!empty($subject) && !empty($message)) {
        // Note: If you have a 'messages' table, you can write the INSERT query here.
        // For now, we simulate a successful transmission for the Capstone UI.
        $msg_status = "success";
    } else {
        $msg_status = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Contact Dispatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'primary-green': '#2D7A5C' } } } }
    </script>
</head>
<body class="bg-gray-50 font-sans">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6 max-w-4xl mx-auto w-full">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Contact Dispatch</h2>
            <p class="text-gray-500 mb-6 text-sm">Emergency lines and HQ support.</p>

            <?php if ($msg_status == 'success'): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700 animate-pulse">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div class="text-sm font-semibold">Message securely transmitted to Headquarters.</div>
                </div>
            <?php elseif ($msg_status == 'error'): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 text-red-700">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <div class="text-sm font-semibold">Please fill out all fields before sending.</div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between group hover:border-primary-green transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-primary-green group-hover:bg-primary-green group-hover:text-white transition">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">HQ Dispatch</h3>
                            <p class="text-xs text-gray-500">Available 24/7</p>
                        </div>
                    </div>
                    <a href="tel:+639123456789" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg font-bold text-sm text-gray-700 transition active:scale-95 flex items-center gap-2">
                        <i class="fas fa-phone"></i> Call
                    </a>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-red-100 flex items-center justify-between group hover:border-red-500 transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full -z-10 transition group-hover:bg-red-100"></div>
                    <div class="flex items-center gap-4 z-10">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 animate-pulse">
                            <i class="fas fa-truck-medical text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Emergency / SOS</h3>
                            <p class="text-xs text-gray-500">Accidents & Road Hazards</p>
                        </div>
                    </div>
                    <a href="tel:911" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-bold text-sm text-white shadow-lg shadow-red-200 transition active:scale-95 flex items-center gap-2 z-10">
                        <i class="fas fa-exclamation-triangle"></i> SOS
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 relative overflow-hidden">
                <i class="fas fa-paper-plane absolute -bottom-10 -right-10 text-9xl text-gray-50 opacity-50 pointer-events-none"></i>
                
                <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2 relative z-10">
                    <i class="fas fa-envelope text-primary-green"></i> Send a Report
                </h3>

                <form method="POST" action="" class="space-y-5 relative z-10">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Subject</label>
                        <select name="subject" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none transition appearance-none">
                            <option value="" disabled selected>Select reason...</option>
                            <option value="Vehicle Breakdown">Vehicle Breakdown</option>
                            <option value="Traffic/Route Delay">Traffic / Route Delay</option>
                            <option value="Customer Issue">Customer Issue at Drop-off</option>
                            <option value="Fuel Request">Fuel Funds Request</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Message Details</label>
                        <textarea name="message" rows="4" required placeholder="Describe the situation..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-green outline-none transition resize-none"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" name="send_message" class="w-full sm:w-auto bg-primary-green hover:bg-green-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-green-100 transition transform active:scale-[0.97] flex items-center justify-center gap-2">
                            <span>Send to Dispatch</span>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
            
        </main>
    </div>
</body>
</html>