<?php
// FILE: USERS/includes/functions.php

// 1. Connect to Database
if (!isset($conn)) {
    // Navigate up to the main includes folder
    $paths = [
        __DIR__ . '/../../includes/db.php',
        __DIR__ . '/../includes/db.php',
        $_SERVER['DOCUMENT_ROOT'] . '/ALLCAPS-MAIN/includes/db.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper: Get the current Driver ID from the logged-in User ID
function getCurrentDriverId() {
    global $conn;
    if (!isset($_SESSION['user']['user_id'])) return 0;
    
    $user_id = $_SESSION['user']['user_id'];
    $stmt = $conn->prepare("SELECT id FROM drivers WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($row = $res->fetch_assoc()) {
        return $row['id'];
    }
    return 0;
}

// 1. Get Real KPI Data
function getUserKPI() {
    global $conn;
    $driver_id = getCurrentDriverId();
    
    // Count Upcoming (Assigned/On Duty)
    $sql_upcoming = "SELECT COUNT(*) FROM dispatches WHERE driver_id = ? AND status IN ('Assigned', 'On Duty', 'Pending')";
    $stmt = $conn->prepare($sql_upcoming);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $upcoming = $stmt->get_result()->fetch_row()[0] ?? 0;

    // Count Completed
    $sql_completed = "SELECT COUNT(*) FROM dispatches WHERE driver_id = ? AND status = 'Completed'";
    $stmt = $conn->prepare($sql_completed);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $completed = $stmt->get_result()->fetch_row()[0] ?? 0;

    // Sum Fuel Used (from fuel_expenses table)
    $sql_fuel = "SELECT SUM(liters) FROM fuel_expenses WHERE driver_id = ?";
    $stmt = $conn->prepare($sql_fuel);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $fuel = $stmt->get_result()->fetch_row()[0] ?? 0;

    // Safety Score (Placeholder until behavior module is active, defaults to 100)
    $sql_score = "SELECT safety_score FROM drivers WHERE id = ?";
    $stmt = $conn->prepare($sql_score);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    $res_score = $stmt->get_result();
    $score = ($row = $res_score->fetch_assoc()) ? ($row['safety_score'] ?? 100) : 100;

    return [
        'upcoming_trips' => $upcoming,
        'completed_trips' => $completed,
        'safety_score' => $score,
        'fuel_used' => number_format($fuel, 1)
    ];
}

// 2. Get Real Trips (Assigned/Active)
function getAssignedTrips() {
    global $conn;
    $driver_id = getCurrentDriverId();
    
    // Join with vehicles to get plate/model
    $sql = "SELECT d.id, d.tracking_id, d.dispatch_date as date, d.start_location as pickup, d.destination, d.status, 
                   v.plate as vehicle, v.model
            FROM dispatches d
            LEFT JOIN vehicles v ON d.vehicle_id = v.id
            WHERE d.driver_id = ? AND d.status NOT IN ('Completed', 'Cancelled')
            ORDER BY d.dispatch_date ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 3. Get Current Active Trip (The one happening NOW)
function getCurrentActiveTrip() {
    global $conn;
    $driver_id = getCurrentDriverId();
    
    $sql = "SELECT d.*, v.plate, v.model 
            FROM dispatches d
            LEFT JOIN vehicles v ON d.vehicle_id = v.id
            WHERE d.driver_id = ? AND d.status = 'On Duty'
            LIMIT 1";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// 4. Get Fuel Logs
function getFuelLogs() {
    global $conn;
    $driver_id = getCurrentDriverId();
    
    $sql = "SELECT f.*, v.plate as vehicle 
            FROM fuel_expenses f
            LEFT JOIN vehicles v ON f.vehicle_id = v.id
            WHERE f.driver_id = ?
            ORDER BY f.date DESC LIMIT 20";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $driver_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 5. Submit New Fuel Log
function submitFuelLog($data, $files) {
    global $conn;
    $driver_id = getCurrentDriverId();
    $cost = $data['cost'];
    $liters = $data['liters'];
    $odometer = $data['odometer'];
    
    // Find current vehicle (Assuming driver is assigned to one 'On Duty' vehicle)
    $activeTrip = getCurrentActiveTrip();
    $vehicle_id = $activeTrip['vehicle_id'] ?? 0;
    
    if($vehicle_id == 0) return ['status' => false, 'message' => 'No active vehicle found. Start a trip first.'];

    // Handle File Upload (Receipt)
    $receipt_path = "";
    if (isset($files['receipt']) && $files['receipt']['error'] == 0) {
        $target_dir = "../../uploads/receipts/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $filename = time() . "_" . basename($files['receipt']['name']);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($files['receipt']['tmp_name'], $target_file)) {
            $receipt_path = "uploads/receipts/" . $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO fuel_expenses (vehicle_id, driver_id, date, liters, cost, odometer, receipt_image, status) VALUES (?, ?, NOW(), ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iiddds", $vehicle_id, $driver_id, $liters, $cost, $odometer, $receipt_path);
    
    if ($stmt->execute()) {
        return ['status' => true, 'message' => 'Fuel log submitted successfully!'];
    } else {
        return ['status' => false, 'message' => 'Database error: ' . $conn->error];
    }
}
?>