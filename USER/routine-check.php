<?php
include 'includes/functions.php';
$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $res = submitRoutineCheck($_POST);
    $msg = $res['message'];
    $msg_type = $res['status'] ? 'success' : 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Routine Check</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'primary-green': '#2D7A5C', 'dark-green': '#1F5240' } } } }
    </script>
</head>
<body class="bg-gray-50 font-sans pb-10">

    <?php include 'includes/sidebar.php'; ?>

    <div class="md:pl-72 transition-all duration-300 min-h-screen flex flex-col">
        <?php include 'includes/header.php'; ?>

        <main class="flex-1 p-4 sm:p-6">
            
            <div class="max-w-3xl mx-auto">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-tasks text-primary-green"></i> Routine Inspection
                    </h2>
                    <p class="text-gray-500 text-sm">Perform your BLOWBAGETS check before driving.</p>
                </div>

                <?php if ($msg): ?>
                <div class="p-4 mb-4 rounded-lg <?php echo $msg_type == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> text-center font-bold">
                    <?php echo $msg; ?>
                </div>
                <?php endif; ?>

                <form action="" method="POST" class="space-y-6">
                    
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" value="Pre-Trip" checked class="peer sr-only">
                            <div class="py-3 text-center rounded-xl bg-gray-100 text-gray-500 peer-checked:bg-primary-green peer-checked:text-white font-bold transition">
                                Pre-Trip
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" value="Post-Trip" class="peer sr-only">
                            <div class="py-3 text-center rounded-xl bg-gray-100 text-gray-500 peer-checked:bg-primary-green peer-checked:text-white font-bold transition">
                                Post-Trip
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php 
                        $items = [
                            'battery' => ['icon' => 'fa-car-battery', 'label' => 'Battery'],
                            'lights' => ['icon' => 'fa-lightbulb', 'label' => 'Lights'],
                            'oil' => ['icon' => 'fa-oil-can', 'label' => 'Oil Level'],
                            'water' => ['icon' => 'fa-tint', 'label' => 'Water/Coolant'],
                            'brakes' => ['icon' => 'fa-shoe-prints', 'label' => 'Brakes'],
                            'air' => ['icon' => 'fa-wind', 'label' => 'Air Pressure'],
                            'gas' => ['icon' => 'fa-gas-pump', 'label' => 'Gas/Fuel'],
                            'engine' => ['icon' => 'fa-cogs', 'label' => 'Engine'],
                            'tire' => ['icon' => 'fa-circle-notch', 'label' => 'Tires & Tools']
                        ];
                        
                        foreach($items as $key => $item): ?>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-primary-green flex items-center justify-center">
                                    <i class="fas <?php echo $item['icon']; ?>"></i>
                                </div>
                                <span class="font-bold text-gray-700"><?php echo $item['label']; ?></span>
                            </div>
                            <div class="flex gap-1 bg-gray-100 p-1 rounded-lg">
                                <label class="cursor-pointer">
                                    <input type="radio" name="<?php echo $key; ?>" value="Pass" checked class="peer sr-only">
                                    <span class="block px-3 py-1 rounded-md text-xs font-bold text-gray-400 peer-checked:bg-green-500 peer-checked:text-white transition">Pass</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="<?php echo $key; ?>" value="Fail" class="peer sr-only">
                                    <span class="block px-3 py-1 rounded-md text-xs font-bold text-gray-400 peer-checked:bg-red-500 peer-checked:text-white transition">Fail</span>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Remarks / Issues Found</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-green" placeholder="Optional notes..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary-green hover:bg-dark-green text-white font-bold py-4 rounded-xl shadow-lg transition transform active:scale-95">
                        Submit Inspection
                    </button>

                </form>
            </div>

        </main>
    </div>
</body>
</html>