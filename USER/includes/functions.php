<?php
// ==========================================
// SMART SESSION & SECURITY HEADERS
// ==========================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ONLY SEND HEADERS IF HTML HAS NOT BEEN PRINTED YET
if (!headers_sent()) {
    // FORCE BROWSER NOT TO CACHE THE PAGE (Prevents Back-Button Access)
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // KICK OUT IF NOT LOGGED IN
    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php");
        exit();
    }
} elseif (!isset($_SESSION['user'])) {
    // Fallback Javascript kick-out if headers were already blocked by HTML
    echo "<script>window.location.href = '../login.php';</script>";
    exit();
}

// 1. AUTO-CONNECT TO DATABASE
if (!isset($conn)) {
    $paths = [
        __DIR__ . '/../../includes/db.php',
        __DIR__ . '/../includes/db.php',
        $_SERVER['DOCUMENT_ROOT'] . '/allcaps-main/includes/db.php'
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
}

// 2. HELPER: Get Current Driver ID
function getCurrentDriverId() {
    global $conn;
    if (!isset($_SESSION['user']['user_id'])) return 0;
    
    $user_id = $_SESSION['user']['user_id'];
    
    // Safety check: Ensure $conn exists before querying
    if (!$conn) return 0;
    
    $stmt = $conn->prepare("SELECT id FROM drivers WHERE user_id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($row = $res->fetch_assoc()) {
            return $row['id'];
        }
    }
    return 0;
}

// 3. GET KPI DATA
function getUserKPI() {
    global $conn;
    $driver_id = getCurrentDriverId();
    $upcoming = 0; $completed = 0; $fuel_used = 0; $safety_score = 100;

    if ($driver_id > 0 && $conn) {
        $r1 = $conn->query("SELECT COUNT(*) FROM dispatches WHERE driver_id=$driver_id AND status IN ('Assigned','On Duty')");
        $upcoming = $r1 ? $r1->fetch_row()[0] : 0;

        $r2 = $conn->query("SELECT COUNT(*) FROM dispatches WHERE driver_id=$driver_id AND status='Completed'");
        $completed = $r2 ? $r2->fetch_row()[0] : 0;
        
        $r3 = $conn->query("SELECT SUM(liters) FROM fuel_expenses WHERE driver_id=$driver_id");
        $fuel_used = $r3 ? ($r3->fetch_row()[0] ?? 0) : 0;
        
        $r4 = $conn->query("SELECT safety_score FROM drivers WHERE id=$driver_id");
        $safety_score = $r4 ? ($r4->fetch_assoc()['safety_score'] ?? 100) : 100;
    }

    return [
        'upcoming_trips' => $upcoming,
        'completed_trips' => $completed,
        'safety_score' => $safety_score,
        'fuel_used' => number_format((float)$fuel_used, 1)
    ];
}

// 4. GET ASSIGNED TRIPS
function getAssignedTrips() {
    global $conn;
    $driver_id = getCurrentDriverId();
    if ($driver_id === 0 || !$conn) return [];

    $sql = "SELECT d.id, d.tracking_id, d.dispatch_date as date, d.start_location as pickup, d.destination, d.status, 
                   v.plate as vehicle, v.model
            FROM dispatches d
            LEFT JOIN vehicles v ON d.vehicle_id = v.id
            WHERE d.driver_id = ? 
            ORDER BY d.dispatch_date DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

// 5. GET FUEL LOGS
function getFuelLogs() {
    global $conn;
    $driver_id = getCurrentDriverId();
    if ($driver_id === 0 || !$conn) return [];

    $sql = "SELECT f.*, v.plate as vehicle 
            FROM fuel_expenses f
            LEFT JOIN vehicles v ON f.vehicle_id = v.id
            WHERE f.driver_id = ? 
            ORDER BY f.date DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

// 6. GET COMPLETED HISTORY
function getTripHistory() {
    global $conn;
    $driver_id = getCurrentDriverId();
    if ($driver_id === 0 || !$conn) return [];

    $sql = "SELECT d.*, v.plate, v.model 
            FROM dispatches d
            LEFT JOIN vehicles v ON d.vehicle_id = v.id
            WHERE d.driver_id = ? AND d.status IN ('Completed', 'Cancelled')
            ORDER BY d.dispatch_date DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

// 7. GET ACTIVE TRIP (Assigned or On Duty)
function getCurrentActiveTrip() {
    global $conn;
    $driver_id = getCurrentDriverId();
    if ($driver_id === 0 || !$conn) return null;
    
    $sql = "SELECT d.*, v.plate, v.model 
            FROM dispatches d
            LEFT JOIN vehicles v ON d.vehicle_id = v.id
            WHERE d.driver_id = ? AND d.status IN ('Assigned', 'On Duty')
            ORDER BY d.dispatch_date DESC LIMIT 1";
            
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    return null;
}

// 8. SUBMIT ROUTINE CHECK
function submitRoutineCheck($data) {
    global $conn;
    if (!$conn) return ['status' => false, 'message' => 'Database connection failed.'];
    
    $driver_id = getCurrentDriverId();
    $active = getCurrentActiveTrip();
    $vehicle_id = $active['vehicle_id'] ?? 0;

    if ($vehicle_id == 0) return ['status' => false, 'message' => 'No active vehicle found. Start a trip first.'];

    $stmt = $conn->prepare("INSERT INTO vehicle_inspections 
        (driver_id, vehicle_id, type, battery_status, lights_status, oil_status, water_status, brakes_status, air_status, gas_status, engine_status, tire_status, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("iisssssssssss", $driver_id, $vehicle_id, $data['type'], $data['battery'], $data['lights'], $data['oil'], $data['water'], $data['brakes'], $data['air'], $data['gas'], $data['engine'], $data['tire'], $data['notes']);
        if ($stmt->execute()) return ['status' => true, 'message' => 'Inspection logged successfully!'];
    }
    return ['status' => false, 'message' => 'Error saving inspection.'];
}

// 9. REPORT INCIDENT
function reportIncident($data, $files) {
    global $conn;
    if (!$conn) return false;
    
    $driver_id = getCurrentDriverId();
    $active = getCurrentActiveTrip();
    $vehicle_id = $active['vehicle_id'] ?? 0;
    
    $type = $data['type'];
    $location = $data['location'];
    $description = $data['description'];
    $severity = $data['severity'];
    
    $photo_path = "";
    if (isset($files['evidence']) && $files['evidence']['error'] == 0) {
        $target_dir = "../../uploads/incidents/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $filename = time() . "_" . basename($files['evidence']['name']);
        if (move_uploaded_file($files['evidence']['tmp_name'], $target_dir . $filename)) {
            $photo_path = "uploads/incidents/" . $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO incidents (driver_id, vehicle_id, type, location, description, severity, photos, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
    if ($stmt) {
        $stmt->bind_param("iisssss", $driver_id, $vehicle_id, $type, $location, $description, $severity, $photo_path);
        return $stmt->execute();
    }
    return false;
}

// Aliases
function getTrip() { return getAssignedTrips(); }
function getLogs() { return getFuelLogs(); }

// --- FUEL LOG FUNCTIONS FOR DRIVER ---

function getVehicles() {
    global $conn;
    if (!$conn) return [];
    
    $sql = "SELECT * FROM vehicles ORDER BY plate ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function addFuelExpense($vehicle_id, $date, $liters, $cost, $driver_id) {
    global $conn;
    if (!$conn) return false;
    
    $stmt = $conn->prepare("INSERT INTO fuel_expenses (vehicle_id, date, liters, cost, driver_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isddi", $vehicle_id, $date, $liters, $cost, $driver_id);
        return $stmt->execute();
    }
    return false;
}

// ==========================================
// NEW PROFILE & VEHICLE FUNCTIONS (FIXED FOR MYSQLI)
// ==========================================

/**
 * Fetches the vehicle PERMANENTLY assigned to the logged-in driver.
 */
function getAssignedVehicle($driver_id) {
    global $conn; 
    
    // Pulls the exact vehicle assigned to them in the Admin panel
    $sql = "SELECT v.* FROM drivers d 
            JOIN vehicles v ON d.assigned_vehicle_id = v.id 
            WHERE d.user_id = ? LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Fetches full driver details for the profile modal
 */
function getDriverProfileDetails($driver_id) {
    global $conn; 
    
    // Uses the exact columns from your 'drivers' table screenshot
    $sql = "SELECT user_id, full_name, license, emergency_contact, profile_picture FROM drivers WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $driver_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}
?>