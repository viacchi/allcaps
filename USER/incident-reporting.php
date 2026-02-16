<?php
include 'includes/functions.php';
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (reportIncident($_POST, $_FILES)) {
        $msg = "Incident reported successfully. Admin notified.";
        $msg_type = "success";
    } else {
        $msg = "Error submitting report.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Report Incident</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'primary-green': '#2D7A5C', 'dark-green': '#1F5240' } } } }
    </script>
</head>
<body class="bg-gray-50 font-sans">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6">
            
            <div class="max-w-2xl mx-auto">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 text-red-600 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i> Report Incident
                    </h2>
                    <p class="text-gray-500 text-sm">Report accidents, breakdowns, or violations immediately.</p>
                </div>

                <?php if ($msg): ?>
                <div class="p-4 mb-4 rounded-lg <?php echo $msg_type == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                    <?php echo $msg; ?>
                </div>
                <?php endif; ?>

                <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type of Incident</label>
                        <select name="type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-green">
                            <option>Accident</option>
                            <option>Breakdown</option>
                            <option>Traffic Violation</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Severity Level</label>
                        <div class="flex gap-4">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="severity" value="Low" class="peer sr-only">
                                <div class="text-center py-2 bg-gray-100 rounded-lg peer-checked:bg-yellow-400 peer-checked:text-black font-bold text-sm transition">Low</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="severity" value="Medium" class="peer sr-only">
                                <div class="text-center py-2 bg-gray-100 rounded-lg peer-checked:bg-orange-500 peer-checked:text-white font-bold text-sm transition">Medium</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="severity" value="High" class="peer sr-only">
                                <div class="text-center py-2 bg-gray-100 rounded-lg peer-checked:bg-red-600 peer-checked:text-white font-bold text-sm transition">High</div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Location</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-4 top-3.5 text-gray-400"></i>
                            <input type="text" name="location" placeholder="e.g. EDSA near Cubao" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-green">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description</label>
                        <textarea name="description" rows="4" placeholder="Describe what happened..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-green"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Photo Evidence</label>
                        <input type="file" name="evidence" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-green file:text-white hover:file:bg-dark-green transition">
                    </div>

                    <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-red-700 transition">
                        SUBMIT REPORT
                    </button>

                </form>
            </div>

        </main>
    </div>
</body>
</html>