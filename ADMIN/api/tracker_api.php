<?php
require_once '../includes/db.php'; 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json; charset=UTF-8");

// Verify connection from db.php exists
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$action = $_GET['action'] ?? '';

// --- ACTION 1: GET DRIVERS ---
if ($action == 'get_drivers') {
    // Only get Active drivers
    $sql = "SELECT id, full_name, status FROM drivers WHERE status = 'Active'";
    $result = mysqli_query($conn, $sql);
    
    $drivers = [];
    while($row = mysqli_fetch_assoc($result)) {
        $drivers[] = $row;
    }
    echo json_encode($drivers);
}

// --- ACTION 2: LOGIN (For App) ---
if ($action == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Secure query
    $sql = "SELECT user_id, password, role FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Compare password (In a real app, use password_verify)
        if ($password == $row['password']) { 
            
            // Find linked Driver ID
            $u_id = $row['user_id'];
            $d_sql = "SELECT id, full_name FROM drivers WHERE user_id = $u_id";
            $d_res = mysqli_query($conn, $d_sql);
            
            if ($d_row = mysqli_fetch_assoc($d_res)) {
                echo json_encode([
                    "status" => "success",
                    "driver_id" => $d_row['id'],
                    "name" => $d_row['full_name']
                ]);
            } else {
                echo json_encode(["status" => "error", "message" => "User is not a Driver"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Wrong Password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User Not Found"]);
    }
}

// --- ACTION 3: SUBMIT FUEL ---
if ($action == 'submit_fuel') {
    $driver_id = $_POST['driver_id'];
    $liters = $_POST['liters'];
    $amount = $_POST['cost'];
    $date = date("Y-m-d");
    
    // Get Driver Name
    $d_sql = "SELECT full_name FROM drivers WHERE id = $driver_id";
    $d_res = mysqli_query($conn, $d_sql);
    $d_row = mysqli_fetch_assoc($d_res);
    $name = $d_row['full_name'];

    $sql = "INSERT INTO expense_requests (expense_type, requested_by, department, vehicle_id, driver_id, liters, amount, request_date, status) 
            VALUES ('Fuel', '$name', 'Logistic 2', 1, '$driver_id', '$liters', '$amount', '$date', 'Pending')";

    if (mysqli_query($conn, $sql)) echo json_encode(["status" => "success"]);
    else echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}

// --- ACTION 4: SUBMIT INCIDENT (SOS/Report) ---
if ($action == 'submit_incident') {
    $driver_id = $_POST['driver_id'];
    $type = $_POST['type']; 
    $severity = $_POST['severity'];
    $desc = $_POST['description'];
    $location = $_POST['location'];
    $date = date("Y-m-d H:i:s");
    $case_num = "CASE-" . date("Ymd-His");

    $sql = "INSERT INTO incident_cases (case_number, driver_id, vehicle_id, type, severity, date, reported_by, location, description, status) 
            VALUES ('$case_num', '$driver_id', 1, '$type', '$severity', '$date', 'Driver', '$location', '$desc', 'Under Investigation')";

    if (mysqli_query($conn, $sql)) echo json_encode(["status" => "success"]);
    else echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
}

// --- ACTION 5: ASSIGN & SAVE TRIP (MAKE IT STICKY) ---
if ($action == 'assign_trip') {
    // 1. Get Data from the Javascript Request
    $data = json_decode(file_get_contents("php://input"), true);
    
    $driver_id = $data['driver_id'];
    $address   = $data['address'];
    $lat       = $data['lat'];
    $lng       = $data['lng'];
    $date      = date("Y-m-d");
    
    // 2. Create a Trip Code (e.g., TRIP-20260213-001)
    $trip_code = "TRIP-" . date("Ymd") . "-" . rand(100, 999);

    // 3. Insert into Database (The 'Sticky' Part)
    // We assume vehicle_id 1 for now (or you can add a vehicle selector later)
    $sql = "INSERT INTO trips (trip_code, driver_id, vehicle_id, route, dispatch_date, status, start_location, end_location, planned_distance) 
            VALUES ('$trip_code', '$driver_id', 1, '$address', '$date', 'Pending', 'HQ', '$address', 10.00)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Trip Saved to DB"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
?>