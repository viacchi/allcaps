<?php
// Start the session
session_start();

// Check if driver is logged in
if (!isset($_SESSION['user_id']) || strtolower(trim($_SESSION['role'])) !== 'driver') {
    echo "<p>Please log in as a driver to view files.</p>";
    exit;
}

// Get the full name
$full_name = $_SESSION['full_name'] ?? 'Driver';

// Generate initials from full name
$names = explode(' ', $full_name);
$initials = '';
foreach ($names as $name) {
    $initials .= strtoupper($name[0]);
}
$initials = substr($initials, 0, 2); // limit to first 2 initials
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen font-sans">

    <div class="text-center p-6 bg-white rounded-lg shadow-md w-80">
        <!-- Initials Circle -->
        <div class="w-16 h-16 mx-auto bg-green-600 rounded-full flex items-center justify-center text-white text-xl font-bold mb-4">
            <?php echo $initials; ?>
        </div>

        <!-- Full Name -->
        <h1 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($full_name); ?></h1>

        <!-- Message -->
        <p class="text-gray-600">There are no files yet from your point of view.</p>
    </div>

</body>
</html>
