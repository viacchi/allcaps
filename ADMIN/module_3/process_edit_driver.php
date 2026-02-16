<?php
session_start();

// Enable error reporting to a file, but keep JSON output clean
ini_set('log_errors', 1);
ini_set('error_log', 'edit_driver_errors.log');
error_reporting(E_ALL);

header('Content-Type: application/json');

// 1. Basic Security
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// 2. Connect to Database safely
$db_path = '../../includes/db.php';
if (file_exists($db_path)) {
    require_once $db_path;
} else {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Log incoming data for debugging
    error_log("Received POST data: " . print_r($_POST, true));
    if (isset($_FILES['profile_picture'])) {
        error_log("Received FILE data: " . print_r($_FILES['profile_picture'], true));
    }

    $user_id = intval($_POST['user_id'] ?? 0);
    $name = $_POST['name'] ?? '';
    $license = $_POST['license'] ?? '';
    $emergency = $_POST['emergency_contact'] ?? '';
    $status = $_POST['status'] ?? 'Active';
    
    // Grab the vehicle ID. If empty, set to NULL.
    $vehicle_id = !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null;

    if ($user_id === 0 || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Required fields missing.']);
        exit;
    }

    $db_photo_path = null;

    // 3. Process Profile Picture Upload (if the admin selected one)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_picture'];
        
        // Define paths carefully
        // Assuming your structure is: 
        // /allcaps-main/ADMIN/module_3/process_edit_driver.php
        // /allcaps-main/uploads/profile_pics/
        $upload_dir = '../../uploads/profile_pics/';
        
        // Ensure folder exists
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                error_log("Failed to create upload directory: " . $upload_dir);
                echo json_encode(['success' => false, 'message' => 'Server error: Cannot create upload folder.']);
                exit;
            }
        }

        // Security Validation
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid image format. JPG or PNG only.']);
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'Image must be under 5MB.']);
            exit;
        }

        // Generate filename & Save
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'driver_' . $user_id . '_' . time() . '.' . $ext;
        $target_file = $upload_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // The path stored in DB should be relative to the root, so both Admin and User can use '../'
            $db_photo_path = 'uploads/profile_pics/' . $filename;
            
            // Delete old photo to save space
            $stmt_old = $conn->prepare("SELECT profile_picture FROM drivers WHERE user_id = ?");
            if ($stmt_old) {
                $stmt_old->bind_param("i", $user_id);
                $stmt_old->execute();
                $res = $stmt_old->get_result();
                if ($row = $res->fetch_assoc()) {
                    $old_path = $row['profile_picture'];
                    // Careful deletion
                    if (!empty($old_path) && strpos($old_path, 'uploads/profile_pics/') === 0) {
                        $full_old_path = '../../' . $old_path;
                        if (file_exists($full_old_path)) {
                            unlink($full_old_path);
                        }
                    }
                }
            }
        } else {
            error_log("Failed to move uploaded file. Source: " . $file['tmp_name'] . " Dest: " . $target_file);
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded photo to server folder.']);
            exit;
        }
    } else if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        // Log specific upload errors
        error_log("Upload failed with error code: " . $_FILES['profile_picture']['error']);
        echo json_encode(['success' => false, 'message' => 'Upload error code: ' . $_FILES['profile_picture']['error']]);
        exit;
    }

    // 4. Update the Database
    if ($db_photo_path) {
        // Update EVERYTHING including the new photo
        $stmt = $conn->prepare("UPDATE drivers SET full_name = ?, license = ?, emergency_contact = ?, status = ?, assigned_vehicle_id = ?, profile_picture = ? WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssisi", $name, $license, $emergency, $status, $vehicle_id, $db_photo_path, $user_id);
        } else {
            error_log("Prepare failed (with photo): " . $conn->error);
        }
    } else {
        // Update text data ONLY (leave photo as is)
        $stmt = $conn->prepare("UPDATE drivers SET full_name = ?, license = ?, emergency_contact = ?, status = ?, assigned_vehicle_id = ? WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssii", $name, $license, $emergency, $status, $vehicle_id, $user_id);
        } else {
            error_log("Prepare failed (no photo): " . $conn->error);
        }
    }

    if ($stmt && $stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Execute failed: " . ($stmt ? $stmt->error : 'unknown'));
        echo json_encode(['success' => false, 'message' => 'Failed to update database record.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>