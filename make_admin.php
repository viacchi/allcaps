<?php
// FILE: ALLCAPS-MAIN/make_admin.php
include 'ADMIN/includes/db.php'; 

// 1. SETTINGS
$name = "Super Admin";
$email = "admin@test.com";
$phone = "09000000000";
$raw_password = "12345"; // <--- THIS WILL BE YOUR PASSWORD
$role = "Admin";

// 2. ENCRYPT THE PASSWORD
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

// 3. INSERT INTO DATABASE
$sql = "INSERT INTO users (full_name, email, phone_number, password, role, status) 
        VALUES ('$name', '$email', '$phone', '$hashed_password', '$role', 'Active')";

if (mysqli_query($conn, $sql)) {
    echo "<h1>✅ Success!</h1>";
    echo "<p>User created.</p>";
    echo "<ul>";
    echo "<li>Email: <b>$email</b></li>";
    echo "<li>Password: <b>$raw_password</b></li>";
    echo "</ul>";
    echo "<br><a href='ADMIN/index.php'>Go to Login</a>"; // Adjust if login is elsewhere
} else {
    echo "<h1>❌ Error</h1>";
    echo mysqli_error($conn);
}
?>