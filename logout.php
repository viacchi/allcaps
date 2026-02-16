<?php
// Start the session so we can destroy it
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session completely
session_destroy();

// Tell the browser to delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect back to the login page (assuming login.php is in the main public_html folder)
header("Location: ../index.php");
exit();
?>