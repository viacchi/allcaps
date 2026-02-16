<?php 
include 'includes/functions.php'; 

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = submitFuelLog($_POST, $_FILES);
    $message = $result['message'];
    $status = $result['status'] ? 'success' : 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Log Fuel</title>
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
            
            <div class="max-w-2xl mx-auto">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-gray-900">Fuel Log</h2>
                    <p class="text-gray-500">Record your refueling expenses</p>
                </div>

                <?php if ($message): ?>
                <div class="mb-4 p-4 rounded-xl <?php echo $status == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> text-center font-bold">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-5">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Total Cost (PHP)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-400">₱</span>
                                    <input type="number" name="cost" step="0.01" required class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-green focus:bg-white transition" placeholder="0.00">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Liters</label>
                                <div class="relative">
                                    <input type="number" name="liters" step="0.01" required class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-green focus:bg-white transition" placeholder="0">
                                    <span class="absolute right-4 top-3 text-gray-400 font-bold text-xs">L</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Current Odometer</label>
                            <input type="number" name="odometer" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-green focus:bg-white transition" placeholder="e.g. 45020">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Receipt</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 hover:border-primary-green transition cursor-pointer group" onclick="document.getElementById('receipt').click()">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 group-hover:bg-green-50 group-hover:text-primary-green transition">
                                    <i class="fas fa-camera text-xl"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-700">Tap to take photo</p>
                                <p class="text-xs text-gray-400">or drag and drop file here</p>
                                <input type="file" name="receipt" id="receipt" class="hidden" accept="image/*">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary-green text-white font-bold py-4 rounded-xl shadow-lg shadow-green-100 hover:bg-[#1F5240] transition transform hover:-translate-y-1">
                            Submit Log
                        </button>

                    </form>
                </div>
            </div>

        </main>
    </div>
</body>
</html>